<?php

require_once('../../../private/initialize.php');

/* тестирование больше не нужно
$test = $_GET['test'] ?? '';

if($test == '404') {
  error_404();
} elseif($test == '500') {
  error_500();
} elseif($test == 'redirect') {
  redirect_to(url_for('/staff/subjects/index.php'));
}
*/

if (is_post_request()) {
  
      $subject = [];
  
      $subject["menu_name"] = $_POST['menu_name'] ?? '';
      $subject["position"] = $_POST['position'] ?? '';
      $subject["visible"] = $_POST['visible'] ?? '';
  
      $result = insert_subject($subject);
  
      if ($result === true) {
          // выясняем последний вставленный id
          $new_id = mysqli_insert_id($db);
          $_SESSION['message'] = 'The subject was created successfully.';
          redirect_to(url_for('/staff/subjects/show.php?id='.$new_id));
      } else {
          $errors = $result;
      }   
  
  } else {
    // если это не пост-запрос,
    // отображаем дальнейшую пустую форму
    
  }

  // выяснить количество строк в таблице БД
  $subject_set = find_all_subjects();
  // и прибавить 1
  // для возможности выбора нового места для новой позиции 
  // (на 1 больше от уже существующих)
  $subject_count = mysqli_num_rows($subject_set) + 1;
  mysqli_free_result($subject_set);

  $subject = [];
  $subject["position"] = $subject_count;
?>

<?php $page_title = 'Create Subject'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/subjects/index.php'); ?>">&laquo; Back to List</a>

  <div class="subject new">
    <h1>Create Subject</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/subjects/new.php'); ?>" method="post">
      <dl>
        <dt>Menu Name</dt>
        <dd><input type="text" name="menu_name" value="" /></dd>
      </dl>
      <dl>
        <dt>Position</dt>
        <dd>
        <select name="position">
          <!-- не вычисляемое динамически option          
            <option value="1">1</option>          
          -->
          <!-- вычисляемое динамически option  -->
          <?php 
            for($i=1; $i <= $subject_count; $i++) {
              echo "<option value=\"{$i}\"";
              if($subject["position"] == $i) {
                echo " selected";
              }
              echo ">{$i}</option>";
            }
          ?>
          </select>
        </dd>
      </dl>
      <dl>
        <dt>Visible</dt>
        <dd>
          <input type="hidden" name="visible" value="0" />
          <input type="checkbox" name="visible" value="1" />
        </dd>
      </dl>
      <div id="operations">
        <input type="submit" value="Create Subject" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>