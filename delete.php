<?php
require_once 'db.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    csrf_verify();
    $id = (int)($_POST['id'] ?? 0);
    if ($id > 0) {
        delete_task($id);
    }
}

header('Location: index.php');
exit;
