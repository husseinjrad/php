<?php
include_once 'tools/database.php';
include_once 'tools/tools.php';

if (!checkAuth()) {
    go('login', null, false);
}

if (isset($_POST['method'])) {

    if ($_POST['methon'] == 'create_user') {
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
                $data['role'] = 'user';
                $mysql->sql_write('users', $data);
                $data['id'] = '999';

                add_session('user', $data);
                go('products', null, false);
            } else {
                foreach ($errors as $value) {
                    echo $value . "<br>";
                }
            }
        }
        if (count($errors) != 0) {
            foreach ($errors as $value) {
                echo $value . "<br>";
            }
        }

    }

    if ($_POST['method'] == 'delete_user') {
        $userId = $_POST['user_id'];
        $mysql->sql_del('users', 'id', $userId);
        go('users', null, false);
    }

    if ($_POST['method'] == 'edit_user') {
        $userId = $_POST['user_id'];
        $updatedData = [
            'username' => $_POST['username'],
            'email' => $_POST['email'],
            'password' => $_POST['password']
        ];
        $mysql->sql_edit('users', 'id', $userId, $updatedData);
        go('users', null, false);
    }

    if ($_POST['method'] == 'create_product') {
        $data = $_POST;
        if (!isset($data['product_name']) || empty($data['product_name'])) {
            $errors[] = 'اسم المنتج مطلوب.';
        }
        if (!isset($data['price']) || !is_numeric($data['price'])) {
            $errors[] = 'السعر يجب أن يكون رقمًا.';
        }

        if (empty($errors)) {
            $mysql->sql_write('products', [
                'product_name' => $data['product_name'],
                'price' => $data['price'],
                'description' => $data['description'] ?? ''
            ]);
            go('products', null, false);
        } else {
            foreach ($errors as $error) {
                echo $error . "<br>";
            }
        }
    }

    if ($_POST['method'] == 'delete_product') {
        $productId = $_POST['product_id'];
        $mysql->sql_del('products', 'id', $productId);
        go('products', null, false);
    }
}
$products = $mysql->sql_readarray('products');
?>

<!DOCTYPE html>
<html lang="ar">

<head>
    <meta charset="UTF-8">
    <title>قائمة المستخدمين</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            direction: rtl;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table,
        th,
        td {
            border: 1px solid #ccc;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        h1 {
            text-align: center;
        }
    </style>
</head>

<body>


    <h1>قائمة المنتجات</h1>
    <!-- زر فتح النافذة المنبثقة -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createProductModal">
        إضافة منتج جديد
    </button>

    <!-- النافذة المنبثقة -->
    <div class="modal fade" id="createProductModal" tabindex="-1" aria-labelledby="createProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createProductModalLabel">إضافة منتج جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="method" value="create_product">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">اسم المنتج</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">السعر</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">الوصف</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">إضافة</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- جدول عرض المنتجات -->
    <h2>قائمة المنتجات</h2>
    <table>
        <thead>
            <tr>
                <th>رقم التعريف</th>
                <th>اسم المنتج</th>
                <th>السعر</th>
                <th>الوصف</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($products)) {
                foreach ($products as $product) { ?>
                    <tr>
                        <td><?= $product['id'] ?></td>
                        <td><?= $product['product_name'] ?></td>
                        <td><?= $product['price'] ?></td>
                        <td><?= $product['description'] ?></td>
                        <td>
                            <!-- زر حذف -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="method" value="delete_product">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php }
            } else {
                echo '<tr><td colspan="5">لا توجد نتائج</td></tr>';
            } ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>