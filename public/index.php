<?php require_once('../private/initialize.php');  ?>

<?php include(SHARED_PATH.'/public_header.php'); ?>

<div id="main">

<?php include(SHARED_PATH.'/public_navigation.php'); ?>
  
  <div id="page">

  <?php
      // Show the homepage

      // The homepage content could:
      // * be static content (here or in a shared file)
      // * show the first page from the nav
      // * be in the database but add code to hide in the nav

      // Содержимое главной страницы может быть:
      // * статичным (здесь или в shared файле)
      // * страница хранится в БД, показать первую страницу из навигационной системы,  
      // * страница хранится в БД, но не относится к навигационной системе

      include(SHARED_PATH . '/static_homepage.php');
  ?>

  </div>
</div>

<?php include(SHARED_PATH.'/public_footer.php'); ?>