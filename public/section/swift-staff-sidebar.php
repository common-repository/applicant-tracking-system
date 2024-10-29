<!--sidebar widget-->
<?php
$swiftstaff_job_feed_page_id = get_option('swiftstaff_job_feed_page_id');
?>

<?php if (is_active_sidebar('swift-staff-sidebar')) : ?>
    <div class="swift-staff-widget swift-staff-widget-space">
<!--        <div class="jobs-print jobs-pagination">
            <div class="jobs-next-link jobs-pagination-link tooltip-left" data-tooltip="RSS Feed"><a href="<?php echo get_permalink($swiftstaff_job_feed_page_id); ?>" target="_blank"><i class="fa fa-rss fa-lg"></i></a></div>
        </div>-->
        <?php dynamic_sidebar('swift-staff-sidebar'); ?>
    </div>
<?php else: ?>
    <div class="jobs-widget">
<!--        <div class="jobs-print jobs-pagination">
            <div class="jobs-next-link jobs-pagination-link tooltip-left" data-tooltip="RSS Feed"><a href="<?php echo get_permalink($swiftstaff_job_feed_page_id); ?>" target="_blank"><i class="fa fa-rss fa-lg"></i></a></div>
        </div>-->
    </div>
<?php endif; ?>