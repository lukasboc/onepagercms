<!DOCTYPE html>
<html>
<?php require_once 'inc/head.php' ?>
<body>

<?php include_once 'inc/header.php' ?>
<div class="container">
<h1>Welcome to OP-CMS!</h1>
    <h2>News</h2>
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
    <?php
    $rss_feed = simplexml_load_string(file_get_contents('https://onepagercms.de/rss/OnePagerCMS.xml'));
    if ($rss_feed !== null) {
        $i = 0;
        foreach ($rss_feed->channel->item as $feed_item) {
            if ($i >= 10) break;
            ?>
            <div class="card card-border bg-base-100 shadow-sm">
                <figure><img src="<?php echo $feed_item->image; ?>" alt="News image"></figure>
                <div class="card-body p-4">
                    <h5 class="card-title text-base"><?php echo $feed_item->title; ?></h5>
                    <p class="text-sm"><?php echo implode(' ', array_slice(explode(' ', $feed_item->description), 0, 14)) . '...'; ?>
                        <a class="link" href="<?php echo $feed_item->link; ?>">
                            <small class="text-base-content/60">Read More</small>
                        </a></p>
                    <small class="text-xs text-base-content/60"><?php echo $feed_item->pubDate; ?></small>
                </div>
            </div>

            <?php
            $i++;
        }
    }
    ?>
    </div>

</div>
<?php include_once 'inc/footer.php' ?>
</body>
</html>
