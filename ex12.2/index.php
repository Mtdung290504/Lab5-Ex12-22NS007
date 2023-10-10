<?php
$lifetime = 60 * 60 * 24 * 14;
session_set_cookie_params($lifetime, '/');
session_start();

// Lấy mảng tasks từ session
$task_list = isset($_SESSION['tasklist']) ? $_SESSION['tasklist'] : array();
$action = filter_input(INPUT_POST, 'action');
$errors = array();

switch($action) {
    case 'add':
        $new_task = filter_input(INPUT_POST, 'newtask');
        empty($new_task)? $errors[] = 'The new task cannot be empty.' : $task_list[] = $new_task;
        break;
    case 'delete':
        $task_index = filter_input(INPUT_POST, 'taskid', FILTER_VALIDATE_INT);
        if ($task_index === NULL || $task_index === FALSE) {
            $errors[] = 'The task cannot be deleted.';
        } else {
            unset($task_list[$task_index]);
            $task_list = array_values($task_list);
        }
        break;
}

// Thêm mảng tasks vào session
$_SESSION['tasklist'] = $task_list;
include('task_list.php');
?>