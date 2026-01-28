<?php
require_once('../../config.php');
require_once($CFG->libdir . '/externallib.php');
require_once('externallib.php');

// Get the function name from the URL path
$path_info = $_SERVER['PATH_INFO'] ?? '';
$function = ltrim($path_info, '/');

// Validate function exists
$valid_functions = [
    'local_courseworkapi_get_student_coursework_by_intake',
    'local_courseworkapi_get_all_intakes',
    'local_courseworkapi_get_current_intake_for_cm'
];

if (!in_array($function, $valid_functions)) {
    http_response_code(404);
    echo json_encode(['error' => 'Function not found']);
    exit;
}

// Get JSON input
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON']);
    exit;
}

// Get authorization token
$headers = getallheaders();
$token = null;

if (isset($headers['Authorization'])) {
    $token = $headers['Authorization'];
} elseif (isset($headers['authorization'])) {
    $token = $headers['authorization'];
}

if (!$token) {
    http_response_code(401);
    echo json_encode(['error' => 'Authorization token required']);
    exit;
}

// Validate token
$token_record = $DB->get_record_sql("
    SELECT t.*, s.shortname as service_name
    FROM {external_tokens} t
    JOIN {external_services} s ON t.externalserviceid = s.id
    WHERE t.token = ? AND (t.validuntil IS NULL OR t.validuntil > ?)
", [$token, time()]);

if (!$token_record) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid or expired token']);
    exit;
}

// Set user context for the token owner
$user = $DB->get_record('user', ['id' => $token_record->userid]);
if (!$user) {
    http_response_code(401);
    echo json_encode(['error' => 'Token user not found']);
    exit;
}

// Simulate login for the token user
complete_user_login($user);

try {
    // Call the appropriate function
    switch ($function) {
        case 'local_courseworkapi_get_student_coursework_by_intake':
            $studentid = $data['studentid'] ?? null;
            $intakecode = $data['intakecode'] ?? null;
            $includeactive = $data['includeactive'] ?? false;
            
            if (!$studentid || !$intakecode) {
                throw new invalid_parameter_exception('studentid and intakecode are required');
            }
            
            $result = local_courseworkapi_external::get_student_coursework_by_intake(
                $studentid, $intakecode, $includeactive
            );
            break;
            
        case 'local_courseworkapi_get_all_intakes':
            $activeonly = $data['activeonly'] ?? false;
            $result = local_courseworkapi_external::get_all_intakes($activeonly);
            break;
            
        case 'local_courseworkapi_get_current_intake_for_cm':
            $cmid = $data['cmid'] ?? null;
            if (!$cmid) {
                throw new invalid_parameter_exception('cmid is required');
            }
            $result = local_courseworkapi_external::get_current_intake_for_cm($cmid);
            break;
            
        default:
            throw new invalid_parameter_exception('Unknown function');
    }
    
    // Return success response
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'data' => $result,
        'timestamp' => time()
    ]);
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'timestamp' => time()
    ]);
}
