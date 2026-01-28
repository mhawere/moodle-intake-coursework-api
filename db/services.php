<?php
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_courseworkapi_get_student_coursework_by_intake' => array(
        'classname'   => 'local_courseworkapi_external',
        'methodname'  => 'get_student_coursework_by_intake',
        'classpath'   => 'local/courseworkapi/externallib.php',
        'description' => 'Get student coursework results filtered by intake code',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities' => 'local/courseworkapi:use_webservice',
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE, 'courseworkapi_service')
    ),
    
    'local_courseworkapi_get_all_intakes' => array(
        'classname'   => 'local_courseworkapi_external',
        'methodname'  => 'get_all_intakes',
        'classpath'   => 'local/courseworkapi/externallib.php',
        'description' => 'Get all global intake periods',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities' => 'local/courseworkapi:view_intakes',
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE, 'courseworkapi_service')
    ),
    
    'local_courseworkapi_get_current_intake_for_cm' => array(
        'classname'   => 'local_courseworkapi_external',
        'methodname'  => 'get_current_intake_for_cm',
        'classpath'   => 'local/courseworkapi/externallib.php',
        'description' => 'Get current intake assignment for a course module',
        'type'        => 'read',
        'ajax'        => true,
        'capabilities' => 'local/courseworkapi:view_intakes',
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE, 'courseworkapi_service')
    )
);

$services = array(
    'courseworkapi_service' => array(
        'functions' => array(
            'local_courseworkapi_get_student_coursework_by_intake',
            'local_courseworkapi_get_all_intakes',
            'local_courseworkapi_get_current_intake_for_cm'
        ),
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' => 'courseworkapi_service',
        'downloadfiles' => 0,
        'uploadfiles' => 0
    )
);
