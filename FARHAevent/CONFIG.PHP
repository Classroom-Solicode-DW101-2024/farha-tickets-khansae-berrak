<?php
$servername = "localhost"; // اسم الخادم
$username = "root"; // اسم المستخدم لقاعدة البيانات
$password = ""; // كلمة المرور (اتركها فارغة إذا كنتِ تستخدمين XAMPP)
$dbname = "farhaevents"; // اسم قاعدة البيانات

// إنشاء الاتصال
$conn = mysqli_connect($servername, $username, $password, $dbname);

// التحقق من الاتصال
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
}
?>
