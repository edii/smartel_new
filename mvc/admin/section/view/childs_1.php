<?php if(is_array($childs_list) and count($childs_list) > 0): ?>
    <?php 
    $lavel += 1;
    $_nbsp = '';
    for($i = 0; $i < $lavel; $i++) {
        $_nbsp .= '<div class="iteration">-</div>';
    }
    foreach($childs_list as $_key => $_item): ?>
        <tr>
            <td><?= $_nbsp ?><a class="tabledrag" href="#"> <?= $lavel ?>  </a></td>
            <td><?= $_item['TimeCreated'] ?></td>
            <td><?= $_item['SectionAlias'] ?></td>
            <td><?= $_item['UserID'] ?></td>
            <td><?= $_item['SectionType'] ?></td>
            <td><?= $_item['SectionParentID'] ?></td>
            <td><?= $_item['SectionName'] ?></td>
            <td><?= $_item['SectionController'] ?></td>
            <td><?= $_item['SectionAction'] ?></td>
            <td><?= $_item['SectionUrl'] ?></td>
            <td><?= $_item['hidden'] ?></td>
            <td class="chChildren"><input type="checkbox" name="checkbox" value="1" class="styled" /></td>
            <td>
                <div class="controls center">
                    <a href="#" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
                    <a class="delete" href="#" title="Remove task" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
                </div>
            </td>
          </tr>

          <?php
          // childs
          if(isset($_item['childs']) and !empty($_item['childs'])) :
              $this->renderView('childs', array('childs_list' => $_item['childs'], 'lavel' => $lavel));
          endif; ?>

    <?php endforeach; ?>
  <?php endif; ?> 