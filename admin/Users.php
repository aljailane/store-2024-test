<?php
require $_SERVER['DOCUMENT_ROOT'] . '/web/SourceDb.php';

session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// البيانات الثابتة للمستخدم (يمكن استبدالها بالبيانات الحقيقية المسترجعة من قاعدة البيانات)
$user_id = $_SESSION['user_id'];

// التحقق مما إذا كان المستخدم هو المسؤول (العضو رقم 1)
$is_admin = ($user_id == 1);

// إذا لم يكن المستخدم مسؤولاً، قم بإعادته إلى صفحة التسجيل
if (!$is_admin) {
    header('Location: login.php');
    exit();
}

// الحصول على جميع الأعضاء من قاعدة البيانات
$db = new SourceDb();
$conn = $db->connect();
$stmt = $conn->prepare('SELECT user_id, username, full_name, status FROM Users');
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Users</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Username</th>
                        <th>Full Name</th>
                        <th>Status</th>
                        <th>Actions</th> <!-- إضافة عمود للإجراءات -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['user_id']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['full_name']; ?></td>
                            <td>
                                <?php
                                switch ($user['status']) {
                                    case 0:
                                        echo 'Active';
                                        break;
                                    case 1:
                                        echo 'Inactive';
                                        break;
                                    case 2:
                                        echo 'Banned';
                                        break;
                                    case 3:
                                        echo 'Pending';
                                        break;
                                    default:
                                        echo 'Unknown';
                                }
                                ?>
                            </td>
                            <td>
                                <a href="view_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-info btn-sm">View</a>
                                <a href="edit_user.php?user_id=<?php echo $user['user_id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                <a href="cpass.php?id=<?php echo $user_id; ?>">Change Password</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

