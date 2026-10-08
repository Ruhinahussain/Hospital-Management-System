<?php
session_start();
require_once 'db.php';

if (empty($_SESSION['login_csrf'])) {
    $_SESSION['login_csrf'] = bin2hex(random_bytes(32));
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['csrf_token'] ?? '';

    if (
        !is_string($token) ||
        !hash_equals($_SESSION['login_csrf'], $token)
    ) {
        $error = 'Invalid request. Please refresh the page and try again.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!is_string($username) || !is_string($password)) {
            $error = 'Invalid username or password.';
        } else {
            $stmt = $conn->prepare(
                'SELECT id, username, password_hash
                 FROM admin WHERE username = ? LIMIT 1'
            );

            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->bind_result($adminId, $adminUsername, $passwordHash);

            if (
                $stmt->fetch() &&
                password_verify($password, $passwordHash)
            ) {
                session_regenerate_id(true);

                $_SESSION['admin_id'] = $adminId;
                $_SESSION['admin_username'] = $adminUsername;

                unset($_SESSION['login_csrf']);

                $stmt->close();

                header('Location: dashboard.php');
                exit;
            }

            $stmt->close();
            $error = 'Incorrect username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login - Hospital Management</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background: #f0f4f8;
            font-family: Arial, sans-serif;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #087f8c;
            font-size: 25px;
        }

        p.subtitle {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-top: 16px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #087f8c;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #066873;
        }

        .error {
            color: #b42318;
            background: #fff0ef;
            padding: 10px;
            border-radius: 6px;
        }
    </style>
</head>

<body>

    <main class="login-box">

        <h1>Hospital Admin Login</h1>

        <p class="subtitle">
            Sign in to manage the hospital system
        </p>

        <?php if ($error !== ''): ?>

            <p class="error">
                <?= htmlspecialchars(
                    $error,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>
            </p>

        <?php endif; ?>

        <form method="POST" action="login.php">

            <input
                type="hidden"
                name="csrf_token"
                value="<?= htmlspecialchars(
                    $_SESSION['login_csrf'],
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>"
            >

            <label for="username">Username</label>

            <input
                type="text"
                id="username"
                name="username"
                maxlength="50"
                autocomplete="username"
                required
            >

            <label for="password">Password</label>

            <input
                type="password"
                id="password"
                name="password"
                autocomplete="current-password"
                required
            >

            <button type="submit">
                Login
            </button>

        </form>

    </main>

</body>
</html>