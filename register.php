<?php
include_once 'tools/database.php';
include_once 'tools/tools.php';



if (checkAuth())
    go('products', null, false);


$errors = [];
$data = $_POST;
if (count($data) != 0) {

    if (!isset($data['email'])) {
        $errors[] = 'the email vaild';
    }
    if (isset($data['password']) && strlen($data['password']) < 6) {
        $errors[] = "password 6 and more latters";
    }
    if (empty($data['password'])) {
        $errors[] = 'password is required';
    }


    if (count($errors) == 0) {
        $data['role'] = 'user';
        $mysql->sql_write('users', $data);
        $data['id'] = '999';

        add_session('user',$data);
        go('products', null, false);
    } else {
        foreach ($errors as $value) {
            echo $value . "<br>";
        }
    }
}

?>



<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>

        <h1>إنشاء حساب جديد</h1>
        <nav>
            <a href="index.php">الرئيسية</a>
            <a href="login.php">تسجيل الدخول</a>
        </nav>
    </header>

    <section class="form-container">
        <form method="post">
            <label>الاسم الكامل:</label>
            <input type="text" name="username" required>

            <label>البريد الإلكتروني:</label>
            <input type="email" name="email" required>

            <label>كلمة المرور:</label>
            <input type="password" name="password" required>

            <button type="submit">تسجيل</button>
        </form>
    </section>
</body>

</html>