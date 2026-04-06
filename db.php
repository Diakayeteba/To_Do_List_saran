<?php
define('DB_PATH', __DIR__ . '/todo.db');

function get_db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO('sqlite:' . DB_PATH);
        } catch (PDOException $e) {
            die('Impossible d\'ouvrir la base de données. Vérifiez les permissions du répertoire.');
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
            id        INTEGER PRIMARY KEY AUTOINCREMENT,
            title     TEXT    NOT NULL,
            done      INTEGER NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
        )");
    }
    return $pdo;
}

// CSRF helpers ---------------------------------------------------------

function csrf_token(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_verify(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $token = $_POST['csrf_token'] ?? '';
    if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Requête invalide (token CSRF manquant ou incorrect).');
    }
}

function get_all_tasks(): array {
    return get_db()
        ->query("SELECT * FROM tasks ORDER BY done ASC, created_at DESC")
        ->fetchAll();
}

function get_task(int $id): array|false {
    $stmt = get_db()->prepare("SELECT * FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function add_task(string $title): void {
    $stmt = get_db()->prepare("INSERT INTO tasks (title) VALUES (?)");
    $stmt->execute([trim($title)]);
}

function update_task(int $id, string $title): void {
    $stmt = get_db()->prepare("UPDATE tasks SET title = ? WHERE id = ?");
    $stmt->execute([trim($title), $id]);
}

function toggle_task(int $id): void {
    $stmt = get_db()->prepare("UPDATE tasks SET done = 1 - done WHERE id = ?");
    $stmt->execute([$id]);
}

function delete_task(int $id): void {
    $stmt = get_db()->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
}
