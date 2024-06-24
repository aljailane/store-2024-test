<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// عند الضغط على زر "تغيير كلمة المرور"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // التحقق من تطابق كلمة المرور الجديدة وإعادة كلمة المرور الجديدة
    if ($_POST['new_password'] !== $_POST['confirm_password']) {
        $_SESSION['error_message'] = 'Passwords do not match.';
    } else {
        // التحقق من صحة البيانات المرسلة
        if (!empty(trim($_POST['current_password'])) && !empty(trim($_POST['new_password'])) && !empty(trim($_POST['confirm_password']))) {
            // استعراض بيانات المستخدم
            require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';
            
            $db = new SourceDb();
            $conn = $db->connect();
            
            $user_id = $_SESSION['user_id'];
            
            // الاستعلام عن كلمة المرور الحالية للمستخدم
            $stmt = $conn->prepare('SELECT password FROM Users WHERE user_id = :user_id');
            $stmt->execute(['user_id' => $user_id]);
            $user = $stmt->fetch();
            
            // التحقق من تطابق كلمة المرور الحالية مع كلمة المرور المدخلة
            if (password_verify($_POST['current_password'], $user['password'])) {
                // تحديث كلمة المرور الجديدة في قاعدة البيانات
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $stmt = $conn->prepare('UPDATE Users SET password = :password WHERE user_id = :user_id');
                $stmt->execute(['password' => $new_password, 'user_id' => $user_id]);
                
                $_SESSION['success_message'] = 'Password changed successfully.';
            } else {
                $_SESSION['error_message'] = 'Incorrect current password.';
            }
        } else {
            $_SESSION['error_message'] = 'Please fill out all fields.';
        }
    }
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
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?php echo $_SESSION['error_message']; ?></div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success"><?php echo $_SESSION['success_message']; ?></div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                <form action="" method="post">
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </form>
                <!-- زر العودة -->
                <div class="text-center mt-3">
                    <a href="User.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
