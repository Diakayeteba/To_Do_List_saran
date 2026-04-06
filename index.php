<?php
require_once 'db.php';
csrf_token(); // start session and initialise token

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    csrf_verify();
    $title = trim($_POST['title'] ?? '');
    if ($title === '') {
        $error = 'Le titre ne peut pas être vide.';
    } else {
        add_task($title);
        header('Location: index.php');
        exit;
    }
}

$tasks  = get_all_tasks();
$total  = count($tasks);
$done   = count(array_filter($tasks, fn($t) => $t['done']));
$pending = $total - $done;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List – Saran Keita</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <header>
        <h1>📝 To-Do List</h1>
        <p class="subtitle">Saran Keita ka projet PHP natif</p>
        <div class="stats">
            <span class="stat total">🗂 Total : <?= $total ?></span>
            <span class="stat pending">⏳ En cours : <?= $pending ?></span>
            <span class="stat done">✅ Terminées : <?= $done ?></span>
        </div>
    </header>

    <!-- Add task form -->
    <form method="POST" action="index.php" class="add-form">
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input
            type="text"
            name="title"
            placeholder="Nouvelle tâche…"
            maxlength="255"
            required
            autofocus
        >
        <button type="submit">Ajouter</button>
    </form>

    <!-- Task list -->
    <?php if (empty($tasks)): ?>
        <p class="empty">Aucune tâche pour l'instant. Ajoutez-en une ! 🎉</p>
    <?php else: ?>
        <ul class="task-list">
            <?php foreach ($tasks as $task): ?>
                <li class="task-item <?= $task['done'] ? 'done' : '' ?>">
                    <span class="task-title"><?= htmlspecialchars($task['title']) ?></span>
                    <div class="actions">
                        <!-- Toggle done -->
                        <form method="POST" action="toggle.php">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                            <button type="submit" class="btn-icon" title="<?= $task['done'] ? 'Marquer comme non terminée' : 'Marquer comme terminée' ?>">
                                <?= $task['done'] ? '↩️' : '✅' ?>
                            </button>
                        </form>
                        <!-- Edit -->
                        <a href="edit.php?id=<?= $task['id'] ?>" class="btn-icon" title="Modifier">✏️</a>
                        <!-- Delete -->
                        <form method="POST" action="delete.php"
                              onsubmit="return confirm('Supprimer cette tâche ?');">
                            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                            <input type="hidden" name="id" value="<?= $task['id'] ?>">
                            <button type="submit" class="btn-icon" title="Supprimer">🗑️</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>
</body>
</html>
