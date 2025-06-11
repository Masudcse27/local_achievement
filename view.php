<?php
require('../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/achievement/view.php', ['id' => $id]));
$PAGE->set_title(get_string('viewachievement', 'local_achievement'));
$PAGE->set_heading(get_string('viewachievement', 'local_achievement'));

$achievement = $DB->get_record('local_achievement', ['id' => $id], '*', MUST_EXIST);

echo $OUTPUT->header();

echo html_writer::tag('h2', format_string($achievement->title));
echo html_writer::div(format_text($achievement->description, FORMAT_HTML));
echo html_writer::empty_tag('hr');

$fs = get_file_storage();
$files = $fs->get_area_files($context->id, 'local_achievement', 'certificate', $id, '', false);

if (!empty($files)) {
    $file = reset($files); 
    $filename = $file->get_filename();

    $url = moodle_url::make_pluginfile_url(
        $context->id,
        'local_achievement',
        'certificate',
        $id,
        '/',
        $filename
    );

    echo html_writer::tag('h3', get_string('certificate', 'local_achievement'));
    echo html_writer::empty_tag('img', [
        'src' => $url,
        'alt' => 'Certificate',
        'style' => 'max-width: 100%; height: auto; border: 1px solid #ccc; padding: 5px;'
    ]);
} else {
    echo html_writer::div(get_string('nocertificatefound', 'local_achievement'), 'alert alert-info');
}

echo $OUTPUT->footer();
