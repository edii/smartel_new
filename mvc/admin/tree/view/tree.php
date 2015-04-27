<?php if(is_array($tree) and count($tree) > 0 and $validate ): ?>
        <ul class="sub">
            <?php foreach ($tree as $_items): ?>
                <li>
                    
                    <div class="section-item">
                        <a href="/<?= _request_uri ?>/<?= $_items['SectionUrl'] ?>">
                            <span class="icon16 icomoon-icon-stats-up"></span>
                            <?= $_items['SectionName'] ?> 
                        </a>
                        <?php if(is_array($_items['childs']) and count($_items['childs']) > 0) { ?> 
                            <span class="notification red section-item-control">sub<?= count($_items['childs']) ?></span> 
                        <?php } ?> 
                    </div>
                    
                    <?php if(is_array($_items['childs']) and count($_items['childs']) > 0): 
                            $this->render('tree', array('tree' => $_items['childs'], 'validate' => $validate));
                         endif; 
                     ?>
                </li>
            <?php endforeach; ?>
        </ul>
<?php endif; ?>