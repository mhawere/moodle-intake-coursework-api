<?php
require_once($CFG->libdir . "/externallib.php");

class local_courseworkapi_external extends external_api {

    /**
     * Parameters for get_student_coursework_by_intake
     */
    public static function get_student_coursework_by_intake_parameters() {
        return new external_function_parameters(
            array(
                'studentid' => new external_value(PARAM_INT, 'Student user ID'),
                'intakecode' => new external_value(PARAM_ALPHANUMEXT, 'Intake code'),
                'includeactive' => new external_value(PARAM_BOOL, 'Include active coursework', VALUE_DEFAULT, false)
            )
        );
    }

    /**
     * Get student coursework results by intake
     */
    public static function get_student_coursework_by_intake($studentid, $intakecode, $includeactive = false) {
        global $DB, $CFG;
        
        $params = self::validate_parameters(
            self::get_student_coursework_by_intake_parameters(),
            array('studentid' => $studentid, 'intakecode' => $intakecode, 'includeactive' => $includeactive)
        );
        
        // Validate context
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/courseworkapi:use_webservice', $context);
        
        // Validate user exists
        $user = $DB->get_record('user', array('id' => $params['studentid'], 'deleted' => 0));
        if (!$user) {
            throw new invalid_parameter_exception('User not found');
        }
        
        // Get intake
        $intake = $DB->get_record('local_cwapi_intakes', array('code' => $params['intakecode']));
        if (!$intake) {
            throw new invalid_parameter_exception('Intake not found: ' . $params['intakecode']);
        }
        
        $coursework = array();
        
        // Get quiz results
        $quizzes = self::get_quiz_results($params['studentid'], $intake->id, $params['includeactive']);
        $coursework = array_merge($coursework, $quizzes);
        
        // Get assignment results
        $assignments = self::get_assignment_results($params['studentid'], $intake->id, $params['includeactive']);
        $coursework = array_merge($coursework, $assignments);
        
        return array(
            'studentid' => $params['studentid'],
            'studentname' => fullname($user),
            'intakecode' => $params['intakecode'],
            'intakename' => $intake->name,
            'coursework' => $coursework,
            'totalitems' => count($coursework)
        );
    }

