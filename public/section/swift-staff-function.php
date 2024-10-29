<?php

/*
 *      Custom excerpt function
 */

if (!function_exists('swift_staff_get_excerpt')) {

    function swift_staff_get_excerpt($excerpt_length = 55, $id = false, $echo = false) {
        return swift_staff_excerpt($excerpt_length, $id, $echo);
    }

}

if (!function_exists('swift_staff_excerpt')) {

    function swift_staff_excerpt($excerpt_length = 55, $id = false, $echo = false) {

        $text = '';

        if ($id) {
            $the_post = & get_post($my_id = $id);
            $text = ($the_post->post_excerpt) ? $the_post->post_excerpt : $the_post->post_content;
        } else {
            global $post;
            $text = ($post->post_excerpt) ? $post->post_excerpt : get_the_content('');
        }

        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);
        $text = strip_tags($text);

        $excerpt_more = ' ' . '<a href=' . get_permalink($id) . ' class="swift_staff-readmore">...continued</a>';
        $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
        if (count($words) > $excerpt_length) {
            array_pop($words);
            $text = implode(' ', $words);
            $text = $text . $excerpt_more;
        } else {
            $text = implode(' ', $words);
        }
        if ($echo)
            echo apply_filters('the_content', $text);
        else
            return $text;
    }

}

function swift_staff_save_local_capture() {
    $result['type'] = "fail";
    if (isset($_POST['action']) && !empty($_POST['action']) && $_POST['action'] == 'swift_staff_save_local_capture') {
        global $wpdb;
        $table_name = $wpdb->prefix . "swift_staff_log";

        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $phone = sanitize_text_field($_POST['phone']);
        parse_str(($_POST['form_data']), $form_data);
        $form_data = maybe_serialize($form_data);

        $wpdb->insert(
                $table_name, array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'form_data' => $form_data,
            'date_time' => date('Y-m-d h:i:s'),
            'status' => 0
                ), array(
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
                )
        );
        $result['type'] = "success";
    }
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $site_title = get_bloginfo('name');
        $subject = "New job application has been received from " . $site_title;
        $body = 'New job application has been received.<br><br>';
        $body .= 'Please check below details:<br><br>';

        if (isset($form_data) && !empty($form_data)) {
            foreach ($form_data as $form_key => $form_value) {
                $body .= $form_key . ': ' . $form_value . '<br>';
            }
        }

        $body .= '<br>From,<br>' . $site_title;
        $headers = array("Content-Type: text/html; charset=UTF-8", "From: " . $site_title . " <" . get_bloginfo('admin_email') . ">");
        wp_mail(get_bloginfo('admin_email'), $subject, $body, $headers);

        $result = json_encode($result);
        echo $result;
    } else {
        header("Location: " . $_SERVER["HTTP_REFERER"]);
    }
    wp_die();
}

add_action('wp_ajax_swift_staff_save_local_capture', 'swift_staff_save_local_capture');
add_action('wp_ajax_nopriv_swift_staff_save_local_capture', 'swift_staff_save_local_capture');


add_action('swift_staff_api_post', 'do_swift_staff_api_post');

function do_swift_staff_api_post() {
    global $wpdb;
    $table_name = $wpdb->prefix . "swift_staff_log";
    $fLog = $wpdb->get_results("SELECT * FROM $table_name WHERE status=0 ORDER BY `id` ASC LIMIT 1");
    if (isset($fLog[0]) && !empty($fLog[0])) {
        if (!empty($fLog[0]->form_data)) {
            $fData = @unserialize($fLog[0]->form_data);
            if (isset($fData) && !empty($fData) && !empty($fData['formid'])) {
                $fData['referer'] = home_url();
                $args = array(
                    'body' => $fData,
                    'timeout' => '5',
                    'redirection' => '5',
                    'httpversion' => '1.0',
                    'blocking' => true,
                    'headers' => array(),
                    'cookies' => array(),
                );
                wp_remote_post('https://portal.swiftcrm.com/f/fhx.php', $args);
                $wpdb->update($table_name, array('status' => 1), array('id' => $fLog[0]->id), array('%d'), array('%d'));
                echo "1";
            }
        }
    }
}
