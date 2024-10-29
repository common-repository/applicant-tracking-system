<?php
get_header();
wp_enqueue_script('swiftcloud-tooltip-min', SWIFTSTAFF_PLUGIN_URL . 'public/js/tooltipster.js', array('jquery'), '', true);
wp_enqueue_style('swiftcloud-plugin-tooltip', SWIFTSTAFF_PLUGIN_URL . 'public/css/swiftcloud-tooltip.css', '', '', '');
wp_enqueue_script('swift-staff-custom-script', SWIFTSTAFF_PLUGIN_URL . 'public/js/swift-staff-custom-script.js', array('jquery'), '', true);
?>
<div class="swift-staff-container container">
    <div class="swift-staff-page">
        <div class="swift-staff-col-8">
            <?php if (have_posts()) : ?>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="swift-staff-page-title">
                        <h1>Staff Directory</h1>
                    </div>
                    <div class="ss-search-box">
                        <form id="prSearchForm" class="ss-search-form" action="<?php echo home_url(); ?>" method="get" role="search">
                            <div class="ss-input-group">
                                <input type="text" id="s" name="s" value="" class="ss-search-control" placeholder="Search...">
                                <input type="hidden" name="post_type" value="swift_staffs" />
                                <span class="ss-group-btn">
                                    <button type="submit" value="Search" id="searchsubmit" class="ss-btn-orange"><i class="fa fa-search fa-lg"></i></button>
                                </span>
                            </div>
                        </form>
                    </div>
                    <div class="ss-content">
                        <?php
                        $op = '<div class="swift-staff-list-wrapper">';

                        while (have_posts()) : the_post();
                            $op .= '<div class="swift-staff-block">';
                            if (has_post_thumbnail()) {
                                $image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'medium');
                                $op .= '<div class="staff_avatar_container"><div class="staff_avatar" style="background-image: url(' . $image[0] . ')"><a href="' . get_the_permalink(get_the_ID()) . '"></a></div></div>';
                            }
                            $op .= '    <div class="' . (has_post_thumbnail() ? "swift-staff-info" : "swift-staff-info-full" ) . '">';
                            $op .= '        <div class="swift-staff-title"><h2><a href="' . get_the_permalink(get_the_ID()) . '">' . get_the_title() . '</a></h2></div>';

                            if (get_post_meta(get_the_ID(), 'swift_staff_job_title', true)) {
                                $op .= '<div class="swift-staff-job-title"><h4><a href="' . get_the_permalink(get_the_ID()) . '">' . get_post_meta($post->ID, 'swift_staff_job_title', true) . '</a></h4></div>';
                            }
                            $op .= '        <div class="swift-staff-content">
                                                <a href="' . get_the_permalink(get_the_ID()) . '">
                                                    ' . swift_staff_get_excerpt(50) . '
                                                </a>
                                            </div>
                                        </div>
                                    </div>';
                        endwhile;
                        $op .= '</div>';
                        echo $op;
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="swift-staff-page-title">
                    <h1><?php the_archive_title(); ?></h1>
                </div>
                <div><h3>No staff found....!</h3></div>
            <?php endif; ?>
        </div>
        <div class="swift-staff-col-4 swift-staff-sidebar-bg">
            <div class="swift-staff-sidebar">
                <?php include_once 'swift-staff-sidebar.php'; ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>