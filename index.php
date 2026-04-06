<?php
require_once 'fonction.php';

# On lit toutes les tâches depuis le fichier JSON
$tasks = lireTasks();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma To-Do List</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Ma To-Do List</h1>

        <!-- Formulaire d'ajout -->
        <form action="ajouter.php" method="POST" class="form-ajout">
            <input type="text" name="titre" placeholder="Entrer une nouvelle tâche..." required>
            <button type="submit">Ajouter</button>
        </form>

        <h2>Liste des tâches</h2>

        <?php if (empty($tasks)): ?>
            <p class="vide">Aucune tâche pour le moment.</p>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <div class="task-item">
                    <span><?php echo $task['titre']; ?></span>

                    <div class="actions">
                        <!-- Formulaire de modification -->
                        <form action="modifier.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $task['id']; ?>">
                            <input type="text" name="titre" value="<?php echo $task['titre']; ?>" required>
                            <button type="submit">Modifier</button>
                        </form>

                        <!-- Lien de suppression -->
                        <a href="supprimer.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Voulez-vous supprimer cette tâche ?')">
                            Supprimer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>