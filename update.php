<?php
/**
 * OnePagerCMS Update Script
 *
 * Anleitung:
 * 1. Neue Repository-Dateien herunterladen
 * 2. Alle Dateien AUSSER database/SQLiteDatabase.db auf den Server laden
 * 3. Diese Datei im Browser aufrufen: https://ihre-domain.de/update.php
 * 4. Diese Datei nach erfolgreicher Ausführung vom Server löschen!
 */

$lockFile = __DIR__ . '/.update.lock';
$dbPath   = __DIR__ . '/database/SQLiteDatabase.db';

$results  = [];
$hasError = false;

function addResult(array &$results, string $label, string $status, string $detail = ''): void {
    $results[] = ['label' => $label, 'status' => $status, 'detail' => $detail];
}

// ── helpers ──────────────────────────────────────────────────────────────────

function tableExists(PDO $db, string $table): bool {
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name=" . $db->quote($table));
    return $stmt && $stmt->fetchColumn() !== false;
}

function columnExists(PDO $db, string $table, string $column): bool {
    $stmt = $db->query("PRAGMA table_info($table)");
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
        if ($col['name'] === $column) return true;
    }
    return false;
}

function runSQL(PDO $db, array &$results, string $label, string $sql): bool {
    try {
        $db->exec($sql);
        addResult($results, $label, 'ok');
        return true;
    } catch (PDOException $e) {
        addResult($results, $label, 'error', $e->getMessage());
        return false;
    }
}

// ── already ran? ──────────────────────────────────────────────────────────────

$alreadyRan = file_exists($lockFile);

