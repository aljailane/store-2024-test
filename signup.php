<?php
require 'web/SourceDb.php';

$errors = [];
$success = '';

// دالة لتوليد Pincode عشوائي
function generatePincode($length = 6) {
    return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)))), 1, $length);
}

$pincode = generatePincode();

// الحصول على عنوان IP بما يتوافق مع IPv4 و IPv6
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

$ip = getUserIP();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $full_name = trim($_POST['full_name']);

    if (empty($username)) {
        $errors[] = 'Username is required.';
    }
    if (empty($password)) {
        $errors[] = 'Password is required.';
    }
    if (empty($full_name)) {
        $errors[] = 'Full name is required.';
    }

    if (empty($errors)) {
        $db = new SourceDb();
        $conn = $db->connect();

        if ($conn) {
            $stmt = $conn->prepare('SELECT COUNT(*) FROM Users WHERE username = :username');
            $stmt->execute(['username' => $username]);
            if ($stmt->fetchColumn() > 0) {
                $errors[] = 'Username is already taken.';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO Users (username, password, pincode, full_name, ip, status) VALUES (:username, :password, :pincode, :full_name, :ip, 0)');
                if ($stmt->execute([
                    'username' => $username,
                    'password' => $hashed_password,
                    'pincode' => $pincode,
                    'full_name' => $full_name,
                    'ip' => $ip
                ])) {
                    $success = 'Registration successful! You can now log in.';
                } else {
                    $errors[] = 'Error during registration. Please try again.';
                }
            }
        } else {
            $errors[] = 'Database connection failed.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Rubik', sans-serif;
        }
    </style>
</head>
<body dir="rtl">
    <section class="section">
        <div class="container">
            <h2 class="title has-text-centered">Sign Up</h2>
            <?php if ($success): ?>
                <div class="notification is-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if ($errors): ?>
                <div class="notification is-danger">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="signup.php" method="post">
                <div class="field">
                    <label class="label" for="username">اسم المستخدم</label>
                    <div class="control">
                        <input type="text" class="input" id="username" name="username" required>
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="password">كلمة المرور</label>
                    <div class="control has-icons-right">
                        <input type="password" class="input" id="password" name="password" required>
                        <span class="icon is-small is-right">
                            <i id="showPassword" class="fas fa-eye" onclick="togglePassword()"></i>
                        </span>
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="pincode">الرمز السري</label>
                    <div class="control">
                        <input type="text" class="input" id="pincode" name="pincode" value="<?php echo $pincode; ?>" readonly>
                    </div>
                </div>
                <div class="field">
                    <label class="label" for="full_name">الاسم الكامل</label>
                    <div class="control">
                        <input type="text" class="input" id="full_name" name="full_name" required>
                    </div>
                </div>
                <div class="field">
                    <div class="control">
                        <button type="submit" class="button is-primary is-fullwidth">تسجيل</button>
                    </div>
                </div>
            </form>
            <p class="has-text-centered">لديك حساب بالفعل؟ <a href="login.php">تسجيل الدخول</a></p>
        </div>
    </section>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var showPasswordIcon = document.getElementById("showPassword");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                showPasswordIcon.classList.remove("fa-eye");
                showPasswordIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                showPasswordIcon.classList.remove("fa-eye-slash");
                showPasswordIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>

