<?php
require_once '../system/bootstrap.php';
require_once '../system/MarketplaceClient.php';
include_once '../database/SQLExtensionActions.php';
include_once '../database/SQLSettingActions.php';
$extensionActions = new SQLExtensionActions();
$extensionActions->ensureMessages();
$settingActions = new SQLSettingActions();
$installedExtensions = $extensionActions->getAllExtensions();
$activeTheme = $settingActions->getSettingValue('active-theme');
$zipAvailable = class_exists('ZipArchive');

$marketplaceClient = new MarketplaceClient();
$httpAvailable = $marketplaceClient->isAvailable();

$activeTab = isset($_GET['tab']) ? $_GET['tab'] : 'installed';
if (!in_array($activeTab, array('installed', 'marketplace', 'upload'), true)) {
    $activeTab = 'installed';
}
$marketplaceType = (isset($_GET['type']) && in_array($_GET['type'], array('plugin', 'theme'), true)) ? $_GET['type'] : '';
$marketplaceSearch = isset($_GET['search']) ? trim($_GET['search']) : '';
$marketplacePage = isset($_GET['mpage']) ? max(1, (int)$_GET['mpage']) : 1;

$installedBySlug = array();
foreach ($installedExtensions as $installedExtension) {
    $installedBySlug[$installedExtension['slug']] = $installedExtension;
}

// Update check for extensions installed from the marketplace (cached inside the client).
$availableUpdates = array();
if ($httpAvailable) {
    $updateCheckPairs = array();
    foreach ($installedExtensions as $installedExtension) {
        if ($installedExtension['source'] === 'marketplace' && (int)$installedExtension['paid'] !== 1) {
            $updateCheckPairs[$installedExtension['slug']] = $installedExtension['version'];
        }
    }
    $availableUpdates = $marketplaceClient->checkUpdates($updateCheckPairs);
}

