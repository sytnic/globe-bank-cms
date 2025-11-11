<?php require_once('../private/initialize.php'); ?>

<?php

if(is_post_request()) {
  // Form was submitted
  $language = $_POST['language'] ?? 'Any';
  // Время истечения на 365 дней -
  // 60 секунд * 60 минут * 24 часа * количество суток
  $expire = time() + 60*60*24*365;
  // Задать куки
  setcookie('language', $language, $expire);

} else {
  // Если это не пост-запрос, считать куки.
  // Read the stored value (if any)
  $language = $_COOKIE['language'] ?? 'Any';
}

?>

<?php include(SHARED_PATH . '/public_header.php'); ?>

<div id="main">

  <?php include(SHARED_PATH . '/public_navigation.php'); ?>

  <div id="page">

    <div id="content">
      <h1>Set Language Preference</h1>

      <p>The currently selected language is: <?php echo $language; ?></p>

      <form action="<?php echo url_for('/language.php'); ?>" method="post">

        <select name="language">
          <?php
            $language_choices = ['Any', 'English', 'Spanish', 'French', 'German'];
            foreach($language_choices as $language_choice) {
              echo "<option value=\"{$language_choice}\"";
              if($language == $language_choice) {
                echo " selected";
              }
              echo ">{$language_choice}</option>";
            }
          ?>
        </select><br />
        <br />
        <input type="submit" value="Set Preference" />
      </form>
    </div>

  </div>

</div>

<?php include(SHARED_PATH . '/public_footer.php'); ?>
