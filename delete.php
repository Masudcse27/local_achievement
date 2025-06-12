<?php
require('../../config.php');
require_login();
$context = context_system::instance();

if (!has_capability('local/achievement:manageachievements', $context)) {
    // Redirect to index.php with a "no permission" message
    redirect(
        new moodle_url('/local/achievement/index.php'),
        get_string('nopermissiontomanage', 'local_achievement'),
        null,
        \core\output\notification::NOTIFY_ERROR
    );
}

$id = required_param('id', PARAM_INT);
if(!$id){
    redirect(new moodle_url('/local/achievement/index.php'), get_string('id_require', 'local_achievement'));
}
$event = $DB->get_record('local_achievement', ['id' => $id], '*');
if(!$event){
    redirect(new moodle_url('/local/achievement/index.php'), get_string('invalid_id', 'local_achievement'));
}
$DB->delete_records('local_achievement', ['id' => $id]);
redirect(new moodle_url('/local/achievement/index.php'), get_string('deleted','local_achievement'));