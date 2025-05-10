<?php
include_once 'tools/database.php';
include_once 'tools/tools.php';

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

if (!checkAuth()) {
    go('login', null, false);
}
$auth = get_session('user');
$user = $mysql->sql_where('users', 'id', $auth['id']);
if ($user['role'] != 'admin') {
    go('products', null, false);
}
$users = $mysql->sql_readarray('users');


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

    <h1>قائمة المستخدمين</h1>
    <!-- زر فتح النافذة المنبثقة -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
        إنشاء مستخدم جديد
    </button>

    <!-- النافذة المنبثقة -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">إنشاء مستخدم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createUserForm">
                        <input type="hidden" name="method" value="create_user">
                        <div class="mb-3">
                            <label for="username" class="form-label">اسم المستخدم</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-success">إنشاء</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- جدول عرض المستخدمين -->

    </table>
    <h2>قائمة المستخدمين</h2>
    <table>
        <thead>
            <tr>
                <th>رقم التعريف</th>
                <th>اسم المستخدم</th>
                <th>البريد الإلكتروني</th>
                <th>كلمة المرور</th>
                <th>أضافة مستخدم</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (!empty($users)) {
                foreach ($users as $user) { ?>
                    <tr>
                        <td><?= $user['id'] ?></td>
                        <td><?= $user['username'] ?></td>
                        <td><?= $user['email'] ?></td>
                        <td>••••••••</td>
                        <td>
                            <!-- زر تعديل -->
                            <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editUserModal<?= $user['id'] ?>">
                                تعديل
                            </button>
                            <!-- زر حذف -->
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="method" value="delete_user">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm">حذف</button>
                            </form>
                        </td>
                    </tr>

                    <!-- نافذة تعديل المستخدم -->
                    <div class="modal fade" id="editUserModal<?= $user['id'] ?>" tabindex="-1"
                        aria-labelledby="editUserModalLabel<?= $user['id'] ?>" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editUserModalLabel<?= $user['id'] ?>">تعديل المستخدم</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST">
                                        <input type="hidden" name="method" value="edit_user">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <div class="mb-3">
                                            <label for="username<?= $user['id'] ?>" class="form-label">اسم المستخدم</label>
                                            <input type="text" class="form-control" id="username<?= $user['id'] ?>"
                                                name="username" value="<?= $user['username'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email<?= $user['id'] ?>" class="form-label">البريد الإلكتروني</label>
                                            <input type="email" class="form-control" id="email<?= $user['id'] ?>" name="email"
                                                value="<?= $user['email'] ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="password<?= $user['id'] ?>" class="form-label">كلمة المرور</label>
                                            <input type="password" class="form-control" id="password<?= $user['id'] ?>"
                                                name="password" placeholder="••••••••">
                                        </div>
                                        <button type="submit" class="btn btn-success">حفظ التعديلات</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }
            } else {
                echo '<tr><td colspan="5">لا توجد نتائج</td></tr>';
            } ?>
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>