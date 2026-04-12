<?php
// api/auth.php
// Handles login/logout for AssetTrack

session_start();
header('Content-Type: application/json');

// Demo credentials (in production: validate against DB with password_verify)
define('DEMO_USERNAME', 'admin');
define('DEMO_PASSWORD', 'admin123');
define('DEMO_EMAIL', 'admin@company.com');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    case 'login':
        $username = trim($_POST['username'] ?? '');
        $password  = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            respond(false, 'Username and password are required.');
        }

        // Demo auth check
        if (
            ($username === DEMO_USERNAME || $username === DEMO_EMAIL) &&
            $password === DEMO_PASSWORD
        ) {
            $_SESSION['user_id']   = 1;
            $_SESSION['username']  = DEMO_USERNAME;
            $_SESSION['role']      = 'Administrator';
            $_SESSION['logged_in'] = true;

            respond(true, 'Login successful.', ['redirect' => '../pages/dashboard.php?role=user']);
        }

        respond(false, 'Invalid username or password. Please try again.');
        break;

    case 'logout':
        session_destroy();
        respond(true, 'Logged out.', ['redirect' => '../index.php']);
        break;

    case 'check':
        if (!empty($_SESSION['logged_in'])) {
            respond(true, 'Authenticated.', [
                'username' => $_SESSION['username'],
                'role'     => $_SESSION['role'],
            ]);
        }
        respond(false, 'Not authenticated.');
        break;

    default:
        respond(false, 'Invalid action.');
}

function respond(bool $success, string $message, array $data = []): void {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message,
    ], $data));
    exit;
}
