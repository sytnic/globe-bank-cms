<?php
  // Default values to prevent errors
  $page_id = $page_id ?? '';
  $subject_id = $subject_id ?? '';
  $visible = $visible ?? true;
  // $visible берётся со скриптов ранее или устанавливается true по умолчанию
?>
<navigation>
  <?php
  // ранее всегда было ['visible' => true], т.е. всегда показывались только "видимые" страницы со столбцом visible == true.
  // Теперь меняется динамически в зависимости от переменной !$preview в идущих ранее скриптах.
  // Когда $preview == true, то $visible == false, и тогда SQL-запрос вообще не учитывает столбец visible,
  // возвращая все запрошенные строки, несмотря на значение в столбце visible.
  ?>
  <?php $nav_subjects = find_all_subjects(['visible' => $visible]); ?>
  
  <ul class="subjects">
    <?php while($nav_subject = mysqli_fetch_assoc($nav_subjects)) { ?>
      <?php  // если субъект (тема) невидимый, то пропустить текущую итерацию цикла
             // if(!$nav_subject['visible']) { continue; } ?>

      <li class="<?php if($nav_subject['id'] == $subject_id) {echo 'selected';}  ?>">
        <a href="<?php echo url_for('index.php?subject_id='.h(u($nav_subject['id']))); ?>">
          <?php echo h($nav_subject['menu_name']); ?>
        </a>

        <?php if($nav_subject['id'] == $subject_id) { 
            // если id текущего get-параметра совпали с id темы, 
            // то показать подстраницы под темой ?>
        <?php
        // ранее всегда было ['visible' => true]
        // Теперь меняется динамически в зависимости от переменной !$preview в идущих ранее скриптах.
        // Когда $preview == true, то $visible == false, и тогда
        // возвращаются все запрошенные строки, несмотря на значение в столбце visible.
        ?>
        <?php $nav_pages = find_pages_by_subject_id($nav_subject['id'], ['visible' => $visible]); ?>
        <ul class="pages">
          <?php while($nav_page = mysqli_fetch_assoc($nav_pages)) { ?>
            <?php  // если страница невидима, то пропустить текущую итерацию цикла
                   // if(!$nav_page['visible']) { continue; } ?>

            <li class="<?php if($nav_page['id'] == $page_id) {echo 'selected';}  ?>">
              <a href="<?php echo url_for('index.php?id='.h(u($nav_page['id']))); ?>">
                <?php echo h($nav_page['menu_name']); ?>
              </a>                          
            </li>
          <?php } // while $nav_pages ?>
        </ul>
        <?php mysqli_free_result($nav_pages); ?>
        <?php } // if($nav_subject['id'] == $subject_id) ?>
      
      </li>
    <?php } // while $nav_subjects ?>
  </ul>

  <?php mysqli_free_result($nav_subjects); ?>
</navigation>
