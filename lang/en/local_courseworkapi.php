<?php
defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Coursework API';
$string['privacy:metadata'] = 'The Coursework API plugin does not store any personal data.';

// Capabilities
$string['courseworkapi:manage_intakes'] = 'Manage global intakes';
$string['courseworkapi:view_intakes'] = 'View intakes';
$string['courseworkapi:use_webservice'] = 'Use coursework API web service';

// Admin interface
$string['intake_management'] = 'Global Intake Management';
$string['system_overview'] = 'System Overview';
$string['global_intakes'] = 'Global Intakes';
$string['global_intakes_desc'] = 'University-wide enrollment periods that can be assigned to quizzes and assignments.';
$string['intake_instruction_1'] = 'Create intakes here (e.g., "Jan/May2025", "Sep2024", "Semester1-2025")';
$string['intake_instruction_2'] = 'Lecturers will see these intakes as dropdown options when creating/editing quizzes and assignments';
$string['intake_instruction_3'] = 'Student results can be retrieved by intake across all courses via API';
$string['system_ready'] = 'System Ready';
$string['system_ready_desc'] = 'Database tables exist and ready for use.';

// Forms
$string['add_new_intake'] = 'Add New Global Intake';
$string['intake_name'] = 'Intake Name';
$string['code'] = 'Code';
$string['description'] = 'Description';
$string['intake_description_optional'] = 'Description (Optional)';
$string['add_intake'] = 'Add Intake';
$string['existing_intakes'] = 'Existing Global Intakes';
$string['status'] = 'Status';
$string['created_by'] = 'Created By';
$string['created'] = 'Created';
$string['actions'] = 'Actions';
$string['no_intakes_found'] = 'No intakes found. Add your first intake above.';

// Instructions
$string['next_steps'] = 'Next Steps for Lecturers';
$string['next_steps_desc'] = 'Now that intakes are created, lecturers can:';
$string['step_1'] = 'Go to any course';
$string['step_2'] = 'Create or edit a quiz/assignment';
$string['step_3'] = 'Look for the "Intake Assignment" section in the form';
$string['step_4'] = 'Select an intake from the dropdown';
$string['step_5'] = 'Save the coursework';
$string['api_usage_example'] = 'API Usage Example';
$string['view_api_documentation'] = 'View Full API Documentation';

// API Documentation
$string['api_documentation'] = 'Coursework API Documentation';
$string['endpoints'] = 'API Endpoints';
$string['standard_endpoint'] = 'Standard Endpoint';
$string['restful_endpoint'] = 'RESTful Endpoint (JSON)';
$string['current_token'] = 'Current Token';
$string['token_valid_until'] = 'Valid until';
$string['available_functions'] = 'Available Functions';
$string['postman_collection'] = 'Postman Collection';
$string['postman_instructions'] = 'Copy this JSON and import it into Postman:';
$string['copy_to_clipboard'] = 'Copy to Clipboard';
$string['code_examples'] = 'Code Examples';

// Error messages
$string['intakenotfound'] = 'Intake not found: {$a}';
$string['usernotfound'] = 'User not found';
$string['sesskey_missing'] = 'Session key is missing';

// Form injection
$string['intake_assignment'] = 'Intake Assignment';
$string['select_intake'] = 'Select Intake';
$string['no_intake'] = 'No intake selected';
$string['assign_to_intake'] = 'Assign to Intake';
$string['intake_selection_help'] = 'Select an intake period to associate this coursework with.';
