<?php
session_start();
include 'config.php'; // ملف الاتصال بقاعدة البيانات

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = isset($_POST['password']) ? $_POST['password'] : ''; // تصحيح اسم المتغير

    // تأمين البيانات ضد الهجمات
    $email = mysqli_real_escape_string($conn, $email);

    // البحث عن المستخدم في قاعدة البيانات
    $query = "SELECT * FROM Utilisateur WHERE mailUser='$email' LIMIT 1";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // التحقق من كلمة المرور (مشفر أو غير مشفر)
        if (password_verify($password, $user['motPasse']) || $password === $user['motPasse']) {
            $_SESSION['user_id'] = $user['idUser'];
            $_SESSION['user_name'] = $user['nomUser'] . ' ' . $user['prenomUser'];
            $_SESSION['user_email'] = $user['mailUser'];
            header("Location: indix.php"); // إعادة التوجيه إلى لوحة التحكم
            exit();
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Email is not registered";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FARHA</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url(RED.jpg) !important;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(0, 0, 0, 0.5); 
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgb(173, 36, 36);
            text-align: center;
            width: 400px;
        }
        h2 {
            margin-bottom: 20px;
            color: white;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        label {
            color: white;
            display: block;
            text-align: left;
            margin-top: 10px;
        }
        input {
            width: 100%;
            margin-left:-6PX;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color:rgb(84, 14, 14);
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
        }
        button:hover {
            background-color:rgb(35, 6, 6);
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="POST">
            <label for="email">Email :</label>
            <input type="email" name="email" required>
            
            <label for="password">Password :</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
