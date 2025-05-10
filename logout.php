<?php
include_once 'tools/tools.php';
if (isset(get_session('user')['id'])) {
    delete_session('user');
}
go('login', null, false);