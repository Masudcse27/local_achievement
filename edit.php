<?php
require('../../config.php');
use local_achievement\form\achievement_form;

require_login();

$context = context_system::instance();
$PAGE->set_context($context);

$id = optional_param('id', '0', PARAM_INT);
$achievement = $id ? $DB->get_record('local_achievement', ['id' => $id], '*', MUST_EXIST) : null;

$PAGE->set_url(new moodle_url('/local/achievement/edit.php', ['id' => $id]));

$PAGE->set_title($id ? get_string('editachievement', 'local_achievement') : get_string('createachievement', 'local_achievement'));
$PAGE->set_heading($id ? get_string('editachievement', 'local_achievement') : get_string('createachievement', 'local_achievement'));

$mform = new achievement_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/local/achievement/index.php'), get_string('cancelled_form', 'local_achievement'));
} else if ($data = $mform->get_data()) {
    $record = (object)[
        'userid' => $data->userid,
        'title' => $data->title,
        'description' => $data->description_editor['text'],
        'timecreated' => $data->timecreated,
        'timemodified' => time(),
    ];

    if ($data->id) {
        $record->id = $data->id;
        $DB->update_record('local_achievement', $record);
    } else {
        $record->timecreated = time();
        $record->id = $DB->insert_record('local_achievement', $record);
    }

    $draftitemid = $data->certificate;
    file_save_draft_area_files(
        $draftitemid,
        $context->id,
        'local_achievement',
        'certificate',
        $record->id,
        ['subdirs' => 0, 'maxbytes' => 10485760, 'accepted_types' => ['image']]
    );

    redirect(new moodle_url('/local/achievement/index.php'), get_string('success', 'local_achievement'));
}
if ($achievement) {
    $achievement->description_editor = [
        'text' => $achievement->description,
        'format' => FORMAT_HTML
    ];

    $draftitemid = file_get_submitted_draft_itemid('certificate');
    file_prepare_draft_area(
        $draftitemid,
        $context->id,
        'local_achievement',
        'certificate',
        $achievement->id,
        ['subdirs' => 0, 'maxbytes' => 10485760, 'accepted_types' => ['image']]
    );
    $achievement->certificate = $draftitemid;

    $mform->set_data($achievement);
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();