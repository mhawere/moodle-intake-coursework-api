<?php
defined('MOODLE_INTERNAL') || die();

function xmldb_local_courseworkapi_upgrade($oldversion) {
    global $CFG, $DB;
    
    $dbman = $DB->get_manager();
    
    // Add future upgrade steps here as needed
    
    return true;
}
