<?php
/**
 * The template for displaying all single swift staffs
 *
 */
get_header();
wp_enqueue_script('swiftcloud-tooltip-min', SWIFTSTAFF_PLUGIN_URL . 'public/js/tooltipster.js', array('jquery'), '', true);
wp_enqueue_style('swiftcloud-plugin-tooltip', SWIFTSTAFF_PLUGIN_URL. 'public/css/swiftcloud-tooltip.css', '', '', '');
wp_enqueue_script('swift-staff-custom-script', SWIFTSTAFF_PLUGIN_URL . 'public/js/swift-staff-custom-script.js', array('jquery'), '', true);
$swiftstaff_job_feed_page_id = get_option('swiftstaff_job_feed_page_id');
$swiftstaff_careers_page_id = get_option('swiftstaff_list_page_id');
?>
<div class="swift-staff-container container">
    <div class="swift-staff-page">
        <div class="swiftstaff-row">
            <div class="swift-staff-col-8">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $job_location_array = array();
                    $job_locations = wp_get_post_terms(get_the_ID(), 'swiftstaff_location_tag');
                    $job_title = get_post_meta(get_the_ID(), 'swift_staff_job_title', true);
                    ?>
                    <div id="post-<?php the_ID(); ?>" <?php post_class('swift-staff-content-wrap'); ?>>
                        <div itemscope itemtype="http://schema.org/JobPosting">
                            <?php if (has_post_thumbnail()) { ?>
                                <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium'); ?>
                                <div class="single_staff_avatar" style="background-image: url(<?php echo $image[0] ?>)"><img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" /></div>
                            <?php } ?>
                            <div class="swift-staff-page-title"><h1 itemprop="title"><?php the_title(); ?></h1></div>
                            <div class="swift-staff-job-title"><h4><?php echo $job_title; ?></h4></div>
                            <div class="swift-staff-page-content" itemprop="description">
                                <?php the_content(); ?>
                                <div class="">
                                    <div class="swiftstaff-tags-wrap">
                                        <?php
                                        echo get_the_term_list(get_the_ID(), 'swift_staffs_tag', '<ul class="swiftstaff-tags-list"><li>', '</li><li>', '</li></ul>');
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="plugin-credit swiftcloud_credit">Powered by
                        <a href="https://SwiftCRM.com/" target="_blank">SwiftCloud</a>&nbsp;
                        <a href="https://wordpress.org/plugins/applicant-tracking-system/" target="_blank">Applicant Tracking System</a>
                    </div>
                    <div class="swiftcloud-edit-link"><?php edit_post_link(__('Edit', 'swift_staff'), '<span class="edit-link">', '</span>', get_the_ID()); ?></div>
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
</div>
<?php get_footer(); ?>