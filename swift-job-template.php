<?php
/*
 * Template Name: Swift Jobs
 * Description:
 */
get_header();
$swiftstaff_job_feed_page_id = get_option('swiftstaff_job_feed_page_id');
$swiftstaff_careers_page_id = get_option('swiftstaff_careers_page_id');

wp_enqueue_script('swiftcloud-tooltip-min', SWIFTSTAFF_PLUGIN_URL . 'public/js/tooltipster.js', array('jquery'), '', true);
wp_enqueue_style('swiftcloud-plugin-tooltip', SWIFTSTAFF_PLUGIN_URL . 'public/css/swiftcloud-tooltip.css', '', '', '');
wp_enqueue_script('swift-staff-custom-script', SWIFTSTAFF_PLUGIN_URL . 'public/js/swift-staff-custom-script.js', array('jquery'), '', true);
?>
<div class="swift-staff-container container">
    <div class="swift-staff-page">
        <div class="swift-staff-col-8">
            <?php
            while (have_posts()) : the_post();
                ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class('swift-staff-content-wrap'); ?>>
                    <div class="swift-staff-page-title"><h1><?php the_title(); ?></h1></div>
                    <div class="swift-staff-page-content"><?php the_content(); ?></div>
                </div>
                <?php
            endwhile;
            ?>
        </div>
        <div class="swift-staff-col-4 swift-staff-sidebar-bg">
            <div class="jobs-sidebar">
                <div class="jobs-widget">
                    <div class="ss-widget-left-box">
                        <?php if ($swiftstaff_careers_page_id): ?><div class="jobs-list-link jobs-pagination-link tooltip-right" data-tooltip="Back to Job list"><a href="<?php echo get_permalink($swiftstaff_careers_page_id); ?>"><i class="fa fa-list"></i></a></div><?php endif; ?>
                    </div>
                    <div class="ss-widget-right-box">
                        <div class="jobs-prev-link jobs-pagination-link tooltip-left" data-tooltip="Send this job to someone"><a href="mailto:?Subject=<?php echo get_the_title(); ?>&Body=<?php echo get_the_permalink(); ?>" target="_new"><i class="fa fa-paper-plane"></i></a></div>
                        <div class="jobs-next-link jobs-pagination-link tooltip-left" data-tooltip="RSS Feed"><a href="<?php echo get_permalink($swiftstaff_job_feed_page_id); ?>" target="_blank"><i class="fa fa-rss fa-lg"></i></a></div>
                    </div>
                </div>
                <?php include_once 'public/section/swift-staff-sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();