    /**
     * Get quiz results for student by intake
     */
    private static function get_quiz_results($studentid, $intakeid, $includeactive) {
        global $DB;
        
        $activeclause = $includeactive ? '' : 'AND qa.state = :finishedstate';
        $params = array('studentid' => $studentid, 'intakeid' => $intakeid);
        if (!$includeactive) {
            $params['finishedstate'] = 'finished';
        }
        
        $sql = "SELECT qa.id,
                       q.name as coursework_name,
                       c.fullname as course_name,
                       c.shortname as course_code,
                       qa.sumgrades as raw_score,
                       q.grade as max_grade,
                       ROUND((qa.sumgrades / q.grade) * 100, 2) as percentage,
                       qa.timestart,
                       qa.timefinish,
                       qa.state,
                       'quiz' as type
                FROM {quiz_attempts} qa
                JOIN {quiz} q ON qa.quiz = q.id
                JOIN {local_cwapi_quiz_map} qm ON q.id = qm.quizid
                JOIN {course} c ON q.course = c.id
                WHERE qa.userid = :studentid 
                AND qm.intakeid = :intakeid
                $activeclause
                ORDER BY qa.timefinish DESC";
        
        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Get assignment results for student by intake
     */
    private static function get_assignment_results($studentid, $intakeid, $includeactive) {
        global $DB;
        
        $activeclause = $includeactive ? '' : 'AND s.status = :submittedstatus';
        $params = array('studentid' => $studentid, 'intakeid' => $intakeid);
        if (!$includeactive) {
            $params['submittedstatus'] = 'submitted';
        }
        
        $sql = "SELECT s.id,
                       a.name as coursework_name,
                       c.fullname as course_name,
                       c.shortname as course_code,
                       g.grade as raw_score,
                       a.grade as max_grade,
                       CASE WHEN g.grade IS NOT NULL AND a.grade > 0 
                            THEN ROUND((g.grade / a.grade) * 100, 2) 
                            ELSE NULL END as percentage,
                       s.timemodified as timestart,
                       s.timemodified as timefinish,
                       s.status as state,
                       'assignment' as type
                FROM {assign_submission} s
                JOIN {assign} a ON s.assignment = a.id
                JOIN {local_cwapi_assign_map} am ON a.id = am.assignmentid
                JOIN {course} c ON a.course = c.id
                LEFT JOIN {assign_grades} g ON g.assignment = a.id AND g.userid = s.userid
                WHERE s.userid = :studentid 
                AND am.intakeid = :intakeid
                $activeclause
                ORDER BY s.timemodified DESC";
        
        return $DB->get_records_sql($sql, $params);
    }

    /**
     * Returns for get_student_coursework_by_intake
     */
    public static function get_student_coursework_by_intake_returns() {
        return new external_single_structure(
            array(
                'studentid' => new external_value(PARAM_INT, 'Student ID'),
                'studentname' => new external_value(PARAM_TEXT, 'Student full name'),
                'intakecode' => new external_value(PARAM_ALPHANUMEXT, 'Intake code'),
                'intakename' => new external_value(PARAM_TEXT, 'Intake name'),
                'totalitems' => new external_value(PARAM_INT, 'Total coursework items'),
                'coursework' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Record ID'),
                            'coursework_name' => new external_value(PARAM_TEXT, 'Coursework name'),
                            'course_name' => new external_value(PARAM_TEXT, 'Course name'),
                            'course_code' => new external_value(PARAM_TEXT, 'Course code'),
                            'raw_score' => new external_value(PARAM_FLOAT, 'Raw score', VALUE_OPTIONAL),
                            'max_grade' => new external_value(PARAM_FLOAT, 'Maximum grade'),
                            'percentage' => new external_value(PARAM_FLOAT, 'Percentage score', VALUE_OPTIONAL),
                            'timestart' => new external_value(PARAM_INT, 'Start time'),
                            'timefinish' => new external_value(PARAM_INT, 'Finish time', VALUE_OPTIONAL),
                            'state' => new external_value(PARAM_TEXT, 'Submission state'),
                            'type' => new external_value(PARAM_TEXT, 'Coursework type (quiz/assignment)')
                        )
                    )
                )
            )
        );
    }

    /**
     * Parameters for get_all_intakes
     */
    public static function get_all_intakes_parameters() {
        return new external_function_parameters(
            array(
                'activeonly' => new external_value(PARAM_BOOL, 'Return only active intakes', VALUE_DEFAULT, false)
            )
        );
    }

    /**
     * Get all intake periods
     */
    public static function get_all_intakes($activeonly = false) {
        global $DB;
        
        $params = self::validate_parameters(
            self::get_all_intakes_parameters(),
            array('activeonly' => $activeonly)
        );
        
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/courseworkapi:view_intakes', $context);
        
        $where = $params['activeonly'] ? 'WHERE active = 1' : '';
        $intakes = $DB->get_records_sql("
            SELECT i.*, u.firstname, u.lastname
            FROM {local_cwapi_intakes} i
            JOIN {user} u ON i.createdby = u.id
            $where
            ORDER BY i.timecreated DESC
        ");
        
        $result = array();
        foreach ($intakes as $intake) {
            $result[] = array(
                'id' => $intake->id,
                'name' => $intake->name,
                'code' => $intake->code,
                'description' => $intake->description,
                'active' => $intake->active,
                'timecreated' => $intake->timecreated,
                'timemodified' => $intake->timemodified,
                'createdby' => fullname($intake)
            );
        }
        
        return array('intakes' => $result);
    }

    /**
     * Returns for get_all_intakes
     */
    public static function get_all_intakes_returns() {
        return new external_single_structure(
            array(
                'intakes' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'Intake ID'),
                            'name' => new external_value(PARAM_TEXT, 'Intake name'),
                            'code' => new external_value(PARAM_ALPHANUMEXT, 'Intake code'),
                            'description' => new external_value(PARAM_TEXT, 'Intake description', VALUE_OPTIONAL),
                            'active' => new external_value(PARAM_BOOL, 'Is active'),
                            'timecreated' => new external_value(PARAM_INT, 'Created time'),
                            'timemodified' => new external_value(PARAM_INT, 'Modified time'),
                            'createdby' => new external_value(PARAM_TEXT, 'Created by user')
                        )
                    )
                )
            )
        );
    }

    /**
     * Parameters for get_current_intake_for_cm
     */
    public static function get_current_intake_for_cm_parameters() {
        return new external_function_parameters(
            array(
                'cmid' => new external_value(PARAM_INT, 'Course module ID')
            )
        );
    }

    /**
     * Get current intake for course module
     */
    public static function get_current_intake_for_cm($cmid) {
        global $DB;
        
        $params = self::validate_parameters(
            self::get_current_intake_for_cm_parameters(),
            array('cmid' => $cmid)
        );
        
        $context = context_system::instance();
        self::validate_context($context);
        require_capability('local/courseworkapi:view_intakes', $context);
        
        // Get course module
        $cm = $DB->get_record('course_modules', array('id' => $params['cmid']));
        if (!$cm) {
            return array('intakeid' => 0, 'intakename' => '');
        }
        
        // Get module info
        $module = $DB->get_record('modules', array('id' => $cm->module));
        if (!$module || !in_array($module->name, ['quiz', 'assign'])) {
            return array('intakeid' => 0, 'intakename' => '');
        }
        
        // Check for intake mapping
        if ($module->name === 'quiz') {
            $mapping = $DB->get_record_sql("
                SELECT qm.intakeid, i.name
                FROM {local_cwapi_quiz_map} qm
                JOIN {local_cwapi_intakes} i ON qm.intakeid = i.id
                WHERE qm.quizid = ?
            ", array($cm->instance));
        } else {
            $mapping = $DB->get_record_sql("
                SELECT am.intakeid, i.name
                FROM {local_cwapi_assign_map} am
                JOIN {local_cwapi_intakes} i ON am.intakeid = i.id
                WHERE am.assignmentid = ?
            ", array($cm->instance));
        }
        
        if ($mapping) {
            return array(
                'intakeid' => $mapping->intakeid,
                'intakename' => $mapping->name
            );
        }
        
        return array('intakeid' => 0, 'intakename' => '');
    }

    /**
     * Returns for get_current_intake_for_cm
     */
    public static function get_current_intake_for_cm_returns() {
        return new external_single_structure(
            array(
                'intakeid' => new external_value(PARAM_INT, 'Intake ID'),
                'intakename' => new external_value(PARAM_TEXT, 'Intake name')
            )
        );
    }
}
