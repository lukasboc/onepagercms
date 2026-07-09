<?php
include '../database/SQLFAQActions.php';

$faqactions = new SQLFAQActions();
$categories = $faqactions->getAllCategories();
$faqpairs = $faqactions->getAllQuestionsAndAnswers();
?>
<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php' ?>
<body>

<?php include_once 'inc/header.php' ?>
<div class="container">
    <h1>Frequently Asked Questions</h1>
    <div class="mt-4 mb-4 space-y-2">
        <?php
        for ($i = 0, $iMax = count($faqpairs); $i < $iMax; $i++) {
            echo '<div class="collapse collapse-arrow card-border bg-base-100 border border-base-300">';
            echo '<input type="checkbox" checked>';
            echo '<div class="collapse-title font-semibold">' . $faqpairs[$i]->getQuestion() . '</div>';
            echo '<div class="collapse-content">';
            echo $faqpairs[$i]->getAnswer();
            echo '</div>';
            echo '</div>';
        }
        ?>

    </div>
</div>


<?php include_once 'inc/footer.php' ?>
</body>
</html>
