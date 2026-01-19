<?php

  // is_blank('abcd')
  // * validate data presence
  // * uses trim() so empty spaces don't count
  // * uses === to avoid false positives
  // * better than empty() which considers "0" to be empty

  // Вернёт true, если 
  // значение не задано,
  // или значение возвращает пустую строку
  function is_blank($value) {
    return !isset($value) || trim($value) === '';
  }


  // has_presence('abcd')
  // * validate data presence
  // * reverse of is_blank()
  // * I prefer validation names with "has_"

  // Если значение присутсвует (не пустой бланк), 
  // то true
  function has_presence($value) {
    return !is_blank($value);
  }


  // has_length_greater_than('abcd', 3)
  // * validate string length
  // * spaces count towards length
  // * use trim() if spaces should not count

  // Вернёт true,
  // если длина значения больше заданного минимума
  function has_length_greater_than($value, $min) {
    $length = strlen($value);
    return $length > $min;
  }


  // has_length_less_than('abcd', 5)
  // * validate string length
  // * spaces count towards length
  // * use trim() if spaces should not count

  // Вернёт true,
  // если длина значения меньше заданного максимума
  function has_length_less_than($value, $max) {
    $length = strlen($value);
    return $length < $max;
  }


  // has_length_exactly('abcd', 4)
  // * validate string length
  // * spaces count towards length
  // * use trim() if spaces should not count

  // Вернёт true,
  // если длина значения равна заданному значению
  function has_length_exactly($value, $exact) {
    $length = strlen($value);
    return $length == $exact;
  }


  // has_length('abcd', ['min' => 3, 'max' => 5])
  // * validate string length
  // * combines functions_greater_than, _less_than, _exactly
  // * spaces count towards length
  // * use trim() if spaces should not count

  // Вернёт false, если любая из проверок вернёт ложь (не будет пройдена)
  // Вернёт true, если будут пройдены все предыдущие проверки
  function has_length($value, $options) {
    if(isset($options['min']) && !has_length_greater_than($value, $options['min'] - 1)) {
      return false;
    } elseif(isset($options['max']) && !has_length_less_than($value, $options['max'] + 1)) {
      return false;
    } elseif(isset($options['exact']) && !has_length_exactly($value, $options['exact'])) {
      return false;
    } else {
      return true;
    }
  }


  // has_inclusion_of( 5, [1,3,5,7,9] )
  // * validate inclusion in a set

  // Вернёт true, если значение входит в состав массива $set
  function has_inclusion_of($value, $set) {
  	return in_array($value, $set);
  }


  // has_exclusion_of( 5, [1,3,5,7,9] )
  // * validate exclusion from a set

  // Вернёт true, если значение не входит в состав массива $set,
  // in_array, после вычисления булева, переворачивается наоборот
  function has_exclusion_of($value, $set) {
    return !in_array($value, $set);
  }


  // has_string('nobody@nowhere.com', '.com')
  // * validate inclusion of character(s)
  // * strpos returns string start position or false
  // * uses !== to prevent position 0 from being considered false
  // * strpos is faster than preg_match()
  
  // Вернёт true, если подстрока входит в состав строки
  function has_string($value, $required_string) {
    // strpos возвращает позицию вхождения, в т.ч. 0, или false,
    // результат strpos сравнивается с false
    return strpos($value, $required_string) !== false;
  }


  // has_valid_email_format('nobody@nowhere.com')
  // * validate correct format for email addresses
  // * format: [chars]@[chars].[2+ letters]
  // * preg_match is helpful, uses a regular expression
  //    returns 1 for a match, 0 for no match
  //    http://php.net/manual/en/function.preg-match.php
  
  // Вернёт true, если значение пройдёт проверку на формат почты
  function has_valid_email_format($value) {
    $email_regex = '/\A[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}\Z/i';
    return preg_match($email_regex, $value) === 1;
  }

  // has_unique_page_menu_name('History')
  // * Validates uniqueness of pages.menu_name
  // * For new records, provide only the menu_name.
  // * For existing records, provide current ID as second arugment
  //   has_unique_page_menu_name('History', 4)

  // Проверяет уникальность названия страницы в таблице pages.menu_name.
  // Для новых записей укажите только menu_name.
  // has_unique_page_menu_name('History')
  // Для существующих записей укажите текущий идентификатор в качестве второго аргумента.
  // has_unique_page_menu_name('History', 4)
  
  /**
   * Уникально ли имя меню
   * 
   * @return boolean
   */
  function has_unique_page_menu_name($menu_name, $current_id="0") {
    global $db;

    $sql = "SELECT * FROM pages";
    $sql.= " WHERE menu_name='".db_escape($db, $menu_name)."'";
    $sql.= " AND id != '".db_escape($db, $current_id)."'";

    $page_set = mysqli_query($db, $sql);
    $page_count = mysqli_num_rows($page_set);
    mysqli_free_result($page_set);

    return ($page_count === 0);

    // Запрос выглядит так:
    // SELECT * FROM pages
    // WHERE menu_name= 'History'
    // AND id != 2;

    // При подставлении существующей записи с её правильным подставленным id (AND id !=2) (во время edit) будет возвращено 0 строк.
    // И функция вернёт истину, $page_count равен нулю.
    // В дальнейшем используется 
    // if(!has_unique) если(истинно ли то, что не-истина),
    // нет, не записывать ошибки, пропустить, не делать ничего.
    // Ошибок нет. Таким образом, разрешено редактировать существующую запись.

    // При подставлении существующей записи с подставленным по умолчанию id !=0 (во время insert) будет возвращена 1 строка.
    // И функция вернёт ложь, $page_count не равен нулю.
    // В дальнейшем используется 
    // if(!has_unique) если(истинно ли то, что не-ложь), 
    // да, записать ошибки в массив $errorrs[] = "..."
    // Будут ошибки. Таким образом, запрещено создать такую же запись.

    // При подставлении несуществующей записи с подставленным по умолчанию id !=0 (во время insert) будет возвращено 0 строк.
    // И функция вернёт истину, $page_count равен нулю.
    // В дальнейшем используется 
    // if(!has_unique) если(истинно ли то, что не-истина),
    // нет, не записывать ошибки, пропустить, не делать ничего.
    // Ошибок нет. Таким образом, разрешено создать новую запись.

  }

  // has_unique_username('johnqpublic')
  // * Validates uniqueness of admins.username
  // * For new records, provide only the username.
  // * For existing records, provide current ID as second argument
  //   has_unique_username('johnqpublic', 4)

  /**
   * Уникально ли имя пользователя.  
   * 
   * Аналогично функции has_unique_page_menu_name().   
   * Работает и для существующей, и для новой записи.
   * 
   * @return boolean
   */
  function has_unique_username($username, $current_id="0") {
    global $db;

    $sql = "SELECT * FROM admins ";
    $sql .= "WHERE username='" . db_escape($db, $username) . "' ";
    $sql .= "AND id != '" . db_escape($db, $current_id) . "'";

    $result = mysqli_query($db, $sql);
    $admin_count = mysqli_num_rows($result);
    mysqli_free_result($result);

    return $admin_count === 0;
  }

?>