<?php
require_once 'db.php';
csrf_token(); // start session and initialise token

$id = (int)($_GET['id'] ?? 0);
$task = $id ? get_task($id) : null;
if (!$task) {
    header('Location: index.php');
    exit;
}

$error = '';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    csrf_verify();
    $title = trim($_POST['title'] ?? '');
    if ($title === '') {
        $error = 'Le titre ne peut pas être vide.';
    } else {
        update_task($id, $title);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la tâche – To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">

    <header>
        <h1>✏️ Modifier la tâche</h1>
    </header>

    <form method="POST" action="edit.php?id=<?= $id ?>" class="add-form">
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input
            type="text"
            name="title"
            value="<?= htmlspecialchars($task['title']) ?>"
            maxlength="255"
            required
            autofocus
        >
        <button type="submit">Enregistrer</button>
        <a href="index.php" class="btn-cancel">Annuler</a>
    </form>

</div>
</body>
</html>
