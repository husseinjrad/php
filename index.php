<?php
include_once 'tools/database.php';
include_once 'tools/tools.php';

$data = $mysql->sql_readarray('products');


?>



<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>متجري البسيط</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>متجري البسيط</h1>
        <nav>
            <a href="index.html">الرئيسية</a>
            <a href="login.html">تسجيل الدخول</a>
            <a href="register.html">إنشاء حساب</a>
        </nav>
    </header>

    <section class="products">
        <h2>المنتجات</h2>
        <?php 
       
        if(!empty($data)){
        foreach($data as $v){ ?>
        <div class="product">
            <h3><?= $v['product_name'] ?></h3>
            <p class="price">السعر: <?= $v['price'] ?>$</p>
            <img src="./images/klm.jpg">
            <p><?= $v['description'] ?></p>
            
        </div>
        <?php
        }}else{
            echo 'resuilt not found';
        }
        ?>
        <!-- <div class="product">
            <h3>دفتر مذكرات</h3>
            <p class="price">السعر: 15$</p>
            <img src="./images/dft.jpg">
            <p>دفتر بغلاف صلب مثالي لتدوين الأفكار.</p>
        </div>
        <div class="product">
            <h3>حقيبة</h3>
            <p class="price">السعر: 30$</p>
            <img src="./images/hkp.jpg">
            <p>حقيبة مخصصة لحمل اللابتوب</p>
        </div> -->
    </section>
</body>
</html>