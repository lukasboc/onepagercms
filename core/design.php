<?php
include '../database/SQLSettingActions.php';
$settingActions = new SQLSettingActions();
$primaryColor = ($settingActions->getSettingValue('text-primary') !== null && $settingActions->getSettingValue('text-primary') !== '') ? $settingActions->getSettingValue('text-primary') : '';
$buttonColor = ($settingActions->getSettingValue('button-color') !== null && $settingActions->getSettingValue('button-color') !== '') ? $settingActions->getSettingValue('button-color') : '';
$customcss = ($settingActions->getSettingValue('custom-css') !== null && $settingActions->getSettingValue('custom-css') !== '') ? $settingActions->getSettingValue('custom-css') : '';
$navbackgroundcolor = ($settingActions->getSettingValue('navigation-color') !== null && $settingActions->getSettingValue('navigation-color') !== '') ? $settingActions->getSettingValue('navigation-color') : '';
$navtextcolor = ($settingActions->getSettingValue('navigationtext-color') !== null && $settingActions->getSettingValue('navigationtext-color') !== '') ? $settingActions->getSettingValue('navigationtext-color') : '';
?>

<!DOCTYPE html>
<html>
<?php require_once '../core/inc/head.php' ?>

<body>
<?php include_once '../core/inc/header.php' ?>
<div class="container">
    <h1>Design</h1>
    <h2>Colors</h2>
    <form method="post" action="../misc/changeprimarycolor.php">
                <?php echo opcms_csrf_field(); ?>
        <div class="mb-4">
            <label class="label" for="primaryColor">Primary:</label>
            <div class="join w-full">
                <input type="text" name="primaryColor" id="primaryColor" class="input join-item w-full"
                       value="<?php echo $primaryColor ?>">
                <input type='submit' class="btn btn-primary join-item" name='action'
                       id='change' value='Update'>
            </div>
        </div>
    </form>
    <form method="post" action="../misc/changebuttoncolor.php">
                <?php echo opcms_csrf_field(); ?>
        <div class="mb-4">
            <label class="label" for="buttonColor">Buttons:</label>
            <div class="join w-full">
                <input type="text" name="buttonColor" id="buttonColor" class="input join-item w-full"
                       value="<?php echo $buttonColor ?>">
                <input type='submit' class="btn btn-primary join-item" name='action'
                       id='change' value='Update'>
            </div>
        </div>
    </form>

    <form method="post" action="../misc/changenavbackgroundcolor.php">
                <?php echo opcms_csrf_field(); ?>
        <div class="mb-4">
            <label class="label" for="navBackgroundColor">Navigation Background:</label>
            <div class="join w-full">
                <input type="text" name="navigation-color" id="navBackgroundColor" class="input join-item w-full"
                       value="<?php echo $navbackgroundcolor ?>">
                <input type='submit' class="btn btn-primary join-item" name='action'
                       id='change' value='Update'>
            </div>
        </div>
    </form>

    <form method="post" action="../misc/changenavtextcolor.php">
                <?php echo opcms_csrf_field(); ?>
        <div class="mb-4">
            <label class="label" for="navTextColor">Navigation Text:</label>
            <div class="join w-full">
                <input type="text" name="navigationtext-color" id="navTextColor" class="input join-item w-full"
                       value="<?php echo $navtextcolor ?>">
                <input type='submit' class="btn btn-primary join-item" name='action'
                       id='change' value='Update'>
            </div>
        </div>
    </form>

    <h2>Custom CSS</h2>
    <form method="post" action="../misc/changecustomcss.php">
                <?php echo opcms_csrf_field(); ?>
        <div class="mb-4">
            <label class="label" for="customcss">CSS:</label>
            <textarea class="textarea w-full font-mono" id="customcss" name="customcss"
                      placeholder=".class { ... }"
                      rows="10"><?php echo $customcss ?></textarea>
        </div>
        <div class="mb-4 text-center">
            <input class="btn btn-success" type="submit" name="changecustomcss" value="Save">
        </div>
    </form>

    <h2>Themes</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <?php
        $activeThemeSlug = (function_exists('opcms_theme')) ? opcms_theme()->getActiveTheme() : 'agency';
        foreach (glob('../themes/*/theme.json') as $themeManifestPath) {
            $themeManifest = json_decode(file_get_contents($themeManifestPath), true);
            if (!is_array($themeManifest) || empty($themeManifest['slug'])) {
                continue;
            }
            $themeSlug = $themeManifest['slug'];
            $themeDir = dirname($themeManifestPath);
            $isActiveTheme = ($themeSlug === $activeThemeSlug);
            ?>
            <div class="card card-border bg-base-100 shadow-sm<?php if ($isActiveTheme) echo ' border-success'; ?>">
                <?php if (file_exists($themeDir . '/screenshot.png')): ?>
                    <figure><img src="<?php echo htmlspecialchars($themeDir . '/screenshot.png') ?>" alt="Theme screenshot"></figure>
                <?php endif; ?>
                <div class="card-body p-4">
                    <h5 class="card-title text-base"><?php echo htmlspecialchars(isset($themeManifest['name']) ? $themeManifest['name'] : $themeSlug) ?></h5>
                    <p class="text-sm"><small class="text-base-content/60">Version <?php echo htmlspecialchars(isset($themeManifest['version']) ? $themeManifest['version'] : '?') ?></small></p>
                    <?php if ($isActiveTheme): ?>
                        <div><span class="badge badge-success">Active</span></div>
                    <?php else: ?>
                        <form method="post" action="../misc/activatetheme.php">
                <?php echo opcms_csrf_field(); ?>
                            <input type="hidden" name="slug" value="<?php echo htmlspecialchars($themeSlug) ?>">
                            <input type="submit" class="btn btn-sm btn-primary" value="Activate">
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php
    $activeThemeOptions = (function_exists('opcms_theme') && method_exists(opcms_theme(), 'getDeclaredOptions'))
        ? opcms_theme()->getDeclaredOptions() : array();
    if (count($activeThemeOptions) > 0):
        ?>
        <h2>Theme Options</h2>
        <div class="card card-border bg-base-100 shadow-sm mb-4">
            <div class="card-body">
                <p class="text-base-content/60"><small>Options provided by the active theme. Leave a field empty to use the theme default.</small></p>
                <form method="post" action="../misc/savethemeoptions.php">
                <?php echo opcms_csrf_field(); ?>
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars($activeThemeSlug) ?>">
                    <?php foreach ($activeThemeOptions as $themeOption):
                        $themeOptionSaved = $settingActions->getSettingValue('theme-option:' . $activeThemeSlug . ':' . $themeOption['key']);
                        $themeOptionSaved = ($themeOptionSaved === null) ? '' : $themeOptionSaved;
                        $themeOptionField = 'theme-option-' . $themeOption['key'];
                        ?>
                        <div class="mb-4">
                            <label class="label" for="<?php echo htmlspecialchars($themeOptionField) ?>"><?php echo htmlspecialchars($themeOption['label']) ?>:</label>
                            <?php if ($themeOption['type'] === 'select'): ?>
                                <select class="select w-full" id="<?php echo htmlspecialchars($themeOptionField) ?>"
                                        name="options[<?php echo htmlspecialchars($themeOption['key']) ?>]">
                                    <?php
                                    $themeOptionSelected = ($themeOptionSaved !== '') ? $themeOptionSaved : $themeOption['default'];
                                    foreach ($themeOption['choices'] as $themeOptionChoice): ?>
                                        <option value="<?php echo htmlspecialchars($themeOptionChoice) ?>"<?php if ($themeOptionChoice === $themeOptionSelected) echo ' selected'; ?>><?php echo htmlspecialchars($themeOptionChoice) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php else: ?>
                                <input type="text" class="input w-full" id="<?php echo htmlspecialchars($themeOptionField) ?>"
                                       name="options[<?php echo htmlspecialchars($themeOption['key']) ?>]"
                                       value="<?php echo htmlspecialchars($themeOptionSaved) ?>"
                                       placeholder="<?php echo htmlspecialchars($themeOption['default']) ?>">
                            <?php endif; ?>
                            <?php if ($themeOption['description'] !== ''): ?>
                                <small class="text-sm text-base-content/60"><?php echo htmlspecialchars($themeOption['description']) ?></small>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <input type="submit" class="btn btn-success" value="Save Options">
                </form>
                <form method="post" action="../misc/savethemeoptions.php" class="mt-2">
                <?php echo opcms_csrf_field(); ?>
                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars($activeThemeSlug) ?>">
                    <input type="hidden" name="reset" value="1">
                    <input type="submit" class="btn btn-sm btn-outline" value="Reset to Defaults">
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>


<?php include_once 'inc/footer.php' ?>
</body>
</html>
