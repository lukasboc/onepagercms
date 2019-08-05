<!DOCTYPE html>
<html>
<?php require_once "inc/head.php" ?>
<body>

<?php include_once "inc/header.php" ?>
<div class="container">
<h1>Welcome to OP-CMS!</h1>
    <p>Feel free to edit everything you want.</p>
    <h2>News</h2>
    <?php
    $rss_feed = simplexml_load_file("http://onepagercms.de/rss/OnePagerCMS.xml");
    if (!empty($rss_feed)) {
        $i = 0;
        foreach ($rss_feed->channel->item as $feed_item) {
            if ($i >= 10) break;
            ?>
            <div class="card rssCard" style="width: 18rem;">
                <img class="card-img-top" src="https://picsum.photos/100/50" alt="Card image cap">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $feed_item->title; ?></h5>
                    <p class="card-text"><?php echo implode(' ', array_slice(explode(' ', $feed_item->description), 0, 14)) . "..."; ?></p>
                    <small id="rssDate" class="form-text text-muted"><?php echo $feed_item->pubDate; ?>
                    </small>
                    <a href="<?php echo $feed_item->link; ?>" class="btn btn-primary">GitHub</a>
                </div>
            </div>

            <?php
            $i++;
        }
    }
    ?>







</div>
</body>
</html>