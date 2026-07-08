<!DOCTYPE html>
<html>
<?php require_once '../core/inc/head.php';
include_once '../database/SQLAdditionalPagesActions.php';
$pagesActions = new SQLAdditionalPagesActions();
$allPages = $pagesActions->getAllAdditionalPages();
?>

<body>
<?php
include_once '../core/inc/header.php' ?>
<div class="container">

    <h1>Additional Pages</h1>
    <div class="mb-4 text-center">
        <a class="btn btn-success" href="../misc/additionalpage.php?action=New" role="button">New Page</a>
    </div>
    <div class="grid grid-cols-12 gap-2 items-center text-sm font-semibold text-base-content/60 pb-2">
        <div class="col-span-3 md:col-span-2">Title</div>
        <div class="col-span-4 md:col-span-7">Content</div>
        <div class="col-span-5 md:col-span-3">Options</div>
    </div>
    <?php
    for ($i = 0, $iMax = count($allPages); $iMax > $i; $i++) {
        echo '
  <div class="grid grid-cols-12 gap-2 items-center py-2 border-t border-base-300">
    <div class="col-span-3 md:col-span-2">
    ' . $allPages[$i][1] . '
    </div>
    <div class="col-span-4 md:col-span-7">
      ' . mb_strimwidth(strip_tags($allPages[$i][2]), 0, 80, '...') . '
    </div>
    <div class="col-span-5 md:col-span-3">
      <div class="join">
        <a href="../misc/additionalpage.php?id=' . $allPages[$i][0] . '&action=Edit" class="btn btn-primary btn-sm btn-square join-item" role="button"><i class="far fa-edit"></i></a>
        <a href="../pages/additionalpage.php?id=' . $allPages[$i][0] . '" class="btn btn-info btn-sm btn-square join-item" target="_blank" role="button"><i class="far fa-eye"></i></a>
        <a href="../misc/additionalpage.php?id=' . $allPages[$i][0] . '&action=Delete" class="btn btn-ghost btn-sm btn-square join-item" role="button"><i class="far fa-trash-alt"></i></a>
      </div>
    </div>
  </div>


    ';
    } ?>
</div>
<?php include_once 'inc/footer.php' ?>
</body>
</html>
