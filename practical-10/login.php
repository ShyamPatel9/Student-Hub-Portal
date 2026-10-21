```php
<?php
session_start();

if (isset($_SESSION['user_id'], $_SESSION['role'])) {
    header('Location: dashboard.php');
    exit;
}

require_once __DIR__ . '/../StudentHub/db.php';

$error = '';
$message = '';

if (
    isset($_GET['message']) &&
    $_GET['message'] === 'timeout'
) {
    $message = 'You were logged out because of 15 minutes of inactivity.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim($_POST['username'] ?? '');
    $password = $_POST['pass'] ?? '';
    $role = $_POST['role'] ?? 'student';

    if (
        $identity === '' ||
        $password === '' ||
        !in_array($role, ['student', 'admin'], true)
    ) {
        $error = 'Enter your login details and select a valid role.';
    } else {
        if ($role === 'admin') {
            $sql = "SELECT admin_id AS id, username, password
                    FROM admins
                    WHERE username = ?
                    LIMIT 1";
        } else {
            $sql = "SELECT student_id AS id, email AS username, password
                    FROM students
                    WHERE email = ?
                    LIMIT 1";
        }

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            error_log('Login SQL preparation failed: ' . mysqli_error($conn));
            $error = 'Login is temporarily unavailable. Please check the database configuration.';
        } elseif (!mysqli_stmt_bind_param($stmt, 's', $identity)) {
            error_log('Login parameter binding failed: ' . mysqli_stmt_error($stmt));
            $error = 'Login is temporarily unavailable.';
            mysqli_stmt_close($stmt);
        } elseif (!mysqli_stmt_execute($stmt)) {
            error_log('Login execution failed: ' . mysqli_stmt_error($stmt));
            $error = 'Login is temporarily unavailable.';
            mysqli_stmt_close($stmt);
        } else {
            $result = mysqli_stmt_get_result($stmt);

            if ($result === false) {
                error_log('Login result failed: ' . mysqli_stmt_error($stmt));
                $error = 'Login is temporarily unavailable.';
            } else {
                $account = mysqli_fetch_assoc($result);

                if (
                    $account &&
                    password_verify($password, $account['password'])
                ) {
                    session_regenerate_id(true);

                    $_SESSION['user_id'] = (int) $account['id'];
                    $_SESSION['username'] = $account['username'];
                    $_SESSION['role'] = $role;
                    $_SESSION['last_activity'] = time();

                    mysqli_stmt_close($stmt);

                    if ($role === 'admin') {
                        header('Location: admin_dashboard.php');
                    } else {
                        header('Location: dashboard.php');
                    }

                    exit;
                }

                $error = 'Invalid username/email, password, or selected role.';
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>StudentHub Login</title>
</head>
<body>

<header>
    <h1>Student Hub Portal</h1>
    <hr>
</header>

<?php if ($message !== ''): ?>
    <p class="label" role="status">
        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<?php if ($error !== ''): ?>
    <p class="label" role="alert">
        <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php endif; ?>

<form method="post" action="login.php">
    <label class="label" for="username">
        Email (student) or username (admin)
    </label>
    <br>
    <input
        class="button"
        type="text"
        id="username"
        name="username"
        required
        autocomplete="username"
        value="<?= htmlspecialchars($identity ?? '', ENT_QUOTES, 'UTF-8') ?>"
    >
    <br><br>

    <label class="label" for="pass">Password</label>
    <br>
    <input
        class="button"
        type="password"
        id="pass"
        name="pass"
        required
        autocomplete="current-password"
    >
    <br><br>

    <label class="label" for="role">Login as</label>
    <br>
    <select class="button" id="role" name="role" required>
        <option value="student"
            <?= (($role ?? 'student') === 'student') ? 'selected' : '' ?>>
            Student
        </option>
        <option value="admin"
            <?= (($role ?? '') === 'admin') ? 'selected' : '' ?>>
            Admin
        </option>
    </select>
    <br><br>

    <button class="button" type="submit">Login</button>
</form>

<p class="label">
    Student: use the email and password used during registration.
</p>

<p class="label">
    Demo admin: <b>admin</b> / <b>Admin@123</b>
</p>

<p>
    <a class="button" href="../index.html">Back</a>
</p>

<footer>
    <h2>@2026 Student portal</h2>
</footer>

</body>
</html>
```