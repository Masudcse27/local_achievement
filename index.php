<?php
require('../../config.php');
require_login();

$context = context_system::instance();
$canmanage = has_capability('local/achievement:manageachievements', $context);

// var_dump($canmanage);
// die();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/achievement/index.php'));
$PAGE->set_title('Student Achievement');
$PAGE->set_heading('Student Achievement');

$sql = "SELECT a.*, u.firstname, u.lastname
        FROM {local_achievement} a
        JOIN {user} u ON a.userid = u.id";

$achievements = $DB->get_records_sql($sql);

$template_context = [
    'canmanage' => $canmanage,
    'heading' => 'Achievement List',
    'achievements' => !empty($achievements) ,
    'newurl' => new moodle_url('/local/achievement/edit.php'),
    'create_new' => get_string('create_new', 'local_achievement'),
    'list' => []
];
foreach($achievements as $achievement){
    $item = [
        'user_name' => $achievement->firstname. " " . $achievement->lastname,
        'title' => $achievement->title,
        'editurl' => new moodle_url('/local/achievement/edit.php', ['id' => $achievement->id]),
        'show_url' => new moodle_url('/local/achievement/view.php', ['id' => $achievement->id]),
        'deleteurl' => new moodle_url('/local/achievement/delete.php', ['id' => $achievement->id])
    ];
    $template_context['list'][] = $item;
}

echo $OUTPUT->header();
echo $OUTPUT->render_from_template('local_achievement/achievementlist', $template_context);
echo $OUTPUT->footer();