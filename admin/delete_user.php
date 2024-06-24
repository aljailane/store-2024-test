<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من أنه تم تمرير معرف المستخدم عبر الرابط
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header('Location: users.php');
    exit();
}

$user_id = $_GET['user_id'];

// يمكنك إضافة المزيد من الاختبارات هنا، مثل التحقق من صلاحيات المستخدم وما إلى ذلك

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Delete User</h2>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="alert alert-danger" role="alert">
                    Are you sure you want to delete this user?
                </div>
                <form action="process/d_u_process.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <a href="users.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
