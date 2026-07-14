<?php

include_once "../database/SQLSettingActions.php";

class SQLSpamProtectionActions
{
    const SETTING_SECRET = 'contact-form-secret';
    const MIN_AGE = 3;
    const MAX_AGE = 86400;
    const RATE_LIMIT = 3;
    const RATE_WINDOW = 600;
    const RETENTION = 3600;

    private $secret;

    public function createFormToken($contactId): string
    {
        $timestamp = time();
        return $timestamp . '.' . hash_hmac('sha256', $timestamp . '|' . (int)$contactId, $this->getSecret());
    }

    public function verifyFormToken($token, $contactId): string
    {
        $parts = explode('.', (string)$token);
        if (count($parts) !== 2 || !ctype_digit($parts[0]) || $parts[1] === '') {
            return 'invalid';
        }
        $expected = hash_hmac('sha256', $parts[0] . '|' . (int)$contactId, $this->getSecret());
        if (!hash_equals($expected, $parts[1])) {
            return 'invalid';
        }
        $age = time() - (int)$parts[0];
        if ($age < self::MIN_AGE) {
            return 'toofast';
        }
        if ($age > self::MAX_AGE) {
            return 'expired';
        }
        return 'ok';
    }

    public function getFormFieldsHtml($contactId): string
    {
        return '<input type="hidden" name="formToken" value="' . $this->createFormToken($contactId) . '">'
            . '<div style="position:absolute;left:-9999px;top:-9999px;" aria-hidden="true">'
            . '<label for="opcms-website">Leave this field empty</label>'
            . '<input type="text" id="opcms-website" name="website" tabindex="-1" autocomplete="off" value="">'
            . '</div>';
    }

    public function isHoneypotFilled(array $post): bool
    {
        return isset($post['website']) && trim((string)$post['website']) !== '';
    }

    public function isRateLimited($ip): bool
    {
        include '../database/connect.php';
        try {
            $this->ensureThrottleTable($db);

            $prune = $db->prepare('DELETE FROM contact_throttle WHERE created < :cutoff;');
            $prune->bindValue(':cutoff', time() - self::RETENTION);
            $prune->execute();

            $count = $db->prepare('SELECT count(*) FROM contact_throttle WHERE iphash = :iphash AND created > :windowstart;');
            $count->bindValue(':iphash', $this->hashIp($ip));
            $count->bindValue(':windowstart', time() - self::RATE_WINDOW);
            $count->execute();
            return ((int)$count->fetch()[0]) >= self::RATE_LIMIT;
        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
            return true;
        }
    }

    public function recordSubmission($ip): void
    {
        include '../database/connect.php';
        try {
            $this->ensureThrottleTable($db);
            $insert = $db->prepare('INSERT INTO contact_throttle (`iphash`, `created`) VALUES (:iphash, :created);');
            $insert->bindValue(':iphash', $this->hashIp($ip));
            $insert->bindValue(':created', time());
            $insert->execute();
        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }
    }

    public function ensureContactMessages(): void
    {
        include '../database/connect.php';
        try {
            $errors = array(
                array(550, 'emailnotsent', 'Message not sent', 'Your message could not be sent. Please try again later.'),
                array(551, 'contactratelimited', 'Too many messages', 'You have sent several messages in a short time. Please wait a few minutes and try again.'),
                array(552, 'contactinvalid', 'Invalid input', 'Please fill in all required fields with valid values and try again.'),
                array(553, 'contactexpired', 'Form expired', 'This page was open for too long. Please reload the page and send your message again.'),
            );
            $successes = array(
                array(550, 'emailsent', 'Message sent', 'Thank you! Your message has been sent successfully.'),
            );

            // guard by reason, not only id: older installs may already have
            // emailsent/emailnotsent rows under different ids
            $insertError = $db->prepare('INSERT INTO error (`id`, `reason`, `headline`, `message`)
                SELECT :id, :reason, :headline, :message
                WHERE NOT EXISTS (SELECT 1 FROM error WHERE reason = :reason OR id = :id)');
            foreach ($errors as $row) {
                $insertError->bindValue(':id', $row[0]);
                $insertError->bindValue(':reason', $row[1]);
                $insertError->bindValue(':headline', $row[2]);
                $insertError->bindValue(':message', $row[3]);
                $insertError->execute();
            }

            $insertSuccess = $db->prepare('INSERT INTO success (`id`, `reason`, `headline`, `message`)
                SELECT :id, :reason, :headline, :message
                WHERE NOT EXISTS (SELECT 1 FROM success WHERE reason = :reason OR id = :id)');
            foreach ($successes as $row) {
                $insertSuccess->bindValue(':id', $row[0]);
                $insertSuccess->bindValue(':reason', $row[1]);
                $insertSuccess->bindValue(':headline', $row[2]);
                $insertSuccess->bindValue(':message', $row[3]);
                $insertSuccess->execute();
            }
        } catch (Exception $exception) {
            error_log('OPCMS DB error: ' . $exception->getMessage());
        }
    }

    private function getSecret(): string
    {
        if ($this->secret !== null) {
            return $this->secret;
        }
        $settingactions = new SQLSettingActions();
        $secret = $settingactions->getSettingValue(self::SETTING_SECRET);
        if ($secret === null || $secret === '') {
            $secret = bin2hex(random_bytes(32));
            $settingactions->setSettingValue(self::SETTING_SECRET, $secret);
        }
        $this->secret = $secret;
        return $secret;
    }

    private function hashIp($ip): string
    {
        return hash_hmac('sha256', (string)$ip, $this->getSecret());
    }

    private function ensureThrottleTable($db): void
    {
        $db->exec('CREATE TABLE IF NOT EXISTS contact_throttle (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            iphash VARCHAR(64) NOT NULL,
            created INTEGER NOT NULL
        );');
        $db->exec('CREATE INDEX IF NOT EXISTS idx_contact_throttle ON contact_throttle (iphash, created);');
    }
}
