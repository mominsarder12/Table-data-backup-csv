<?php
/*
 * Plugin Name:       Table data backup csv
 * Plugin URI:         https://github.com/mominsarder12/table-data-backup-csv
 * Description:       export table  deta as csv format
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Momin Sarder
 * Author URI:        https://github.com/mominsarder12/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/mominsarder12/table-data-backup-csv
 * Text Domain:       textdomain-mstdbc
 * Domain Path:       /languages
 */

//admin menu
add_action("admin_menu", "tdbc_create_admin_menu");
function tdbc_create_admin_menu() {

    add_menu_page("CSV Data Backup", "CSV Data Backup", "manage_options", "tdbc-admin-menu", "tdbc_admin_form", "dashicons-media-spreadsheet", 76);
}
function tdbc_admin_form() {
    ob_start();

    include_once plugin_dir_path(__FILE__) . 'template/backup-form.php';
    $layout = ob_get_contents();
    ob_end_clean();
    echo $layout;
}

// export data 
add_action('init', 'ms_tdbc_export_data');
function ms_tdbc_export_data() {
    if (isset($_POST['ms_tdbc_submit'])) {
        global $wpdb;
        $table_name = $wpdb->prefix . "students_data";
        $students = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        if (empty($students)) {
            wp_die("Empty Records");
        }
      

        $filename = "students_data_" . time() . ".csv";

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        $output = fopen("php://output", "w");

        // Write the CSV column headers
        fputcsv($output, array_keys($students[0]));

        // Write the data rows
        foreach ($students as $row) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit();
    }
}
