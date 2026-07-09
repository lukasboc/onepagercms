<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php'; ?>
<body>

<?php include_once 'inc/header.php' ?>
<div class="container">
<?php
include '../database/SQLSectionActions.php';
include '../database/SQLHeaderActions.php';
$sectionactions = new SQLSectionActions();
$headeractions = new SQLHeaderActions();
$sections = $sectionactions->getAllSections();
?>
    <h1>Sections</h1>

    <div class="grid grid-cols-12 gap-2 items-center text-sm font-semibold text-base-content/60 pb-2">
        <div class="col-span-2 lg:col-span-1">Position</div>
        <div class="col-span-2">Type</div>
        <div class="col-span-5 lg:col-span-6">Title</div>
        <div class="col-span-3">Options</div>
    </div>

    <form method="post" action="../misc/positions.php" id="positionsform">
        <div class="grid grid-cols-12 gap-2 items-center py-2 border-t border-base-300">
            <div class="col-span-2 lg:col-span-1">
            </div>
            <div class="col-span-2">
                Header
            </div>
            <div class="col-span-5 lg:col-span-6">
                <?php echo $headeractions->getHeaderTitle(); ?>
            </div>
            <div class="col-span-3">
                <a href="../misc/header.php?action=Edit" class="btn btn-primary btn-sm btn-square" role="button"><i
                            class="far fa-edit"></i></a>
            </div>
        </div>
    <?php
    for ($i = 0, $iMax = count($sections); $iMax > $i; $i++) {
    echo'
  <div class="grid grid-cols-12 gap-2 items-center py-2 border-t border-base-300">
    <div class="col-span-2 lg:col-span-1">
      <select class="select select-sm w-full" form="positionsform" name="' . $sections[$i]->getSuperid() . '">
        <option selected disabled>'. $sections[$i]->getPosition() . '</option>

      ';
        for($h=1; count($sections)+1 > $h; $h++){
        echo'
         <option value="' . $h . '">' . $h . '</option>
        ';
        }
        echo '
      </select>
    </div>
    <div class="col-span-2">
      ' . $sections[$i]->getType() . '
    </div>
    <div class="col-span-5 lg:col-span-6">
      ' . $sections[$i]->getTitle() . '
    </div>
    <div class="col-span-3">
      <div class="join">
        <a href="../misc/changesection.php?id=' . $sections[$i]->getSuperid() . '&action=Edit&type=' . $sections[$i]->getType() . '" class="btn btn-primary btn-sm btn-square join-item" role="button"><i class="far fa-edit"></i></a>
        <a href="../misc/changesection.php?id=' . $sections[$i]->getSuperid() . '&action=Delete&type=' . $sections[$i]->getType() . '" class="btn btn-ghost btn-sm btn-square join-item" role="button"><i class="far fa-trash-alt"></i></a>
      </div>
    </div>
  </div>';
}
foreach ($sectionactions->getOrphanSectionRows() as $opcmsOrphanRow) {
    echo '
  <div class="grid grid-cols-12 gap-2 items-center py-2 border-t border-base-300 text-base-content/60">
    <div class="col-span-2 lg:col-span-1">
      <select class="select select-sm w-full" disabled>
        <option selected>' . htmlspecialchars((string)$opcmsOrphanRow['position']) . '</option>
      </select>
    </div>
    <div class="col-span-2">
      ' . htmlspecialchars($opcmsOrphanRow['type']) . '
    </div>
    <div class="col-span-5 lg:col-span-6">
      <em>unavailable &mdash; plugin inactive</em>
    </div>
    <div class="col-span-3">
      <div class="join">
        <a href="../misc/deletesectionentry.php?id=' . urlencode($opcmsOrphanRow['id']) . '" class="btn btn-ghost btn-sm btn-square join-item" role="button" onclick="return confirm(\'Remove this orphaned section entry? The plugin\\\'s own data is not touched.\');"><i class="far fa-trash-alt"></i></a>
      </div>
    </div>
  </div>';
}
?>
        <div class="grid grid-cols-12 gap-2 items-center py-2 border-t border-b border-base-300">
            <div class="col-span-2 lg:col-span-1">
            </div>
            <div class="col-span-2">
                Footer
            </div>
            <div class="col-span-5 lg:col-span-6">
                -
            </div>
            <div class="col-span-3">
                <a href="../misc/footer.php?action=Edit" class="btn btn-primary btn-sm btn-square" role="button"><i
                            class="far fa-edit"></i></a>
            </div>
        </div>
        <input type="submit" name="action" value="Save Positions" class="btn btn-warning mt-4">
    </form>
    <div class="divider"></div>
    <h1 class="mt-4">New Section</h1>
    <form action="../misc/newsection.php" method="post">
        <div class="mb-4">
    <label class="label" for="types">Type:</label>
            <select class="select w-full max-w-xs" name="type" id="types">
        <option>standard</option>
        <option>icons</option>
        <option>contact</option>
<?php
if (function_exists('opcms_get_section_types')) {
    foreach (opcms_get_section_types() as $opcmsSectionType => $opcmsSectionTypeConfig) {
        echo '        <option value="' . htmlspecialchars($opcmsSectionType) . '">' . htmlspecialchars($opcmsSectionTypeConfig['label']) . '</option>' . "\n";
    }
}
?>
    </select>
    </div>
        <input type="submit" name="action" value="New Section" class="btn btn-success">
    </form>
</div>
<?php include_once 'inc/footer.php' ?>
</body>
</html>
