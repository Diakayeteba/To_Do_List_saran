<?php
define('TASKS_FILE', 'tasks.json');

function lireTasks()
{
    if (!file_exists(TASKS_FILE)) {
        file_put_contents(TASKS_FILE, json_encode([]));
    }

    $contenu = file_get_contents(TASKS_FILE);
    $tasks = json_decode($contenu, true);

    if (!is_array($tasks)) {
        $tasks = [];
    }

    return $tasks;
}

function enregistrerTasks($tasks)
{
    file_put_contents(
        TASKS_FILE,
        json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

function genererId()
{
    return time() . rand(100, 999);
}

function nettoyerTexte($texte)
{
    $texte = trim($texte);
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}