<?php

  // Performs all actions necessary to log in an admin
  /**
   * Устанавливает значения в сессию из ассоциативного массива,
   * полученного из БД
   *  
   * @param array
   * @return boolean
   */
  function log_in_admin($admin) {
  // Regenerating the ID protects the admin from session fixation.
    session_regenerate_id();
    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['last_login'] = time();
    $_SESSION['username'] = $admin['username'];
    return true;
  }

?>
