<?php

namespace local_achievement\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class achievement_form extends \moodleform{
    public function definition()  {
        global $DB;

        $mform = $this->_form;
        $studentroleid = 5;
        $sql = "SELECT u.id, CONCAT(u.firstname, ' ', u.lastname) AS fullname
                FROM {user} u
                JOIN {role_assignments} ra ON ra.userid = u.id
                WHERE ra.roleid = :roleid";
        $users = $DB->get_records_sql_menu($sql, ['roleid' => $studentroleid]);

        $mform->addElement('select', 'userid', get_string('user'), $users);
        $mform->addRule('userid', null, 'required', null, 'client');

        $mform->addElement('text', 'title', get_string('title', 'local_achievement'));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required', null, 'client');

        $mform->addElement('editor', 'description_editor', get_string('description', 'local_achievement'), null);
        $mform->setType('description_editor', PARAM_RAW);

        $mform->addElement('filepicker', 'certificate', get_string('certificate', 'local_achievement'), null, [
            'accepted_types' => ['.jpg', '.png', '.jpeg', '.pdf']
        ]);
        $mform->addRule('certificate', null, 'required', null, 'client');


        $mform->addElement('hidden', 'timecreated', time());
        $mform->setType('timecreated', PARAM_INT);

        $mform->addElement('hidden', 'timemodified', time());
        $mform->setType('timemodified', PARAM_INT);

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('savechanges'));
    }
}