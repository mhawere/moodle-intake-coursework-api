<?php
defined('MOODLE_INTERNAL') || die();

/**
 * Inject JavaScript on module edit pages
 */
function local_courseworkapi_before_footer() {
    global $PAGE;
    
    // Check if we're on the module edit page
    if (strpos($PAGE->url->get_path(), '/course/modedit.php') !== false) {
        $PAGE->requires->js_call_amd('local_courseworkapi/intake_form_injection', 'init');
    }
}

/**
 * Process intake selection when saving module
 */
function local_courseworkapi_coursemodule_edit_post_actions($data, $course) {
    global $DB, $USER;
    
    // Get intake selection from form
    $intake_selection = optional_param('intake_selection', null, PARAM_INT);
    
    // Validate we have necessary data
    if ($intake_selection === null || !isset($data->modulename) || !isset($data->instance)) {
        return $data;
    }
    
    // Only process for quiz and assign
    if (!in_array($data->modulename, ['quiz', 'assign'])) {
        return $data;
    }
    
    // Determine table and field
    $table = ($data->modulename === 'quiz') ? 'local_cwapi_quiz_map' : 'local_cwapi_assign_map';
    $field = ($data->modulename === 'quiz') ? 'quizid' : 'assignmentid';
    
    // Delete existing mapping
    $DB->delete_records($table, [$field => $data->instance]);
    
    // Add new mapping if intake selected
    if ($intake_selection > 0) {
        $link = new stdClass();
        $link->$field = $data->instance;
        $link->intakeid = $intake_selection;
        $link->timecreated = time();
        $link->createdby = $USER->id;
        
        try {
            $DB->insert_record($table, $link);
        } catch (Exception $e) {
            debugging('Failed to save intake mapping: ' . $e->getMessage(), DEBUG_DEVELOPER);
        }
    }
    
    return $data;
}

/**
 * Extend navigation with intake management
 */
function local_courseworkapi_extend_settings_navigation($settingsnav, $context) {
    global $PAGE;
    
    if (!has_capability('local/courseworkapi:manage_intakes', context_system::instance())) {
        return;
    }
    
    if ($settingnode = $settingsnav->find('siteadministration', navigation_node::TYPE_SITE_ADMIN)) {
        if ($localplugins = $settingnode->find('localplugins', navigation_node::TYPE_CATEGORY)) {
            $url = new moodle_url('/local/courseworkapi/manage.php');
            $localplugins->add(
                get_string('intake_management', 'local_courseworkapi'),
                $url,
                navigation_node::TYPE_SETTING
            );
        }
    }
}

/**
 * Extend navigation for admin tree
 */
function local_courseworkapi_extend_navigation_category_settings($navigation, $coursecategorycontext) {
    // This function is called when extending category navigation
}

/**
 * Extend global navigation
 */
function local_courseworkapi_extend_navigation(global_navigation $navigation) {
    // This function is called to extend global navigation if needed
}
