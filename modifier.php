<?php
require_once 'fonction.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $titre = $_POST['titre'] ?? '';
    $titre = nettoyerTexte($titre);

    if ($id !== null && !empty($titre)) {
        $tasks = lireTasks();

        foreach ($tasks as &$task) {
            if ($task['id'] == $id) {
                $task['titre'] = $titre;
                break;
            }
        }

        enregistrerTasks($tasks);
    }
}

header('Location: index.php');
exit;