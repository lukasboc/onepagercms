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
        <div class="alert alert-warning">The PHP <code>zip</code> extension (ZipArchive) is missing on this server. Installing extensions from ZIP archives is not possible.</div>
    <?php endif; ?>
    <?php if (!$httpAvailable): ?>
        <div class="alert alert-warning">Neither <code>curl</code> nor <code>allow_url_fopen</code> is available. The marketplace cannot be reached from this server; you can still install extensions via ZIP upload.</div>
    <?php endif; ?>

    <ul class="nav nav-tabs mt-3">
        <li class="nav-item">
            <a class="nav-link<?php if ($activeTab === 'installed') echo ' active'; ?>" href="extensions.php?tab=installed">Installed</a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php if ($activeTab === 'marketplace') echo ' active'; ?>" href="extensions.php?tab=marketplace">Marketplace</a>
        </li>
        <li class="nav-item">
            <a class="nav-link<?php if ($activeTab === 'upload') echo ' active'; ?>" href="extensions.php?tab=upload">Upload</a>
        </li>
    </ul>

    <div class="pt-4">
        <?php if ($activeTab === 'installed'): ?>
            <?php if (count($installedExtensions) === 0): ?>
                <p>No extensions installed yet. Install one from the <a href="extensions.php?tab=marketplace">Marketplace</a> or <a href="extensions.php?tab=upload">upload a ZIP archive</a>.</p>
            <?php else: ?>
                <table class="table table-striped">
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
                                <small class="text-muted"><?php echo htmlspecialchars($slug) ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($installedExtension['type']) ?></td>
                            <td><?php echo htmlspecialchars($installedExtension['version']) ?></td>
                            <td>
                                <?php echo $isActive ? '<span class="badge badge-success">active</span>' : '<span class="badge badge-secondary">inactive</span>'; ?>
                                <?php if ($isPaid) echo ' <span class="badge badge-info">paid</span>'; ?>
                                <?php if ($hasUpdate) echo ' <span class="badge badge-warning">update available: ' . htmlspecialchars($availableUpdates[$slug]['new_version']) . '</span>'; ?>
                            </td>
                            <td class="text-right">
                                <?php if ($hasUpdate || $isPaid): ?>
                                    <form class="d-inline" method="post" action="../misc/extensionupdate.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <input type="submit" class="btn btn-sm btn-warning" value="<?php echo $hasUpdate ? 'Update' : 'Check for update'; ?>">
                                    </form>
                                <?php endif; ?>
                                <?php if ($isActive): ?>
                                    <form class="d-inline" method="post" action="../misc/extensiondeactivate.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <input type="submit" class="btn btn-sm btn-outline-secondary" value="Deactivate">
                                    </form>
                                <?php else: ?>
                                    <form class="d-inline" method="post" action="../misc/extensionactivate.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <input type="submit" class="btn btn-sm btn-primary" value="Activate">
                                    </form>
                                <?php endif; ?>
                                <form class="d-inline" method="post" action="../misc/extensiondelete.php"
                                      onsubmit="return confirm('Delete this extension? Its files will be removed.');">
                                    <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                    <input type="submit" class="btn btn-sm btn-outline-danger" value="Delete">
                                </form>
                            </td>
                        </tr>
                        <?php if ($isPaid): ?>
                            <tr>
                                <td colspan="5" class="pt-0 border-top-0">
                                    <form class="form-inline" method="post" action="../misc/extensionlicense.php">
                                        <input type="hidden" name="slug" value="<?php echo htmlspecialchars($slug) ?>">
                                        <label class="mr-2 text-muted" for="license-<?php echo htmlspecialchars($slug) ?>"><small>License key:</small></label>
                                        <input type="text" class="form-control form-control-sm mr-2" style="min-width: 280px"
                                               id="license-<?php echo htmlspecialchars($slug) ?>" name="license_key"
                                               value="<?php echo htmlspecialchars((string)$installedExtension['license_key']) ?>"
                                               placeholder="Enter the license key from the developer">
                                        <input type="submit" class="btn btn-sm btn-outline-primary" value="Save license">
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>

        <?php elseif ($activeTab === 'marketplace'): ?>
            <form class="form-inline mb-4" method="get" action="extensions.php">
                <input type="hidden" name="tab" value="marketplace">
                <select class="form-control mr-2" name="type">
                    <option value="">All types</option>
                    <option value="plugin" <?php if ($marketplaceType === 'plugin') echo 'selected'; ?>>Plugins</option>
                    <option value="theme" <?php if ($marketplaceType === 'theme') echo 'selected'; ?>>Themes</option>
                </select>
                <input type="text" class="form-control mr-2" name="search" placeholder="Search..."
                       value="<?php echo htmlspecialchars($marketplaceSearch) ?>">
                <input type="submit" class="btn btn-primary" value="Search">
            </form>
            <form class="mb-4" method="post" action="../misc/marketplacerefresh.php"
                  title="Marketplace results are cached for a few hours; refresh to fetch the latest listings.">
                <input type="submit" class="btn btn-sm btn-outline-secondary" value="Refresh listings">
            </form>

            <?php if (!$httpAvailable): ?>
                <div class="alert alert-warning">The marketplace cannot be reached because this server has no HTTP client available.</div>
            <?php elseif ($marketplaceResult === null): ?>
                <div class="alert alert-danger">The marketplace could not be reached. Please try again later.</div>
            <?php elseif (count($marketplaceResult['data']) === 0): ?>
                <p>No extensions found.</p>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($marketplaceResult['data'] as $marketplaceItem):
                        $slug = $marketplaceItem['slug'];
                        $isInstalled = isset($installedBySlug[$slug]);
                        $installedVersion = $isInstalled ? $installedBySlug[$slug]['version'] : null;
                        $hasUpdate = $isInstalled && isset($marketplaceItem['latest_version'])
                            && version_compare($marketplaceItem['latest_version'], $installedVersion, '>');
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($marketplaceItem['name']) ?></h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                        <?php echo htmlspecialchars($marketplaceItem['type']) ?>
                                        · v<?php echo htmlspecialchars((string)$marketplaceItem['latest_version']) ?>
                                        · by <?php echo htmlspecialchars((string)$marketplaceItem['author']) ?>
                                        <?php if (!empty($marketplaceItem['is_paid'])) echo ' · <span class="badge badge-info">Paid</span>'; ?>
                                    </h6>
                                    <p class="card-text"><?php echo htmlspecialchars((string)$marketplaceItem['summary']) ?></p>
                                    <p class="card-text"><small class="text-muted"><?php echo (int)$marketplaceItem['downloads'] ?> downloads</small></p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <?php if (!empty($marketplaceItem['is_paid'])): ?>
                                        <?php if (!empty($marketplaceItem['purchase_url'])): ?>
                                            <a class="btn btn-sm btn-primary" target="_blank" rel="noopener"
                                               href="<?php echo htmlspecialchars($marketplaceItem['purchase_url']) ?>">Buy on developer site</a>
                                        <?php endif; ?>
                                        <small class="text-muted d-block mt-1">After purchase you receive a ZIP and a license key from the developer. Install the ZIP via the Upload tab.</small>
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
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php if (isset($marketplaceResult['meta']['last_page']) && $marketplaceResult['meta']['last_page'] > 1): ?>
                    <nav>
                        <ul class="pagination">
                            <?php for ($p = 1; $p <= (int)$marketplaceResult['meta']['last_page']; $p++): ?>
                                <li class="page-item<?php if ($p === $marketplacePage) echo ' active'; ?>">
                                    <a class="page-link" href="extensions.php?tab=marketplace&type=<?php echo urlencode($marketplaceType) ?>&search=<?php echo urlencode($marketplaceSearch) ?>&mpage=<?php echo $p ?>"><?php echo $p ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>

        <?php else: ?>
            <p>Upload a plugin or theme as a ZIP archive. The archive must contain a <code>plugin.json</code> or
                <code>theme.json</code> manifest.</p>
            <p class="text-muted"><small>Only install extensions from sources you trust &mdash; extension code runs with
                full access to your website.</small></p>
            <form method="post" action="../misc/extensionupload.php" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="file" name="extension" accept=".zip" required <?php if (!$zipAvailable) echo 'disabled'; ?>>
                </div>
                <input type="submit" class="btn btn-primary" value="Install" <?php if (!$zipAvailable) echo 'disabled'; ?>>
            </form>
        <?php endif; ?>
    </div>
</div>
<?php include_once 'inc/footer.php' ?>
</body>
</html>
