<?php
session_start();
require 'function.php';

$email = $_POST["email"];
$password = $_POST["password"];

if (login($email, $password)) {
// если пароли совпадают, то перенаправить на страницу "пользователи"
    $path = "users.php";
} else {
// если пароли не совпадают, то вернуть на страницу авторизации, показав сообщение
    set_flash_message("danger","Логин или пароль не совпадают.");
    $path = "page_login.php";
}
redirect_to($path);


