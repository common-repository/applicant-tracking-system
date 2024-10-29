<?php

/**
 *  Create widget to showing latest release
 */
class swift_staff_widget_latest_jobs extends WP_Widget {

    public function __construct() {
        $widget_ops = array(
            'classname' => 'swift_staff_widget_latest_jobs',
            'description' => __('Swift staff latest jobs.'),
            'customize_selective_refresh' => true,
        );
        parent::__construct('swift_staff_latest_jobs', __('Latest Jobs'), $widget_ops);
    }

    /**
     * Outputs the content for the current latest jobs widget instance.
     *
     * @param array $args     Display arguments including 'before_title', 'after_title',
     *                        'before_widget', and 'after_widget'.
     * @param array $instance Settings for the current Recent Posts widget instance.
     */
    public function widget($args, $instance) {
        if (!isset($args['widget_id'])) {
            $args['widget_id'] = $this->id;
        }

        $title = (!empty($instance['title']) ) ? $instance['title'] : __('Latest Jobs');

        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $number = (!empty($instance['number']) ) ? absint($instance['number']) : 5;
        if (!$number)
            $number = 5;
        $show_date = isset($instance['show_date']) ? $instance['show_date'] : false;

        /**
         * Filters the arguments for the Recent Jobs widget.
         *
         * @param array $args An array of arguments used to retrieve the recent posts.
         */
        $latest_jobs_args = array(
            'post_type' => 'swift_jobs',
            'post_status' => 'publish',
            'posts_per_page' => $number,
            'orderby' => 'date',
            'order' => 'DESC',
            'no_found_rows' => true,
        );
        $r = new WP_Query($latest_jobs_args);

        if ($r->have_posts()) :
            ?>
            <?php echo $args['before_widget']; ?>
            <?php
            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }
            ?>
            <ul class="swift-staff-latest-jobs">
                <?php
                while ($r->have_posts()) : $r->the_post();
                    $job_id = get_post_meta(get_the_ID(), 'swift_staff_job_id', true);
                    ?>
                    <li>
                        <a href="<?php the_permalink(); ?>">
                            <span class="swift-staff-widget-job-title"><?php get_the_title() ? the_title() : the_ID(); ?></span>
                            <?php if ($show_date) : ?>
                                <span class="swift-staff-latest-date"><?php echo get_the_date(); ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php echo $args['after_widget']; ?>
            <?php
            // Reset the global $the_post as this query will have stomped on it
            wp_reset_postdata();

        endif;
    }

    /**
     * Handles updating the settings for the current Recent Posts widget instance.
     * @param array $new_instance New settings for this instance as input by the user via
     *                            WP_Widget::form().
     * @param array $old_instance Old settings for this instance.
     * @return array Updated settings to save.
     */
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field($new_instance['title']);
        $instance['number'] = (int) $new_instance['number'];
        $instance['show_date'] = isset($new_instance['show_date']) ? (bool) $new_instance['show_date'] : false;
        return $instance;
    }

    /**
     * Outputs the settings form for the Recent Posts widget.
     * @param array $instance Current settings.
     */
    public function form($instance) {
        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $number = isset($instance['number']) ? absint($instance['number']) : 5;
        $show_date = isset($instance['show_date']) ? (bool) $instance['show_date'] : false;
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to show:'); ?></label>
            <input class="tiny-text" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="number" step="1" min="1" value="<?php echo $number; ?>" size="3" /></p>

        <p><input class="checkbox" type="checkbox"<?php checked($show_date); ?> id="<?php echo $this->get_field_id('show_date'); ?>" name="<?php echo $this->get_field_name('show_date'); ?>" />
            <label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Display post date?'); ?></label></p>
        <?php
    }

}

add_action('widgets_init', function() {
            register_widget('swift_staff_widget_latest_jobs');
        }
);
?>
