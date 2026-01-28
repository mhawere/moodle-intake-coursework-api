<?php
defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // Create the main settings page for the plugin
    $settings = new admin_settingpage('local_courseworkapi', get_string('pluginname', 'local_courseworkapi'));
    
    // Add to the local plugins category
    $ADMIN->add('localplugins', $settings);
    
    // Add plugin description
    $settings->add(new admin_setting_heading(
        'local_courseworkapi/plugin_description',
        get_string('pluginname', 'local_courseworkapi'),
        'A comprehensive plugin for managing global intake periods and retrieving coursework data via API.'
    ));
    
    // Add a link to the intake management page
    $settings->add(new admin_setting_heading(
        'local_courseworkapi/intake_management_heading',
        get_string('intake_management', 'local_courseworkapi'),
        get_string('global_intakes_desc', 'local_courseworkapi')
    ));
    
    // Add intake management link
    $manageurl = new moodle_url('/local/courseworkapi/manage.php');
    $managelinkhtml = html_writer::div(
        html_writer::link($manageurl, get_string('intake_management', 'local_courseworkapi'), 
                         array('class' => 'btn btn-primary')) .
        html_writer::tag('p', 'Create and manage global intake periods that can be assigned to quizzes and assignments.', 
                         array('class' => 'text-muted mt-2')),
        'mb-3'
    );
    $settings->add(new admin_setting_description(
        'local_courseworkapi/manage_intakes_link',
        '',
        $managelinkhtml
    ));
    
    // Add API documentation link
    $docurl = new moodle_url('/local/courseworkapi/documentation.php');
    $doclinkhtml = html_writer::div(
        html_writer::link($docurl, get_string('api_documentation', 'local_courseworkapi'), 
                         array('class' => 'btn btn-info')) .
        html_writer::tag('p', 'View comprehensive API documentation with code examples and Postman collection.', 
                         array('class' => 'text-muted mt-2')),
        'mb-3'
    );
    $settings->add(new admin_setting_description(
        'local_courseworkapi/api_documentation_link',
        '',
        $doclinkhtml
    ));
    
    // Add system information
    $settings->add(new admin_setting_heading(
        'local_courseworkapi/system_info_heading',
        get_string('system_overview', 'local_courseworkapi'),
        ''
    ));
    
    // Check if tables exist
    global $DB;
    $tables_exist = $DB->get_manager()->table_exists('local_cwapi_intakes');
    $status_html = html_writer::div(
        html_writer::tag('p', 
            $tables_exist ? 
            '✅ Database tables installed and ready' : 
            '❌ Database tables not found - please reinstall plugin',
            array('class' => $tables_exist ? 'text-success' : 'text-danger')
        ),
        'alert ' . ($tables_exist ? 'alert-success' : 'alert-danger')
    );
    
    $settings->add(new admin_setting_description(
        'local_courseworkapi/system_status',
        'System Status',
        $status_html
    ));
}
