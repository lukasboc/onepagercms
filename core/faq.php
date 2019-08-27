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
    <div class="mt-4 mb-4" id="accordion">
        <?php
        for ($i = 0, $iMax = count($faqpairs); $i < $iMax; $i++) {
            echo '<div class="card">';
            echo '<div class="card-header">';
            echo '<h4 class="card-header">';
            echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $i . '">' . $faqpairs[$i]->getQuestion() . '</a>';
            echo "</h4>";
            echo "</div>";
            echo '<div id="collapse' . $i . '" class="panel-collapse collapse in">';
            echo '<div class="card-block">';
            echo $faqpairs[$i]->getAnswer();
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>

    </div>
</div>
<style>
    .card-header {
        background-color: rgba(0, 0, 0, 0);
        padding: .35rem 0.25rem;
        border-bottom: none;
    }

    .card-header .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, .125)
    }

    .card-block {
        padding: 0.55rem 0.55rem;
    }

    .panel-heading [data-toggle="collapse"]:after {
        font-family: 'Glyphicons Halflings', serif;
        content: "e072"; /* "play" icon */
        float: right;
        color: #F58723;
        font-size: 18px;
        line-height: 22px;
        /* rotate "play" icon from > (right arrow) to down arrow */
        -webkit-transform: rotate(-90deg);
        -moz-transform: rotate(-90deg);
        -ms-transform: rotate(-90deg);
        -o-transform: rotate(-90deg);
        transform: rotate(-90deg);
    }

    .panel-heading [data-toggle="collapse"].collapsed:after {
        /* rotate "play" icon from > (right arrow) to ^ (up arrow) */
        -webkit-transform: rotate(90deg);
        -moz-transform: rotate(90deg);
        -ms-transform: rotate(90deg);
        -o-transform: rotate(90deg);
        transform: rotate(90deg);
        color: #454444;
    }
</style>


<?php include_once 'inc/footer.php' ?>
</body>
</html>