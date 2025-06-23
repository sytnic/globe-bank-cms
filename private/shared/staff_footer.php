    <footer>
      &copy; <?php echo date('Y'); ?> Globe Bank
    </footer>

  </body>
</html>

<?php
  // отсоединение от БД,
  // работает благодаря тому, что на странице продолжает работать файл
  // database.php со своими функциями
  // и переменная соединения $db,
  // установленная в initialize.php
  db_disconnect($db);
?>