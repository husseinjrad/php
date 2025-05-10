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
    if (!isset($data['password']) || $data['password'] != '') {
        $errors[] = 'password is vaild';
    }


    if (count($errors) == 0) {
        $value = $mysql->sql_where('users', 'email', $data['eamil']);
        if ($value) {

            if ($data['password'] = $value['password']) {

                add_session('user', $data);
                go('products', null, false);
            } else {
                $errors[] = 'vaild th password';
            }
        } else {
            $errors[] = 'email not found';
        }
    }
    if (count($errors) != 0) {
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
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>تسجيل الدخول</h1>
        <nav>
            <a href="index.html">الرئيسية</a>
            <a href="register.html">إنشاء حساب</a>
        </nav>
    </header>

    <section class="form-container">
        <form method="post">
            <label>البريد الإلكتروني:</label>
            <input type="email" required>

            <label>كلمة المرور:</label>
            <input type="password" required>

            <button type="submit">دخول</button>
        </form>
    </section>
</body>

</html>