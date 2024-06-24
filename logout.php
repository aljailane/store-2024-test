<?php
session_start();

// إنهاء الجلسة
session_unset();
session_destroy();

// إعادة توجيه المستخدم إلى صفحة تسجيل الدخول
header('Location: login.php');
exit();
?>
