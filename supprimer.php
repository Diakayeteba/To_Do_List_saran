<?php
require_once 'fonction.php';

$id = $_GET['id'] ?? null;

if ($id !== null) {
    $tasks = lireTasks();

    $tasks = array_filter($tasks, function ($task) use ($id) {
        return $task['id'] != $id;
    });

    $tasks = array_values($tasks);
    enregistrerTasks($tasks);
}

header('Location: index.php');
exit;