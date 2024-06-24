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
$stmt = $conn->prepare('SELECT username, full_name, pincode, status FROM Users WHERE user_id = :user_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

// التحقق مما إذا كان العضو المحدد موجودًا في قاعدة البيانات
if (!$user) {
    header('Location: users.php');
    exit();
}

// معالجة طلب تحديث بيانات العضو
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $status = $_POST['status'];
    $pincode = isset($_POST['generate_pincode']) ? generatePincode() : $user['pincode'];

    // تحديث بيانات العضو في قاعدة البيانات
    $stmt = $conn->prepare('UPDATE Users SET full_name = :full_name, pincode = :pincode, status = :status WHERE user_id = :user_id');
    if ($stmt->execute(['full_name' => $full_name, 'pincode' => $pincode, 'status' => $status, 'user_id' => $user_id])) {
        if (isset($_POST['generate_pincode'])) {
            $_SESSION['success_message'] = 'New pincode generated successfully.';
            // إعادة تحميل الصفحة بعد تحديث البيانات
            header("Refresh:0");
            exit();
        } else {
            $_SESSION['success_message'] = 'User updated successfully.';
        }
    } else {
        $_SESSION['error_message'] = 'Failed to update user. Please try again.';
    }
}

// توليد قيمة جديدة لل pincode
function generatePincode() {
    return rand(100000, 999999);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Edit User</h2>
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message']; ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <form action="" method="post" id="userForm">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" value="<?php echo $user['username']; ?>" readonly>
            </div>
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>" required onchange="updateFullName()">
            </div>
            <div class="form-group">
                <label for="pincode">Pincode</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="pincode" name="pincode" value="<?php echo $user['pincode']; ?>" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="submit" name="generate_pincode" onclick="updatePincode()">Generate New</button>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="0" <?php echo ($user['status'] == 0) ? 'selected' : ''; ?>>Active</option>
                    <option value="1" <?php echo ($user['status'] == 1) ? 'selected' : ''; ?>>Inactive</option>
                    <option value="2" <?php echo ($user['status'] == 2) ? 'selected' : ''; ?>>Banned</option>
                    <option value="3" <?php echo ($user['status'] == 3) ? 'selected' : ''; ?>>Pending</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="Users.php" class="btn btn-secondary">Back</a>
        </form>
    </div>

    <script>
        function updateFullName() {
            var fullNameInput = document.getElementById('full_name');
            var fullNameValue = fullNameInput.value;
            var fullNameDisplay = document.getElementById('full_name_display');
            fullNameDisplay.textContent = fullNameValue;
        }

        function updatePincode() {
            var pincodeInput = document.getElementById('pincode');
            var pincodeValue = Math.floor(100000 + Math.random() * 900000);
            pincodeInput.value = pincodeValue;
        }
    </script>
</body>
</html>

