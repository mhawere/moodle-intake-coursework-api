<?php
require_once('../../config.php');
require_once($CFG->libdir . '/adminlib.php');

require_login();
$context = context_system::instance();

$PAGE->set_url(new moodle_url('/local/courseworkapi/documentation.php'));
$PAGE->set_context($context);
$PAGE->set_pagelayout('admin');
$PAGE->set_title(get_string('api_documentation', 'local_courseworkapi'));
$PAGE->set_heading(get_string('api_documentation', 'local_courseworkapi'));

// Include CSS
$PAGE->requires->css('/local/courseworkapi/styles.css');

// Get current token for the user
$token = $DB->get_record_sql("
    SELECT t.* FROM {external_tokens} t
    JOIN {external_services} s ON t.externalserviceid = s.id
    WHERE t.userid = ? AND s.shortname IN ('moodle_mobile_app', 'courseworkapi_service')
    ORDER BY t.timecreated DESC LIMIT 1",
    [$USER->id]
);

$functions = [
    [
        'id' => 1,
        'name' => 'local_courseworkapi_get_student_coursework_by_intake',
        'description' => 'Get student coursework results by intake code',
        'parameters' => '{
    "studentid": 4540,
    "intakecode": "Janmay2025",
    "includeactive": 0
}',
        'example' => 'curl -X POST "' . $CFG->wwwroot . '/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake" \\
  -H "Authorization: YOUR_TOKEN" \\
  -H "Content-Type: application/json" \\
  -d \'{"studentid": 4540, "intakecode": "Janmay2025", "includeactive": 0}\''
    ],
    [
        'id' => 2,
        'name' => 'local_courseworkapi_get_all_intakes',
        'description' => 'Get all global intakes',
        'parameters' => '{
    "activeonly": 1
}',
        'example' => 'curl -X POST "' . $CFG->wwwroot . '/local/courseworkapi/restful.php/local_courseworkapi_get_all_intakes" \\
  -H "Authorization: YOUR_TOKEN" \\
  -H "Content-Type: application/json" \\
  -d \'{"activeonly": 1}\''
    ],
    [
        'id' => 3,
        'name' => 'local_courseworkapi_get_current_intake_for_cm',
        'description' => 'Get current intake for course module',
        'parameters' => '{
    "cmid": 123
}',
        'example' => 'curl -X POST "' . $CFG->wwwroot . '/local/courseworkapi/restful.php/local_courseworkapi_get_current_intake_for_cm" \\
  -H "Authorization: YOUR_TOKEN" \\
  -H "Content-Type: application/json" \\
  -d \'{"cmid": 123}\''
    ]
];

$postman_collection = '{
    "info": {
        "name": "Coursework API Collection",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "variable": [
        {
            "key": "BASE_URL",
            "value": "' . $CFG->wwwroot . '/local/courseworkapi/restful.php"
        },
        {
            "key": "TOKEN",
            "value": "' . ($token ? $token->token : 'YOUR_TOKEN_HERE') . '"
        }
    ],
    "item": [
        {
            "name": "Get Student Coursework by Intake",
            "request": {
                "method": "POST",
                "header": [
                    {"key": "Authorization", "value": "{{TOKEN}}"},
                    {"key": "Content-Type", "value": "application/json"}
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"studentid\": 4540,\n    \"intakecode\": \"Janmay2025\",\n    \"includeactive\": 0\n}"
                },
                "url": "{{BASE_URL}}/local_courseworkapi_get_student_coursework_by_intake"
            }
        },
        {
            "name": "Get All Intakes",
            "request": {
                "method": "POST",
                "header": [
                    {"key": "Authorization", "value": "{{TOKEN}}"},
                    {"key": "Content-Type", "value": "application/json"}
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"activeonly\": 1\n}"
                },
                "url": "{{BASE_URL}}/local_courseworkapi_get_all_intakes"
            }
        },
        {
            "name": "Get Current Intake for Course Module",
            "request": {
                "method": "POST",
                "header": [
                    {"key": "Authorization", "value": "{{TOKEN}}"},
                    {"key": "Content-Type", "value": "application/json"}
                ],
                "body": {
                    "mode": "raw",
                    "raw": "{\n    \"cmid\": 123\n}"
                },
                "url": "{{BASE_URL}}/local_courseworkapi_get_current_intake_for_cm"
            }
        }
    ]
}';

