<?php
/*
 * Description:
 */
get_header();
wp_enqueue_script('swiftcloud-tooltip-min', SWIFTSTAFF_PLUGIN_URL . 'public/js/tooltipster.js', array('jquery'), '', true);
wp_enqueue_style('swiftcloud-plugin-tooltip', SWIFTSTAFF_PLUGIN_URL . 'public/css/swiftcloud-tooltip.css', '', '', '');
wp_enqueue_script('swift-staff-custom-script', SWIFTSTAFF_PLUGIN_URL . 'public/js/swift-staff-custom-script.js', array('jquery'), '', true);
$swiftstaff_job_feed_page_id = get_option('swiftstaff_job_feed_page_id');
$swiftstaff_careers_page_id = get_option('swiftstaff_careers_page_id');
?>
<div class="swift-staff-container container">
    <div class="swift-staff-page">
        <div class="swift-staff-col-8">
            <?php while (have_posts()) : the_post(); ?>
                <div class="swift-job-wrap">
                    <div class="swift-job-header">
                        <div class="swift-job-title"><a href="<?php echo get_the_permalink(get_the_ID()); ?>"><?php echo get_the_title(); ?></a></div>
                    </div>
                    <div class="swift-job-content">
                        <a href="<?php echo get_the_permalink(get_the_ID()); ?>">
                            <?php echo swift_staff_get_excerpt(40); ?>
                        </a>
                    </div>
                    <div class="swiftstaff-tags-wrap">
                        <?php echo get_the_term_list(get_the_ID(), 'swift_jobs_tag', '<ul class="swiftstaff-tags-list"><li>', '</li><li>', '</li></ul>'); ?>
                    </div>
                </div>
            <?php endwhile; ?>
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
<!--                            <div class="jobs-next-link jobs-pagination-link tooltip-left" data-tooltip="PDF / Print Version"><a href="<?php echo get_permalink() . "?print=1&pr="; ?>"><i class="fa fa-file-pdf-o fa-lg"></i></a></div>-->
                    </div>
                </div>
                <?php include_once 'swift-staff-sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
