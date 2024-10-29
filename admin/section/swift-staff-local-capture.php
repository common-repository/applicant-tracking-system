<?php
/*
 *      Log listing page
 */

function swift_staff_admin_display_log() {
    global $wpdb;
    $i = 1;
    $table_name = $wpdb->prefix . "swift_staff_log";

    /* Remove log */
    if (isset($_GET['mode']) && $_GET['mode'] == 'remove_record' && isset($_GET['id']) && !empty($_GET['id'])) {
        $wpdb->delete($table_name, $where = array('id' => $_GET['id']), $where_format = array('%d'));
    }

    /* Export log */
    if (isset($_POST['exportlogs']) && !empty($_POST['exportlogs']) && $_POST['exportlogs'] == "Export Logs") {
        $fLog = $wpdb->get_results("SELECT * FROM `$table_name` ORDER BY `id` DESC");
        if ((isset($fLog[0]) && !empty($fLog[0]))) {
            swift_staff_generate_csv_log($fLog);
        }
    }

    /* Select log for display */

    $where = " WHERE 1 ";
    $order_by = " ORDER BY `id` desc";
    /*
     *  Pagination
     */
    $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
    $limit = 100; // number of rows in page
    $offset = ( $pagenum - 1 ) * $limit;
    $total = $wpdb->get_var("SELECT count(*) FROM $table_name $where $order_by");
    $num_of_pages = ceil($total / $limit);

    $total_filtered_log = $wpdb->get_var("SELECT count(*) FROM $table_name $where $order_by");
    $fLog = $wpdb->get_results("SELECT * FROM $table_name $where $order_by LIMIT $offset,$limit");
    wp_enqueue_script('sma-time-ago', SWIFTSTAFF_PLUGIN_URL.'/admin/js/jquery.timeago.js', array('jquery'), '', true);
    ?>
    <div class="wrap">
        <h2 class="swiftpage-title">Form Submission</h2>
        <?php if ($fLog) { ?>
            <div class="export-form">
                <form id="frm-export-log" method="post" name="frmexportlog">
                    <input type="submit" class="button-primary" name="exportlogs" id="exportlogs" value="Export Logs" title="Export to csv"/>
                </form>
            </div>
        <?php } ?>
        <hr/>
        <div class="inner_content">
            <table cellspacing="0" class="widefat fixed users">
                <thead>
                    <tr>
                        <th scope='col' id='cb' class='manage-column column-cb check-column'>&nbsp;</th>
                        <th scope='col' id='name' class='manage-column'><strong>Name</strong></th>
                        <th scope='col' id='email' class='manage-column'><strong>E-mail</strong></th>
                        <th scope='col' id='phone' class='manage-column'><strong>Phone</strong></th>
                        <th scope='col' id='status' class='manage-column'><strong>Status</strong></th>
                        <th scope='col' id='date' class='manage-column column-role'><strong>Date/Time</strong></th>
                        <th scope='col' id='actions' class='manage-column column-posts num'><strong>Actions</strong></th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th scope='col' id='cb' class='manage-column column-cb check-column'>&nbsp;</th>
                        <th scope='col' id='name' class='manage-column'><strong>Name</strong></th>
                        <th scope='col' id='email' class='manage-column'><strong>E-mail</strong></th>
                        <th scope='col' id='phone' class='manage-column'><strong>Phone</strong></th>
                        <th scope='col' id='status' class='manage-column'><strong>Status</strong></th>
                        <th scope='col' id='date' class='manage-column column-role'><strong>Date/Time</strong></th>
                        <th scope='col' id='actions' class='manage-column column-posts num'><strong>Actions</strong></th>
                    </tr>
                </tfoot>
                <tbody id="the-list" class='list:user'>
                    <?php
                    if ($fLog) :
                        foreach ($fLog as $log) :
                            ?>
                            <tr id='user-<?php echo $log->id; ?>' class="alternate">
                                <th scope='row' class='check-column'><span style="margin-left:10px;"><a href="admin.php?page=swift_staff_admin_display_log_details&log_id=<?php echo $log->id; ?>" title="View Log Detail"><i class="fa fa-search" style="font-size: 16px;"></i></a></span></th>
                                <!--<td class="filename column-filename">
                                <?php
                                /* if ($log->filename)
                                  echo $log->filename;
                                  else
                                  echo '-'; */
                                ?>
                                </td>-->
                                <td class="name column-name">
                                    <?php echo ($log->name) ? '<a href="admin.php?page=swift_staff_admin_display_log_details&log_id=' . $log->id . '" title="View Log Detail">' . $log->name . '</a>' : '-'; ?>
                                </td>
                                <td class="email column-email">
                                    <?php if ($log->email) { ?><a href='mailto:<?php echo $log->email; ?>' title='E-mail: <?php echo $log->email; ?>'><?php echo $log->email; ?></a><?php
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td class="role column-role"><?php
                                    if ($log->status == '1')
                                        echo '<i class="fa fa-flag-checkered complete"></i> Complete';
                                    else
                                        echo '<i class="fa fa-exclamation-triangle incomplete" ></i> Incomplete'
                                        ?></td>
                                <td class="role column-role"><abbr class="timeago" title="<?php echo $log->date_time; ?>"><?php echo ($log->date_time); ?></abbr></td>
                                <td class="posts column-posts num"> <a onclick="return confirm('Are you sure you want to delete this record ?');" href="admin.php?page=swift_staff_admin_display_log&mode=remove_record&id=<?php echo $log->id; ?>"><i class="fa fa-times-circle delete fa-lg"></i></a></td>
                            </tr>
                            <?php
                            $i++;
                        endforeach; //foreach end;
                    else:
                        ?>
                        <tr id='user-1' class="alternate">
                            <td scope='row' colspan="7" align="center"><?php _e('<h2>No record found.</h2>', 'swift-cloud'); ?></th>
                        </tr>
                    <?php
                    endif; //first if end
                    ?>
                </tbody>
            </table>
            <?php swift_staff_admin_log_pagination($num_of_pages, $pagenum, $total_filtered_log, $limit); ?>
            <style type="text/css">
                .complete{ color:#66CD00; }
                .incomplete{ color:#F5F500; }
                .delete{ color:#FF0000; }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery("abbr.timeago").timeago();
                });
            </script>
        </div>
    </div>
    <?php
}

/*
 *      Log detail page
 */

function swift_staff_admin_display_log_details() {
    global $wpdb;
    $table_name = $wpdb->prefix . "swift_staff_log";
    $fLogDetail = false;
    if (isset($_GET['log_id']) && !empty($_GET['log_id'])) {
        $fLog = $wpdb->get_results("SELECT * FROM $table_name WHERE id='" . $_GET['log_id'] . "' ");
        $fLogDetail = (isset($fLog[0]) && !empty($fLog[0])) ? $fLog[0] : false;
    }
    ?>
    <div class="wrap">
        <h2>Form Log Detail</h2> <a href="admin.php?page=swift_staff_admin_display_log">Back to Log List</a>
        <div class="inner_content">
            <table cellspacing="0" class="widefat striped fixed users">
                <?php if ($fLogDetail) : ?>
                    <?php wp_enqueue_script('sma-time-ago', plugins_url('/js/jquery.timeago.js', __FILE__), array('jquery'), '', true); ?>
                    <tr>
                        <td>Name: </td>
                        <td><?php echo ($fLogDetail->name) ? $fLogDetail->name : "Anonymous"; ?></td>
                    </tr>
                    <tr class="">
                        <td>Email Address: </td>
                        <td><?php echo ($fLogDetail->email) ? $fLogDetail->email : "---"; ?></td>
                    </tr>
                    <tr class="">
                        <td>Phone: </td>
                        <td><?php echo ($fLogDetail->phone) ? $fLogDetail->phone : "---"; ?></td>
                    </tr>
                    <tr class="">
                        <td>Status: </td>
                        <td><?php echo ($fLogDetail->status == 1) ? "Complete" : "Incomplete"; ?></td>
                    </tr>
                    <tr>
                        <td>Date: </td>
                        <td><abbr class="timeago" title="<?php echo $fLogDetail->date_time; ?>"></abbr></td>
                    </tr>
                    <tr>
                        <td>Form Data:</td>
                        <td>
                            <?php
                            if (!empty($fLogDetail->form_data)) {
                                $fData = @unserialize($fLogDetail->form_data);
                                if (isset($fData) && !empty($fData)) {
                                    foreach ($fData as $key => $value) {
                                        echo "<strong>" . ucfirst($key) . "</strong>: " . $value . "<BR>";
                                    }
                                }
                            } else {
                                echo "---";
                            }
                            ?>
                        </td>
                    </tr>
                    <tr class="">
                        <td><a href="admin.php?page=swift_staff_admin_display_log">Back to Log List</a></td>
                        <td>&nbsp;</td>
                    </tr>
                <?php else: ?>
                    <tr id='user-1' class="">
                        <td scope='row' class='check-column' colspan="9" align="center" valign="middle"><?php _e('No Record found.', 'swift-mortgage-app') ?></th>
                    </tr>
                <?php
                endif; //first if end
                ?>
                </tbody>
            </table>
            <style type="text/css">
                .complete{ color:#66CD00; }
                .incomplete{ color:#F5F500; }
                .delete{ color:#FF0000; }
            </style>
            <script type="text/javascript">
                jQuery(document).ready(function () {
                    jQuery("abbr.timeago").timeago();
                });
            </script>
        </div>
    </div>
    <?php
}

/*
 *      Pagination
 */

function swift_staff_admin_log_pagination($num_of_pages, $pagenum, $total_filtered_log, $limit) {
    $page_links = paginate_links(array(
        'base' => add_query_arg('pagenum', '%#%'),
        'format' => '',
        'prev_text' => __('&laquo;', 'swift-cloud'),
        'next_text' => __('&raquo;', 'swift-cloud'),
        'total' => $num_of_pages,
        'current' => $pagenum
    ));
    if ($page_links) {
        if ($total_filtered_log > $limit) {
            echo '<div class="tablenav" id="swiftlog-pagination"><div class="tablenav-pages">' . $page_links . '</div></div>';
        }
    }
}

/*
 *      Genrate CSV file for logs
 */

function swift_staff_generate_csv_log($info) {
    ob_end_clean();
    $filename = 'swift_staff_form_submission_log_' . date('Y-m-d-H-i-s') . '.csv';
    header('Content-Disposition: attachment; filename=' . $filename);
    header('Content-Type: text/csv', true);

    $headers = array("Name", "Email", "Phone", "Status", "Date", "Time");
    echo implode(',', $headers) . "\n";

    foreach ($info as $log) {
        $date = $time = "";

        if (isset($log->date_time) && !empty($log->date_time)) {
            $dateTimeArr = @explode(" ", $log->date_time);
            $date = $dateTimeArr[0];
            $time = $dateTimeArr[1];
        }
        $log_arr = array();
        $log_arr[] = '"' . str_replace('"', '""', $log->name) . '"';
        $log_arr[] = '"' . str_replace('"', '""', $log->email) . '"';
        $log_arr[] = '"' . str_replace('"', '""', $log->phone) . '"';
        $log_arr[] = '"' . str_replace('"', '""', ($log->status == 1) ? "Complete" : "Incomplete") . '"';
        $log_arr[] = '"' . str_replace('"', '""', $date) . '"';
        $log_arr[] = '"' . str_replace('"', '""', $time) . '"';
        echo @implode(",", $log_arr) . "\n";
    }
    exit;
}
