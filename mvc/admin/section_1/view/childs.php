<?php if(is_array($childs_list) and count($childs_list) > 0): ?>
<!--   <ul class="sortable subcat-section section hidden">-->
    <?php 
    $lavel += 1;
    $_nbsp = '';
    for($i = 0; $i < $lavel; $i++) {
        $_nbsp .= '<div class="iteration">-</div>';
    }
    foreach($childs_list as $_key => $_item): ?>
        <li id="menu-item-<?= $_item['SectionID'] ?>" class="menu-item menu-item-depth-<?= $lavel ?> menu-item-edit-inactive">
            
            <dl class="menu-item-bar clearfix">
                <dt class="menu-item-handle clearfix">
                    <div class="item-sorttable w-20 sections-list-collumn left">
                        <?= $_nbsp ?>
                        <a class="tabledrag" href="#"> <?= $lavel ++ ?>  </a>
                        <span id="sections" class="open-subcat"> sub </span>
                    </div>
                    <div class="item-sorttable w-20 sections-list-collumn left"><?= $_item['SectionID'] ?></div>
                    <div class="item-sorttable w-100 sections-list-collumn left"><?= $_item['TimeCreated'] ?></div>
                    <div class="item-sorttable w-100 sections-list-collumn left"><?= $_item['SectionAlias'] ?></div>
                    <div class="item-sorttable w-100 sections-list-collumn left"><?= $_item['UserID'] ?></div>
                    <div class="item-sorttable w-100 sections-list-collumn left"><?= $_item['SectionType'] ?></div>
                    <div class="item-sorttable w-100 sections-list-collumn left"><?= $_item['SectionName'] ?></div>
                    <div class="item-sorttable w-100 sections-list-collumn left"><?= $_item['SectionController'] ?></div>
                    <div class="item-sorttable w-100 sections-list-collumn left"><?= $_item['SectionAction'] ?></div>

                    <div class="item-sorttable sections-list-collumn ch Children left"><input type="checkbox" name="checkbox" value="1" class="styled" /></div>
                    <div class="action right">
                        <div class="controls center">
                            <a href="<?= $this->_getUrl() ?>/manager/method/edit/id/<?= $_item['SectionID'] ?>" title="Edit task" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
                            <a class="delete" href="<?= $this->_getUrl() ?>/delete/id/<?= $_item['SectionID'] ?>" title="Remove task" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
                        </div>
                    </div>
                
                </dt>
            </dl>    
            
            
          </li>

          <?php
          // childs
          if(isset($_item['childs']) and !empty($_item['childs'])) :
              $this->renderView('childs', array('childs_list' => $_item['childs'], 'lavel' => $lavel));
          endif; ?>

    <?php endforeach; ?>
<!--   </ul>      -->
  <?php endif; ?> 