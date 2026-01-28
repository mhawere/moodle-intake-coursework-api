<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

$action = optional_param('action', '', PARAM_ALPHA);
$id = optional_param('id', 0, PARAM_INT);

require_login();
$context = context_system::instance();
require_capability('local/courseworkapi:manage_intakes', $context);

$PAGE->set_url(new moodle_url('/local/courseworkapi/manage.php'));
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('intake_management', 'local_courseworkapi'));
$PAGE->set_heading(get_string('intake_management', 'local_courseworkapi'));

// Include CSS
$PAGE->requires->css('/local/courseworkapi/styles.css');

// Handle actions
if ($action === 'toggle' && $id && confirm_sesskey()) {
    $intake = $DB->get_record('local_cwapi_intakes', ['id' => $id], '*', MUST_EXIST);
    $intake->active = $intake->active ? 0 : 1;
    $intake->timemodified = time();
    $DB->update_record('local_cwapi_intakes', $intake);
    redirect($PAGE->url, get_string('success'));
}

if ($action === 'add' && confirm_sesskey()) {
    $name = required_param('name', PARAM_TEXT);
    $description = optional_param('description', '', PARAM_TEXT);
    
    if (!empty($name)) {
        $code = preg_replace('/[^A-Za-z0-9]/', '', $name);
        
        if ($DB->record_exists('local_cwapi_intakes', ['code' => $code])) {
            redirect($PAGE->url, get_string('error') . ': Intake code already exists', 
                    null, \core\output\notification::NOTIFY_ERROR);
        } else {
            $intake = new stdClass();
            $intake->name = clean_param($name, PARAM_TEXT);
            $intake->code = $code;
            $intake->description = clean_param($description, PARAM_TEXT);
            $intake->active = 1;
            $intake->timecreated = time();
            $intake->timemodified = time();
            $intake->createdby = $USER->id;
            
            $DB->insert_record('local_cwapi_intakes', $intake);
            redirect($PAGE->url, get_string('success'));
        }
    }
}

// Prepare template data
$intakes = $DB->get_records_sql("
    SELECT i.*, u.firstname, u.lastname
    FROM {local_cwapi_intakes} i
    JOIN {user} u ON i.createdby = u.id
    ORDER BY i.timecreated DESC
");

$templatedata = [
    'sesskey' => sesskey(),
    'formurl' => $PAGE->url->out(false),
    'has_intakes' => !empty($intakes),
    'intakes' => [],
    'doc_url' => new moodle_url('/local/courseworkapi/documentation.php'),
    'api_example' => "curl -X POST '" . $CFG->wwwroot . "/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake' \\
  -H 'Authorization: YOUR_TOKEN' \\
  -H 'Content-Type: application/json' \\
  -d '{\"studentid\": 4540, \"intakecode\": \"Janmay2025\", \"includeactive\": 0}'"
];

foreach ($intakes as $intake) {
    $toggleurl = new moodle_url('/local/courseworkapi/manage.php', [
        'action' => 'toggle',
        'id' => $intake->id,
        'sesskey' => sesskey()
    ]);
    
    $templatedata['intakes'][] = [
        'name' => $intake->name,
        'code' => $intake->code,
        'description' => $intake->description,
        'active' => $intake->active,
        'created_by' => fullname($intake),
        'created_date' => userdate($intake->timecreated),
        'toggle_url' => $toggleurl->out(false)
    ];
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_courseworkapi/intake_management', $templatedata);
echo $OUTPUT->footer();
