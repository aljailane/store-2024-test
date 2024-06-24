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

// التحقق من وجود معرف العضو في الرابط
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    header('Location: users.php');
    exit();
}

$user_id = $_GET['user_id'];

// الحصول على بيانات العضو المحدد من قاعدة البيانات
$db = new SourceDb();
$conn = $db->connect();
$stmt = $conn->prepare('SELECT username, full_name, pincode, ip, status, date FROM Users WHERE user_id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

// التحقق مما إذا كان العضو المحدد موجودًا في قاعدة البيانات
if (!$user) {
    header('Location: users.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">View User</h2>
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>User:</th>
                            <td><?php echo $user['username']; ?></td>
                        </tr>
                        <tr>
                            <th>Name:</th>
                            <td><?php echo $user['full_name']; ?></td>
                        </tr>
                        <tr>
                            <th>Pin:</th>
                            <td><?php echo $user['pincode']; ?></td>
                        </tr>
                        <tr id="ipRow" style="display: none;">
                            <th>IP:</th>
                            <td><?php echo $user['ip']; ?></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td><?php echo getStatusText($user['status']); ?></td>
                        </tr>
                        <tr>
                            <th>Reg:</th>
                            <td><?php echo $user['date']; ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="text-center">
                    <button id="toggleIpButton" class="btn btn-primary" onclick="toggleIp()">Show IP</button>
                    <a href="edit_user.php?user_id=<?php echo $user_id; ?>" class="btn btn-primary">Edit</a>
                    <a href="delete_user.php?user_id=<?php echo $user_id; ?>" class="btn btn-danger">Delete</a>
                    <a href="Users.php" class="btn btn-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleIp() {
            var ipRow = document.getElementById('ipRow');
            var toggleIpButton = document.getElementById('toggleIpButton');

            if (ipRow.style.display === 'none') {
                ipRow.style.display = '';
                toggleIpButton.textContent = 'Hide IP';
            } else {
                ipRow.style.display = 'none';
                toggleIpButton.textContent = 'Show IP';
            }
        }
    </script>
</body>
</html>

<?php
// تحويل قيمة الحالة إلى نص قابل للقراءة
function getStatusText($status) {
    switch ($status) {
        case 0:
            return 'Active';
        case 1:
            return 'Inactive';
        case 2:
            return 'Banned';
        case 3:
            return 'Pending';
        default:
            return 'Unknown';
    }
}
?>

