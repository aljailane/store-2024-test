<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من إرسال معرف المستخدم
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: users.php');
    exit();
}

$user_id = $_GET['id'];

// استعراض اسم المستخدم
require 'web/SourceDb.php';

$db = new SourceDb();
$conn = $db->connect();

$stmt = $conn->prepare('SELECT username FROM Users WHERE user_id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

// عند الضغط على زر "تغيير كلمة المرور"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التحقق من صحة البيانات المرسلة
    if (isset($_POST['new_password']) && !empty(trim($_POST['new_password']))) {
        $new_password = trim($_POST['new_password']);
        
        // قم بتحديث كلمة المرور للعضو في قاعدة البيانات
        $stmt = $conn->prepare('UPDATE Users SET password = :password WHERE user_id = :user_id');
        $stmt->execute(['password' => password_hash($new_password, PASSWORD_DEFAULT), 'user_id' => $user_id]);
        
        $_SESSION['success_message'] = 'Password changed successfully.';
    } else {
        $_SESSION['error_message'] = 'Please enter a new password.';
    }
    // توجيه المستخدم إلى نفس الصفحة بعد عملية التعديل
    header("Location: {$_SERVER['PHP_SELF']}?id=$user_id");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Change Password</h2>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <!-- عرض رسائل الخطأ والنجاح -->
                <?php if (isset($_SESSION['error_message']) && !isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; ?></div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['success_message']) && !isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success_message']; ?></div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                <form action="" method="post">
                    <!-- عرض اسم المستخدم -->
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
                <!-- زر العودة -->
                <div class="text-center mt-3">
                    <a href="Users.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

