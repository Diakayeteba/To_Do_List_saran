<?php
require_once 'fonction.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'] ?? '';
    $titre = nettoyerTexte($titre);

    if (!empty($titre)) {
        $tasks = lireTasks();

        $tasks[] = [
            'id' => genererId(),
            'titre' => $titre
        ];

        enregistrerTasks($tasks);
    }
}

header('Location: index.php');
exit;