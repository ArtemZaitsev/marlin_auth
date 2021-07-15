<?php

/*---------------------Регистрация---------------------*/
/**
get_user_by_id

  Parameters: string - email.
 Description: искать пользователя по email.
Return value: array.
 */
function get_user_by_email($email){
//  подключаюсь к БД
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root","");

//  готовлю запрос в БД
    $sql = "SELECT * FROM users WHERE email=:email";

//  подготоваливаю запрос в БД и помещаю его в переменную
    $statement = $pdo->prepare($sql);

//  выполняю запрос в БД
    $statement->execute(["email" => $email,]);

// возвращаю данные (объект) в виде ассоциативного массива и сохраняю в переменную.
    $user = $statement->fetch(PDO::FETCH_ASSOC);

// на выходе из функции возвращаю переменную
    return $user;
}

/**
set_flash_message

  Parameters: string - name (ключ);
              string - message.
 Description: записать в сессию значение сообщения по ключу.
Return value: null.
 */
//function set_flash_message($name, $message) {
////в глобальный объект SESSION (ассоциативный массив) записываю новые данные "ключ-значение".
//    $_SESSION[$name] = $message;
//}
function set_flash_message($block_style,$message) {
//в глобальный объект SESSION (ассоциативный массив) записываю новые данные "ключ-значение".
    $_SESSION['block_style'] = $block_style;
    $_SESSION['message'] = $message;
}
/**
display_flash_message

Parameters: string - name.
Description: вывести сообщение.
Return value: null.
 */
function display_flash_message($name) {
    // условие, что если в глобальном массиве SESSION существует ключ со значением "name"
    if(isset($_SESSION['block_style'])) {
        // вывожу сообщение ввиде HTML с использованием классов Bootstrap
        echo "<div class=\"alert alert-{$_SESSION['block_style']} text-dark\" role=\"alert\">{$_SESSION['message']}</div>";
        // удаляю "ключ-значение" в глобально массиве SESSION по ключу "name" с использованием стандарной фукнции "unset"
        unset($_SESSION['block_style']);
        unset($_SESSION['message']);
    }
}

/**
redirect_to

  Parameters: string - path.
 Description: перенаправить на другую страницу.
Return value: null.
 */
function redirect_to($path) {
//помещаю функцию header в свою с более понятным названием и более кратким написанием (без "Location")
    header("Location: {$path}");
    exit;
}

/**
add_user

  Parameters: string - email;
              string - password.
 Description: добавить пользователя в БД.
Return value: int - (user_id).

*/
function add_user($email, $password){
//  подключаюсь к БД
    $pdo = new PDO("mysql:host=localhost;dbname=my_project", "root","");
//  готовлю запрос в БД
    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
//  подготоваливаю запрос в БД и помещаю его в переменную
    $statement = $pdo->prepare($sql);
//  выполняю запрос в БД
    $statement->execute([
        "email" => $email,
        "password" => password_hash($password,PASSWORD_DEFAULT),
    ]);
// на выходе из функции подключаюс к базе и возвращаю id добавленного пользователя
    return $pdo->lastInsertId();
}

/*---------------------Авторизация---------------------*/

/**
login

  Parameters: string - email;
              string - password.
 Description: авторизовать пользователя.
Return value: boolean.
 */

function login($email, $password) {
//получаем по email все данные пользователя  в виде ассоциативного массива из базы и помещаем его в переменную
    $user_from_db = get_user_by_email($email);
//получаем пароль из базы данных в виде хеша и помещаем его в переменную
    $password_from_db = $user_from_db ['password'];

//при помощи стандартной функции PHP сравниванием пароль, который ввел пользователь при авторизации и пароль,
// который хранится в базе
    $result = password_verify($password, $password_from_db);
    return $result;
}
