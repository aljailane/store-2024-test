<?php
session_start();

// التحقق من تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// التحقق من إرسال معرف المستخدم
if (!isset($_POST['user_id']) || !is_numeric($_POST['user_id'])) {
    header('Location: /users.php');
    exit();
}

$user_id = $_POST['user_id'];

// التحقق مما إذا كان المستخدم هو المسؤول (العضو رقم 1)
if ($_SESSION['user_id'] != 1 && $user_id == 1) {
    // إذا لم يكن المستخدم المسؤول والعضو المطلوب للحذف هو العضو رقم 1، قم بإعادته إلى صفحة الأعضاء
    $_SESSION['error_message'] = 'You are not authorized to delete the administrator.';
    header('Location: /Users.php');
    exit();
}

// الآن قم بحذف العضو من قاعدة البيانات إذا لم يكن العضو هو العضو المشرف
if ($user_id != 1) {
    require '../web/SourceDb.php';

    $db = new SourceDb();
    $conn = $db->connect();
    $stmt = $conn->prepare('DELETE FROM Users WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $user_id]);

    // إعادة توجيه المستخدم إلى صفحة الأعضاء بعد حذف العضو بنجاح
    $_SESSION['success_message'] = 'User deleted successfully.';
}

header('Location: /Users.php');
exit();
?>
