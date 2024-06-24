<?php
require 'web/SourceDb.php';

session_start();

// فحص الجلسة
if (isset($_SESSION['user_id'])) {
    header('Location: User.php');
    exit();
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        $db = new SourceDb();
        $conn = $db->connect();

        if ($conn) {
            $stmt = $conn->prepare('SELECT * FROM Users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                // فحص العنوان IP
                $user_ip = getUserIP();
                // يمكنك هنا إجراء ما تحتاجه مع عنوان IP المستخدم
                
                $success = 'تم تسجيل الدخول بنجاح! يتم توجيهك إلى صفحة المستخدم...';
                $_SESSION['login_success'] = $success;
                header("Location: pin.php"); // توجيه المستخدم إلى صفحة المستخدم بعد تسجيل الدخول بنجاح
                exit;
            } else {
                $errors[] = 'Invalid username or password.';
            }
        } else {
            $errors[] = 'Database connection failed.';
        }
    }
}

// فحص العنوان IP
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // IP من مشاركة إنترنت
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // IP عبر وكيل
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // IP من عنوان IP عن بعد
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
</head>
<body>
    <section class="section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-half">
                    <div class="box mt-6">
                        <h2 class="title has-text-centered">Login</h2>
<?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
                        <?php if (!empty($errors)): ?>
                            <div class="notification is-danger">
                                <ul>
                                    <?php foreach ($errors as $error): ?>
                                        <li><?php echo $error; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form action="login.php" method="post">
                            <div class="field">
                                <label class="label">Email</label>
                                <div class="control">
                                    <input class="input" type="username" id="username" name="username" placeholder="User Name" required>
                                </div>
                            </div>

                            <div class="field">
                                <label class="label">Password</label>
                                <div class="control">
                                    <input class="input" type="password" id="password" name="password" placeholder="********" required>
                                </div>
                            </div>

                            <button class="button is-primary is-fullwidth" type="submit">Sign in</button>
                        </form>
                        <br>
                        <p class="has-text-centered">النظام يضمن عدم تسريب أي معلومات خاصة بكم</p>
                        <p class="has-text-centered">ليس لديك حساب؟ <a href="register.php">سجل الآن</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