$curl_example = 'curl -X POST "' . $CFG->wwwroot . '/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake" \\
  -H "Authorization: ' . ($token ? $token->token : 'YOUR_TOKEN') . '" \\
  -H "Content-Type: application/json" \\
  -d \'{"studentid": 4540, "intakecode": "Janmay2025", "includeactive": 0}\'';

$php_example = '<?php
$token = \'' . ($token ? $token->token : 'YOUR_TOKEN') . '\';
$url = \'' . $CFG->wwwroot . '/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake\';

$data = [
    \'studentid\' => 4540,
    \'intakecode\' => \'Janmay2025\',
    \'includeactive\' => 0
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    \'Authorization: \' . $token,
    \'Content-Type: application/json\'
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
print_r($result);';

$javascript_example = 'const token = \'' . ($token ? $token->token : 'YOUR_TOKEN') . '\';
const url = \'' . $CFG->wwwroot . '/local/courseworkapi/restful.php/local_courseworkapi_get_student_coursework_by_intake\';

const data = {
    studentid: 4540,
    intakecode: \'Janmay2025\',
    includeactive: 0
};

fetch(url, {
    method: \'POST\',
    headers: {
        \'Authorization\': token,
        \'Content-Type\': \'application/json\'
    },
    body: JSON.stringify(data)
})
.then(response => response.json())
.then(result => console.log(result))
.catch(error => console.error(\'Error:\', error));';

$delphi_example = 'procedure GetStudentCoursework;
var
  RequestBody: TJSONObject;
begin
  BaseURL := \'' . $CFG->wwwroot . '/local/courseworkapi/restful.php/\';
  AuthKey := \'' . ($token ? $token->token : 'YOUR_TOKEN') . '\';
  
  RESTClient.BaseURL := BaseURL;
  RESTRequest.ClearBody;
  RESTRequest.Method := TRESTRequestMethod.rmPOST;
  
  RequestHeader.Clear();
  RequestHeader.Add(\'Authorization\', AuthKey);
  RequestHeader.Add(\'Content-Type\', \'application/json\');
  RequestHeader.Add(\'Accept\', \'application/json\');
  
  RequestBody := TJSONObject.Create;
  RequestBody.AddPair(\'studentid\', TJSONNumber.Create(4540));
  RequestBody.AddPair(\'intakecode\', \'Janmay2025\');
  RequestBody.AddPair(\'includeactive\', TJSONNumber.Create(0));
  
  RESTRequest.Resource := \'local_courseworkapi_get_student_coursework_by_intake\';
  RESTRequest.Body.Add(RequestBody.ToString, TRESTContentType.ctAPPLICATION_JSON);
  RESTRequest.Execute;
end;';

$templatedata = [
    'standard_endpoint' => $CFG->wwwroot . '/webservice/rest/server.php',
    'restful_endpoint' => $CFG->wwwroot . '/local/courseworkapi/restful.php',
    'token' => $token ? $token->token : 'No token found - please create one',
    'token_expiry' => $token && $token->validuntil ? userdate($token->validuntil) : 'Never',
    'functions' => $functions,
    'postman_collection' => $postman_collection,
    'curl_example' => $curl_example,
    'php_example' => $php_example,
    'javascript_example' => $javascript_example,
    'delphi_example' => $delphi_example
];

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_courseworkapi/api_documentation', $templatedata);
echo $OUTPUT->footer();