$marketplaceResult = null;
if ($activeTab === 'marketplace' && $httpAvailable) {
    $marketplaceResult = $marketplaceClient->listItems($marketplaceType, $marketplaceSearch, $marketplacePage);
}
?>
<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php' ?>
<body>
<?php include_once 'inc/header.php' ?>
<div class="container">
    <h1>Extensions</h1>
    <?php if (!$zipAvailable): ?>
        <div class="alert alert-warning"><span>The PHP <code>zip</code> extension (ZipArchive) is missing on this server. Installing extensions from ZIP archives is not possible.</span></div>
    <?php endif; ?>
    <?php if (!$httpAvailable): ?>
        <div class="alert alert-warning"><span>Neither <code>curl</code> nor <code>allow_url_fopen</code> is available. The marketplace cannot be reached from this server; you can still install extensions via ZIP upload.</span></div>
    <?php endif; ?>

    <div class="tabs tabs-lift mt-4" role="tablist">
        <a class="tab<?php if ($activeTab === 'installed') echo ' tab-active'; ?>" role="tab" href="extensions.php?tab=installed">Installed</a>
        <a class="tab<?php if ($activeTab === 'marketplace') echo ' tab-active'; ?>" role="tab" href="extensions.php?tab=marketplace">Marketplace</a>
        <a class="tab<?php if ($activeTab === 'upload') echo ' tab-active'; ?>" role="tab" href="extensions.php?tab=upload">Upload</a>
    </div>

    <div class="pt-4">
        <?php if ($activeTab === 'installed'): ?>
            <?php if (count($installedExtensions) === 0): ?>
                <p>No extensions installed yet. Install one from the <a class="link" href="extensions.php?tab=marketplace">Marketplace</a> or <a class="link" href="extensions.php?tab=upload">upload a ZIP archive</a>.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Version</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($installedExtensions as $installedExtension):
                        $slug = $installedExtension['slug'];
                        $isTheme = $installedExtension['type'] === 'theme';
                        $isPaid = (int)$installedExtension['paid'] === 1;
                        $isActive = $isTheme ? ($activeTheme === $slug) : ((int)$installedExtension['active'] === 1);
                        $hasUpdate = isset($availableUpdates[$slug]);
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($installedExtension['name']) ?><br>
                                <small class="text-base-content/60"><?php echo htmlspecialchars($slug) ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($installedExtension['type']) ?></td>
                            <td><?php echo htmlspecialchars($installedExtension['version']) ?></td>
                            <td>
                                <?php echo $isActive ? '<span class="badge badge-success">active</span>' : '<span class="badge badge-neutral">inactive</span>'; ?>
                                <?php if ($isPaid) echo ' <span class="badge badge-info">paid</span>'; ?>
                                <?php if ($hasUpdate) echo ' <span class="badge badge-warning">update available: ' . htmlspecialchars($availableUpdates[$slug]['new_version']) . '</span>'; ?>
                            </td>
                            <td class="text-right">
                                <?php if ($hasUpdate || $isPaid): ?>
                                    <form class="inline" method="post" action="../misc/extensionupdate.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <input type="submit" class="btn btn-sm btn-warning" value="<?php echo $hasUpdate ? 'Update' : 'Check for update'; ?>">
                                    </form>
                                <?php endif; ?>
                                <?php if ($isActive): ?>
                                    <form class="inline" method="post" action="../misc/extensiondeactivate.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <input type="submit" class="btn btn-sm btn-outline btn-neutral" value="Deactivate">
                                    </form>
                                <?php else: ?>
                                    <form class="inline" method="post" action="../misc/extensionactivate.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <input type="submit" class="btn btn-sm btn-primary" value="Activate">
                                    </form>
                                <?php endif; ?>
                                <form class="inline" method="post" action="../misc/extensiondelete.php"
                                      onsubmit="return confirm('Delete this extension? Its files will be removed.');">
                                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                    <input type="submit" class="btn btn-sm btn-outline btn-error" value="Delete">
                                </form>
                            </td>
                        </tr>
                        <?php if ($isPaid): ?>
                            <tr>
                                <td colspan="5" class="pt-0 border-0">
                                    <form class="flex items-center gap-2 flex-wrap" method="post" action="../misc/extensionlicense.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <label class="text-base-content/60" for="license-<?php echo htmlspecialchars($slug) ?>"><small>License key:</small></label>
                                        <input type="text" class="input input-sm" style="min-width: 280px"
                                               id="license-<?php echo htmlspecialchars($slug) ?>" name="license_key"
                                               value="<?php echo htmlspecialchars((string)$installedExtension['license_key']) ?>"
                                               placeholder="Enter the license key from the developer">
                                        <input type="submit" class="btn btn-sm btn-outline btn-primary" value="Save license">
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                </div>
            <?php endif; ?>

        <?php elseif ($activeTab === 'marketplace'): ?>
            <form class="flex items-center gap-2 flex-wrap mb-4" method="get" action="extensions.php">
                <input type="hidden" name="tab" value="marketplace">
                <select class="select w-auto" name="type">
                    <option value="">All types</option>
                    <option value="plugin" <?php if ($marketplaceType === 'plugin') echo 'selected'; ?>>Plugins</option>
                    <option value="theme" <?php if ($marketplaceType === 'theme') echo 'selected'; ?>>Themes</option>
                </select>
                <input type="text" class="input w-auto" name="search" placeholder="Search..."
                       value="<?php echo htmlspecialchars($marketplaceSearch) ?>">
                <input type="submit" class="btn btn-primary" value="Search">
            </form>
            <form class="mb-4 tooltip" data-tip="Marketplace results are cached for a few hours; refresh to fetch the latest listings." method="post" action="../misc/marketplacerefresh.php">
                <input type="submit" class="btn btn-sm btn-outline btn-neutral" value="Refresh listings">
            </form>

            <?php if (!$httpAvailable): ?>
                <div class="alert alert-warning"><span>The marketplace cannot be reached because this server has no HTTP client available.</span></div>
            <?php elseif ($marketplaceResult === null): ?>
                <div class="alert alert-error"><span>The marketplace could not be reached. Please try again later.</span></div>
            <?php elseif (count($marketplaceResult['data']) === 0): ?>
                <p>No extensions found.</p>
            <?php else: ?>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <?php foreach ($marketplaceResult['data'] as $marketplaceItem):
                        $slug = $marketplaceItem['slug'];
                        $isInstalled = isset($installedBySlug[$slug]);
                        $installedVersion = $isInstalled ? $installedBySlug[$slug]['version'] : null;
                        $hasUpdate = $isInstalled && isset($marketplaceItem['latest_version'])
                            && version_compare($marketplaceItem['latest_version'], $installedVersion, '>');
                        ?>
                        <div class="card card-border bg-base-100 shadow-sm h-full">
                            <div class="card-body p-4">
                                <h5 class="card-title text-base"><?php echo htmlspecialchars($marketplaceItem['name']) ?></h5>
                                <h6 class="text-sm text-base-content/60 mb-2">
                                    <?php echo htmlspecialchars($marketplaceItem['type']) ?>
                                    · v<?php echo htmlspecialchars((string)$marketplaceItem['latest_version']) ?>
                                    · by <?php echo htmlspecialchars((string)$marketplaceItem['author']) ?>
                                    <?php if (!empty($marketplaceItem['is_paid'])) echo ' · <span class="badge badge-info badge-sm">Paid</span>'; ?>
                                </h6>
                                <p class="text-sm"><?php echo htmlspecialchars((string)$marketplaceItem['summary']) ?></p>
                                <p class="text-sm"><small class="text-base-content/60"><?php echo (int)$marketplaceItem['downloads'] ?> downloads</small></p>
                            </div>
                            <div class="px-4 pb-4 mt-auto">
                                <?php if (!empty($marketplaceItem['is_paid'])): ?>
                                    <?php if (!empty($marketplaceItem['purchase_url'])): ?>
                                        <a class="btn btn-sm btn-primary" target="_blank" rel="noopener"
                                           href="<?php echo htmlspecialchars($marketplaceItem['purchase_url']) ?>">Buy on developer site</a>
                                    <?php endif; ?>
                                    <small class="text-base-content/60 block mt-1">After purchase you receive a ZIP and a license key from the developer. Install the ZIP via the Upload tab.</small>
                                <?php elseif ($isInstalled && !$hasUpdate): ?>
                                    <span class="badge badge-success">Installed</span>
                                <?php elseif ($hasUpdate): ?>
                                    <form method="post" action="../misc/extensionupdate.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <input type="submit" class="btn btn-sm btn-warning"
                                               value="Update to <?php echo htmlspecialchars($marketplaceItem['latest_version']) ?>">
                                    </form>
                                <?php else: ?>
                                    <form method="post" action="../misc/extensioninstallremote.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <input type="submit" class="btn btn-sm btn-primary" value="Install"
                                            <?php if (!$zipAvailable) echo 'disabled'; ?>>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($marketplaceResult['meta']['last_page']) && $marketplaceResult['meta']['last_page'] > 1): ?>
                    <nav class="mt-4">
                        <div class="join">
                            <?php for ($p = 1; $p <= (int)$marketplaceResult['meta']['last_page']; $p++): ?>
                                <a class="join-item btn btn-sm<?php if ($p === $marketplacePage) echo ' btn-active'; ?>" href="extensions.php?tab=marketplace&type=<?php echo urlencode($marketplaceType) ?>&search=<?php echo urlencode($marketplaceSearch) ?>&mpage=<?php echo $p ?>"><?php echo $p ?></a>
                            <?php endfor; ?>
                        </div>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>

        <?php else: ?>
            <p>Upload a plugin or theme as a ZIP archive. The archive must contain a <code>plugin.json</code> or
                <code>theme.json</code> manifest.</p>
            <p class="text-base-content/60"><small>Only install extensions from sources you trust &mdash; extension code runs with
                full access to your website.</small></p>
            <form method="post" action="../misc/extensionupload.php" enctype="multipart/form-data">
                <div class="mb-4">
                    <input type="file" class="file-input" name="extension" accept=".zip" required <?php if (!$zipAvailable) echo 'disabled'; ?>>
                </div>
                <input type="submit" class="btn btn-primary" value="Install" <?php if (!$zipAvailable) echo 'disabled'; ?>>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php include_once 'inc/footer.php' ?>
</body>
</html>
