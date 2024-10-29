<?php

/**
 * SwiftStaff Search widget.
 *
 */
class swift_staff_widget_search_jobs extends WP_Widget {

    /**
     * Sets up a new Job Search widget instance.
     *
     */
    public function __construct() {
        $widget_ops = array(
            'classname' => 'swift-staff-widget-job-search',
            'description' => __('A search form for Jobs.'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('swift_staff_job_search', _x('Search Jobs', 'swift_staff'), $widget_ops);
    }

    /**
     * Outputs the content for the current Search widget instance.
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Search widget instance.
     */
    public function widget($args, $instance) {
        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', empty($instance['title']) ? 'Search Jobs' : $instance['title'], $instance, $this->id_base);

        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Use current theme search form if it exists
        //get_search_form();
        ?>
        <div class="swift-staff-search-form-wrap">
            <form class="swift-staff-search-form" role="search" method="get" action="<?php echo home_url(); ?>">
                <input class="search-field" placeholder="Search Jobs…" value="" name="s" type="search">
                <input type="hidden" name="post_type" value="swift_jobs" />
                <button class="swift-staff-search-jobs-submit" type="submit"><span>Search</span></button>
            </form>
        </div>
        <?php
        echo $args['after_widget'];
    }

    /**
     * Outputs the settings form for the Search widget.
     *
     * @param array $instance Current settings.
     */
    public function form($instance) {
        $instance = wp_parse_args((array) $instance, array('title' => ''));
        $title = $instance['title'];
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
        <?php
    }

    /**
     * Handles updating settings for the current Search widget instance.
     *
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $new_instance = wp_parse_args((array) $new_instance, array('title' => ''));
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }

}

add_action('widgets_init', function() {
            register_widget('swift_staff_widget_search_jobs');
        }
);