if (!$alreadyRan) {
    // ── connect ───────────────────────────────────────────────────────────────

    if (!file_exists($dbPath)) {
        $hasError = true;
        addResult($results, 'Datenbankdatei', 'error',
            "Nicht gefunden: $dbPath – Bitte zuerst die Anwendung installieren.");
    } else {
        try {
            $db = new PDO('sqlite:' . $dbPath);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            addResult($results, 'Datenbankverbindung', 'ok');
        } catch (PDOException $e) {
            $hasError = true;
            addResult($results, 'Datenbankverbindung', 'error', $e->getMessage());
            $db = null;
        }

        if (isset($db)) {

            // ── sections: sid → id ─────────────────────────────────────────
            if (tableExists($db, 'sections')) {
                $hasSid = columnExists($db, 'sections', 'sid');
                $hasId  = columnExists($db, 'sections', 'id');

                if ($hasSid && !$hasId) {
                    // SQLite ≥ 3.25 supports RENAME COLUMN
                    $sqliteVersion = $db->query('SELECT sqlite_version()')->fetchColumn();
                    if (version_compare($sqliteVersion, '3.25.0', '>=')) {
                        runSQL($db, $results, 'sections: Spalte sid → id umbenennen',
                            'ALTER TABLE sections RENAME COLUMN sid TO id');
                    } else {
                        $hasError = true;
                        addResult($results, 'sections: Spalte sid → id umbenennen', 'error',
                            "SQLite $sqliteVersion unterstützt RENAME COLUMN nicht (benötigt ≥ 3.25). Bitte manuell migrieren.");
                    }
                } elseif ($hasId) {
                    addResult($results, 'sections: Spalte sid → id umbenennen', 'skipped', 'Spalte id existiert bereits');
                }

                // sections.position
                if (!columnExists($db, 'sections', 'position')) {
                    runSQL($db, $results, 'sections: Spalte position hinzufügen',
                        'ALTER TABLE sections ADD COLUMN position int NOT NULL DEFAULT 0');
                } else {
                    addResult($results, 'sections: Spalte position hinzufügen', 'skipped', 'Bereits vorhanden');
                }
            }

            // ── users.email ────────────────────────────────────────────────
            if (tableExists($db, 'users') && !columnExists($db, 'users', 'email')) {
                runSQL($db, $results, 'users: Spalte email hinzufügen',
                    'ALTER TABLE users ADD COLUMN email VARCHAR(50) NULL');
            } elseif (tableExists($db, 'users')) {
                addResult($results, 'users: Spalte email hinzufügen', 'skipped', 'Bereits vorhanden');
            }

            // ── standard.background ────────────────────────────────────────
            if (tableExists($db, 'standard') && !columnExists($db, 'standard', 'background')) {
                runSQL($db, $results, 'standard: Spalte background hinzufügen',
                    'ALTER TABLE standard ADD COLUMN background varchar(100)');
            } elseif (tableExists($db, 'standard')) {
                addResult($results, 'standard: Spalte background hinzufügen', 'skipped', 'Bereits vorhanden');
            }

            // ── icons.background ───────────────────────────────────────────
            if (tableExists($db, 'icons') && !columnExists($db, 'icons', 'background')) {
                runSQL($db, $results, 'icons: Spalte background hinzufügen',
                    'ALTER TABLE icons ADD COLUMN background varchar(100) NULL');
            } elseif (tableExists($db, 'icons')) {
                addResult($results, 'icons: Spalte background hinzufügen', 'skipped', 'Bereits vorhanden');
            }

            // ── contact.background ─────────────────────────────────────────
            if (tableExists($db, 'contact') && !columnExists($db, 'contact', 'background')) {
                runSQL($db, $results, 'contact: Spalte background hinzufügen',
                    'ALTER TABLE contact ADD COLUMN background varchar(100) NULL');
            } elseif (tableExists($db, 'contact')) {
                addResult($results, 'contact: Spalte background hinzufügen', 'skipped', 'Bereits vorhanden');
            }

            // ── contact.receiverMail ───────────────────────────────────────
            if (tableExists($db, 'contact') && !columnExists($db, 'contact', 'receiverMail')) {
                runSQL($db, $results, 'contact: Spalte receiverMail hinzufügen',
                    'ALTER TABLE contact ADD COLUMN receiverMail varchar(50) NULL');
            } elseif (tableExists($db, 'contact')) {
                addResult($results, 'contact: Spalte receiverMail hinzufügen', 'skipped', 'Bereits vorhanden');
            }

            // ── header table ───────────────────────────────────────────────
            if (!tableExists($db, 'header')) {
                runSQL($db, $results, 'Tabelle header erstellen',
                    'CREATE TABLE header (
                        specialid int PRIMARY KEY,
                        mutedtitle TEXT,
                        title TEXT,
                        background varchar(100) NULL,
                        customrow TEXT NULL
                    )');
            } else {
                addResult($results, 'Tabelle header erstellen', 'skipped', 'Bereits vorhanden');
                if (!columnExists($db, 'header', 'customrow')) {
                    runSQL($db, $results, 'header: Spalte customrow hinzufügen',
                        'ALTER TABLE header ADD COLUMN customrow TEXT NULL');
                } else {
                    addResult($results, 'header: Spalte customrow hinzufügen', 'skipped', 'Bereits vorhanden');
                }
            }

            // ── footer table ───────────────────────────────────────────────
            if (!tableExists($db, 'footer')) {
                runSQL($db, $results, 'Tabelle footer erstellen',
                    'CREATE TABLE footer (
                        fid int PRIMARY KEY,
                        custom TEXT,
                        facebook_page VARCHAR(50),
                        twitter_page VARCHAR(50),
                        linkedin_page VARCHAR(50),
                        custom_page varchar(100),
                        copyright boolean,
                        custom_icon VARCHAR(30) NULL
                    )');
            } else {
                addResult($results, 'Tabelle footer erstellen', 'skipped', 'Bereits vorhanden');
                if (!columnExists($db, 'footer', 'custom_icon')) {
                    runSQL($db, $results, 'footer: Spalte custom_icon hinzufügen',
                        'ALTER TABLE footer ADD COLUMN custom_icon VARCHAR(30) NULL');
                } else {
                    addResult($results, 'footer: Spalte custom_icon hinzufügen', 'skipped', 'Bereits vorhanden');
                }
            }

            // ── settings table ─────────────────────────────────────────────
            if (!tableExists($db, 'settings')) {
                runSQL($db, $results, 'Tabelle settings erstellen',
                    'CREATE TABLE settings (
                        id int PRIMARY KEY,
                        setting VARCHAR(255),
                        value TEXT
                    )');
            } else {
                addResult($results, 'Tabelle settings erstellen', 'skipped', 'Bereits vorhanden');
            }

            // ── error table ────────────────────────────────────────────────
            if (!tableExists($db, 'error')) {
                runSQL($db, $results, 'Tabelle error erstellen',
                    'CREATE TABLE error (
                        id int PRIMARY KEY,
                        reason VARCHAR(255),
                        headline VARCHAR(255),
                        message TEXT
                    )');
            } else {
                addResult($results, 'Tabelle error erstellen', 'skipped', 'Bereits vorhanden');
            }

            // ── success table ──────────────────────────────────────────────
            if (!tableExists($db, 'success')) {
                runSQL($db, $results, 'Tabelle success erstellen',
                    'CREATE TABLE success (
                        id int PRIMARY KEY,
                        reason VARCHAR(50),
                        headline VARCHAR(100),
                        message TEXT
                    )');
            } else {
                addResult($results, 'Tabelle success erstellen', 'skipped', 'Bereits vorhanden');
            }

            // ── faq table ──────────────────────────────────────────────────
            if (!tableExists($db, 'faq')) {
                runSQL($db, $results, 'Tabelle faq erstellen',
                    'CREATE TABLE faq (
                        id int PRIMARY KEY,
                        question text,
                        answer text,
                        category VARCHAR(255) NULL
                    )');
            } else {
                addResult($results, 'Tabelle faq erstellen', 'skipped', 'Bereits vorhanden');
                if (!columnExists($db, 'faq', 'category')) {
                    runSQL($db, $results, 'faq: Spalte category hinzufügen',
                        'ALTER TABLE faq ADD COLUMN category VARCHAR(255) NULL');
                } else {
                    addResult($results, 'faq: Spalte category hinzufügen', 'skipped', 'Bereits vorhanden');
                }
            }

            // ── additionalPages table ──────────────────────────────────────
            if (!tableExists($db, 'additionalPages')) {
                runSQL($db, $results, 'Tabelle additionalPages erstellen',
                    'CREATE TABLE additionalPages (
                        id int PRIMARY KEY,
                        title VARCHAR(40),
                        content TEXT,
                        showInFooter boolean
                    )');
            } else {
                addResult($results, 'Tabelle additionalPages erstellen', 'skipped', 'Bereits vorhanden');
            }

            // ── write lock file ────────────────────────────────────────────
            if (!$hasError) {
                file_put_contents($lockFile, date('Y-m-d H:i:s'));
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>OnePagerCMS – Update</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
               background: #f4f6f9; color: #333; margin: 0; padding: 2rem 1rem; }
        .container { max-width: 760px; margin: 0 auto; }
        h1 { font-size: 1.6rem; margin-bottom: .25rem; }
        .subtitle { color: #666; margin-bottom: 2rem; font-size: .95rem; }

        .warning { background: #fff3cd; border: 1px solid #ffc107; border-radius: 6px;
                   padding: 1rem 1.25rem; margin-bottom: 1.5rem; }
        .warning strong { color: #856404; }

        .success-box { background: #d1e7dd; border: 1px solid #0f5132; border-radius: 6px;
                       padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #0f5132; }
        .error-box   { background: #f8d7da; border: 1px solid #842029; border-radius: 6px;
                       padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #842029; }
        .info-box    { background: #cff4fc; border: 1px solid #055160; border-radius: 6px;
                       padding: 1rem 1.25rem; margin-bottom: 1.5rem; color: #055160; }

        table { width: 100%; border-collapse: collapse; background: #fff;
                border-radius: 6px; overflow: hidden;
                box-shadow: 0 1px 4px rgba(0,0,0,.08); }
        th { background: #343a40; color: #fff; text-align: left;
             padding: .65rem 1rem; font-size: .85rem; }
        td { padding: .6rem 1rem; border-bottom: 1px solid #e9ecef; font-size: .9rem; vertical-align: top; }
        tr:last-child td { border-bottom: none; }

        .badge { display: inline-block; padding: .2em .55em; border-radius: 4px;
                 font-size: .78rem; font-weight: 600; white-space: nowrap; }
        .badge-ok      { background: #d1e7dd; color: #0f5132; }
        .badge-skipped { background: #e2e3e5; color: #41464b; }
        .badge-error   { background: #f8d7da; color: #842029; }

        .detail { font-size: .78rem; color: #666; margin-top: .2rem; }

        .delete-hint { margin-top: 2rem; padding: 1rem 1.25rem; background: #fff;
                       border: 2px solid #dc3545; border-radius: 6px; }
        .delete-hint strong { color: #dc3545; }
        code { background: #f1f3f5; padding: .1em .35em; border-radius: 3px;
               font-family: monospace; font-size: .9em; }
        footer { margin-top: 2.5rem; text-align: center; font-size: .8rem; color: #aaa; }
    </style>
</head>
<body>
<div class="container">
    <h1>OnePagerCMS – Update</h1>
    <p class="subtitle">Datenbank-Migrationen für bestehende Installationen</p>

    <?php if ($alreadyRan): ?>
        <div class="info-box">
            <strong>Update wurde bereits ausgeführt.</strong><br>
            Die Datei <code>.update.lock</code> verhindert eine erneute Ausführung.<br>
            Um das Update erneut auszuführen, löschen Sie <code>.update.lock</code> vom Server.
        </div>
    <?php elseif ($hasError): ?>
        <div class="error-box">
            <strong>Update mit Fehlern abgeschlossen.</strong>
            Bitte prüfen Sie die rot markierten Einträge unten.
        </div>
    <?php else: ?>
        <div class="success-box">
            <strong>Update erfolgreich abgeschlossen!</strong>
            Alle Migrationen wurden durchgeführt.
        </div>
    <?php endif; ?>

    <?php if (!$alreadyRan && !empty($results)): ?>
    <table>
        <thead>
            <tr>
                <th>Migration</th>
                <th>Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($results as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['label']) ?></td>
                <td>
                    <?php if ($r['status'] === 'ok'): ?>
                        <span class="badge badge-ok">&#10003; Ausgeführt</span>
                    <?php elseif ($r['status'] === 'skipped'): ?>
                        <span class="badge badge-skipped">&#8211; Übersprungen</span>
                    <?php else: ?>
                        <span class="badge badge-error">&#10007; Fehler</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if (!empty($r['detail'])): ?>
                        <div class="detail"><?= htmlspecialchars($r['detail']) ?></div>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div class="delete-hint">
        <strong>&#9888; Wichtig:</strong> Bitte löschen Sie diese Datei (<code>update.php</code>)
        nach der Ausführung von Ihrem Server. Sie wird nicht mehr benötigt und sollte aus
        Sicherheitsgründen entfernt werden.
    </div>

    <div class="warning" style="margin-top:1rem;">
        <strong>Nächste Schritte:</strong>
        <ol style="margin:.5rem 0 0; padding-left:1.25rem;">
            <li>Prüfen Sie, ob alle Migrationen erfolgreich waren (grüne Badges).</li>
            <li>Testen Sie das Admin-Backend und das Frontend Ihrer Website.</li>
            <li>Löschen Sie <code>update.php</code> vom Server.</li>
        </ol>
    </div>

    <footer>OnePagerCMS Update-Script &mdash; bitte nach Benutzung löschen</footer>
</div>
</body>
</html>
