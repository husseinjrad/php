<?php

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
    <?php if(true){?>
      <p>hello</p>
           <?php } ?>
        <h1>إنشاء حساب جديد</h1>
        <nav>
            <a href="index.html">الرئيسية</a>
            <a href="login.html">تسجيل الدخول</a>
        </nav>
    </header>

    <section class="form-container">
        <form>
            <label>الاسم الكامل:</label>
            <input type="text" required>

            <label>البريد الإلكتروني:</label>
            <input type="email" required>

            <label>كلمة المرور:</label>
            <input type="password" required>

            <button type="submit">تسجيل</button>
        </form>
    </section>
</body>
</html>