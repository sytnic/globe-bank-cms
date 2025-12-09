## 04. Set up the database

Создание БД globe_bank_cms примерно так:  

    CREATE DATABASE globe_bank;

    GRANT ALL PRIVILEGES ON globe_bank.* TO 'webuser'@'localhost'
    IDENTIFIED BY 'secretpassword';

Перенос таблиц в командной строке
    
    # Import subjects and pages tables and data
    mysql -u webuser -p globe_bank < path/to/file.sql

## 06. List subjects

Пример полного цикла соединения с БД с помощью mysqli.

```php
// 1
    $db = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
// 2    
    $sql = "SELECT * FROM subjects ";
    $sql.= " ORDER BY position ASC";
    
    $subject_set = mysqli_query($db, $sql);
// 3    
    while ($subject = mysqli_fetch_assoc($subject_set)) {
        echo $subject['menu_name'];
    }
// 4
    mysqli_free_result($subject_set);
// 5
    mysqli_close($db);
```

## 15. Use an option for conditional code

Как использовать массив вместо аргументов в функции

```php

function find_all_subjects($options = []) { 
    
    $visible = $options['visible'] ?? false;
    
    if($visible) {
      // ...
    }
}

$opts = ['visible' => true, 'order' => 'ascending'];

$subjects = find_all_subjects($opts);

```

## 16. Insecure direct object reference (IDOR)

Это по сути доступ из адресной строки к параметрам или id, к которым пользователь не должен получать доступ.  
В коде это выражается в выполнении sql-кода без проверки прав доступа пользователя.  
Аналогично неправомерный доступ может быть получен к файлам, директориям, скриптам, функциям.  

## 18. Allow HTML in dynamic content

Спсобы экранирования опасных html-тэгов

- использовать свой собственный символический язык (например, фигурные скобки вместо угловых скобок тегов)
- Markdown
- библиотека HTMLPurifier, htmlpurifier.org
- встроенная функция в PHP - strip_tags(), позволяет указывать разрешённые теги. Также для правильного форматирования тут может помочь функция nl2br()

## 23. Unset cookie values

Два способа. Но делают они одно и то же: `false` откатывает на 1 час назад.

```php
// Right: set value to false 
setcookie($name, false);

// Right: expire 1 hour ago
setcookie($name, $value, (time() - 3600));
```

- Избегайте логических значений при настройке (установке) файлов cookie.
- Вместо этого используйте "0" для "false" и "1" для "true".
- Файлы cookie можно удалить только с помощью тех же параметров, которые использовались при настройке (установке) файлов cookie.

## 24. Work with sessions

Сессии могут быть настроены в php.ini.

https://www.php.net/manual/ru/session.configuration.php

Старт сессии

    session_start($options);

## 25. Set and read session values

> Основы работы с сессиями.

Установить данные сессии.

    $_SESSION['lang'] = 'English';

Прочитать данные сессии   

    $lang = $_SESSION['lang'];    
    
    // чтение с предварительной проверкой доступности данных
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : '';
    // или так
    $lang = $_SESSION['lang'] ?? ''; // PHP > 7.0

Удалить данные сессии.

    unset($_SESSION['lang']);

##