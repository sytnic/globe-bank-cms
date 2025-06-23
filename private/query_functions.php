<?php

// Subjects

/**
 * Получает набор всех тем из таблицы subjects БД 
 * 
 * @return mysqli_result|bool
 */
function find_all_subjects() {
    global $db;
    
    $sql = "SELECT * FROM subjects";
    $sql.= " ORDER BY position ASC";
    $result_set = mysqli_query($db, $sql);
    confirm_result_set($result_set);
    return $result_set;
}

/**
 * Получает ассоциативный массив одной записи
 * темы из БД по её id
 *
 * @param string $id
 * @return array
 */
function find_subject_by_id($id) {
    global $db;

    $sql = "SELECT * FROM subjects";
    $sql.= " WHERE id='".db_escape($db, $id)."'";
    // echo $sql;
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);

    $subject = mysqli_fetch_assoc($result);
    mysqli_free_result($result);

    return $subject; // return an assoc. array
}

/**
 * Валидация темы
 *
 * @param array $subject
 * @return array
 */
function validate_subject($subject) {

    $errors = [];
    
    // menu_name
    if(is_blank($subject['menu_name'])) {
      $errors[] = "Name cannot be blank.";
    } elseif (!has_length($subject['menu_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Name must be between 2 and 255 characters.";
    }
  
    // position
    // Make sure we are working with an integer
    $postion_int = (int) $subject['position'];
    if($postion_int <= 0) {
      $errors[] = "Position must be greater than zero.";
    }
    if($postion_int > 999) {
      $errors[] = "Position must be less than 999.";
    }
  
    // visible
    // Make sure we are working with a string
    $visible_str = (string) $subject['visible'];
    if(!has_inclusion_of($visible_str, ["0","1"])) {
      $errors[] = "Visible must be true or false.";
    }
  
    return $errors;
}

/**
 * Вставит запись темы в БД
 *
 * @param array $subject
 * @return array|(true|error)
 */
function insert_subject($subject) {
    global $db;

    // return array
    $errors = validate_subject($subject);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "INSERT INTO subjects";
    $sql.= " (menu_name, position, visible)";
    $sql.= " VALUES (";
    $sql.= "'".db_escape($db, $subject['menu_name'])."',";
    $sql.= "'".db_escape($db, $subject['position'])."',";
    $sql.= "'".db_escape($db, $subject['visible'])."'";
    $sql.= ")";

    $result = mysqli_query($db, $sql);
    // For INSERT, $result is true/false.
    // Для всех запросов, кроме SELECT, возвращается true/false.
    // Для SELECT - результирующий набор.
    if($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }

}

/**
 * Обновит запись темы в БД
 *
 * @param array $subject
 * @return array|(true|error) 
 */
function update_subject($subject) {
    global $db;

    // return array
    $errors = validate_subject($subject);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE subjects SET";
    $sql.= " menu_name='".db_escape($db, $subject['menu_name'])."',";
    $sql.= " position='".db_escape($db, $subject['position'])."',";
    $sql.= " visible='".db_escape($db, $subject['visible'])."'";
    $sql.= " WHERE id='".db_escape($db, $subject['id'])."' ";
    $sql.= " LIMIT 1";
  
    $result = mysqli_query($db, $sql);
    // For UPDATE $result is true/false
    if($result) {
      // redirect_to(url_for('/staff/subjects/show.php?id='.$id));
      // вместо редиректа вернём true
      return true;
    } else {
      // Update failed
      echo mysqli_error($db);
      db_dissonnect($db);
      exit;
    }
}

/**
 * Удалит запись темы в БД
 *
 * @param string $id
 * @return true|error 
 */
function delete_subject($id) {
    global $db;

    $sql = "DELETE FROM subjects";
    $sql.= " WHERE id='".db_escape($db, $id)."'";
    $sql.= " LIMIT 1";

    $result = mysqli_query($db, $sql);

    // For DELETE $result is true/false
    if($result) {
        // redirect_to(url_for('/staff/subjects/index.php'));
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


// Pages

/**
 * Получает набор всех записей из таблицы pages БД 
 * 
 * @return mysqli_result|bool
 */
function find_all_pages() {
    global $db;
    
    $sql = "SELECT * FROM pages";
    $sql.= " ORDER BY subject_id ASC, position ASC";
    $result_set = mysqli_query($db, $sql);
    confirm_result_set($result_set);
    return $result_set;
}


/**
 * Получает ассоциативный массив одной записи 
 * страницы из БД по её id
 *
 * @param string $id
 * @return array 
 */
function find_page_by_id($id) {
    global $db;

    $sql = "SELECT * FROM pages";
    $sql.= " WHERE id='".db_escape($db, $id)."'";
    $result = mysqli_query($db, $sql);
    confirm_result_set($result);
    $page = mysqli_fetch_assoc($result);
    mysqli_free_result($result);
    return $page; // returns an assoc. array
}

/**
 * Ищет и возвращает ошибки в форме создания или обновления страницы (page)
 */
function validate_page($page) {
    $errors = [];

    // subject_id
    if(is_blank($page['subject_id'])) {
      $errors[] = "Subject cannot be blank.";
    }

    // menu_name
    if(is_blank($page['menu_name'])) {
      $errors[] = "Name cannot be blank.";
    } elseif(!has_length($page['menu_name'], ['min' => 2, 'max' => 255])) {
      $errors[] = "Name must be between 2 and 255 characters.";
    }

    // уникальное ли имя меню.
    // $page['id'] явно дано только при edit,
    // при insert его нет, и используется 0 по умолчанию.
    $current_id = $page['id'] ?? '0';
    if(!has_unique_page_menu_name($page['menu_name'], $current_id)) {
      $errors[] = "Menu name must be unique.";
    }

    // position
    // Make sure we are working with an integer
    $postion_int = (int) $page['position'];
    if($postion_int <= 0) {
      $errors[] = "Position must be greater than zero.";
    }
    if($postion_int > 999) {
      $errors[] = "Position must be less than 999.";
    }

    // visible
    // Make sure we are working with a string
    $visible_str = (string) $page['visible'];
    if(!has_inclusion_of($visible_str, ["0","1"])) {
      $errors[] = "Visible must be true or false.";
    }

    // content
    if(is_blank($page['content'])) {
      $errors[] = "Content cannot be blank.";
    }

    return $errors;
}

/**
 * Вставляет в БД запись о новой странице
 *
 * @param array $page
 * @return true|error  
 */
function insert_page($page) {
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "INSERT INTO pages";
    $sql.= " (subject_id, menu_name, position, visible, content)";
    $sql.= " VALUES (";
    $sql.= "'".db_escape($db, $page['subject_id'])."', ";
    $sql.= "'".db_escape($db, $page['menu_name'])."', ";
    $sql.= "'".db_escape($db, $page['position'])."', ";
    $sql.= "'".db_escape($db, $page['visible'])."', ";
    $sql.= "'".db_escape($db, $page['content'])."'";
    $sql.= ")";

    $result = mysqli_query($db, $sql);
    // For INSERT statements, $result is true/false
    if($result) {
        return true;
    } else {
        // INSERT failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/**
 * Обновляет запись о странице в БД
 *
 * @param array $page 
 * @return true|error  
 */
function update_page($page) {
    global $db;

    $errors = validate_page($page);
    if(!empty($errors)) {
        return $errors;
    }

    $sql = "UPDATE pages SET";
    $sql.= " subject_id='".db_escape($db, $page['subject_id'])."', ";
    $sql.= " menu_name='".db_escape($db, $page['menu_name'])."', ";
    $sql.= " position='".db_escape($db, $page['position'])."', ";
    $sql.= " visible='".db_escape($db, $page['visible'])."', ";
    $sql.= " content='".db_escape($db, $page['content'])."' ";
    $sql.= " WHERE id='".db_escape($db, $page['id'])."' ";
    $sql.= " LIMIT 1";    

    $result = mysqli_query($db, $sql);
    // For UPDATE statements, $result is true/false
    if($result) {
        return true;
    } else {
        // UPDATE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}

/**
 * Удаляет запись о странице в БД
 *
 * @param string $id 
 * @return true|error  
 */
function delete_page($id) {
    global $db;

    $sql = "DELETE FROM pages ";
    $sql.= " WHERE id='".db_escape($db, $id)."'";
    $sql.= " LIMIT 1";
    
    $result = mysqli_query($db, $sql);
    // For DELETE statements, $result is true/false
    if($result) {
        return true;
    } else {
        // DELETE failed
        echo mysqli_error($db);
        db_disconnect($db);
        exit;
    }
}


?>