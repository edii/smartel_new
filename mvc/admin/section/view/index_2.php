<?php if(!$validate): ?>
    <!-- error fatall or other -->
<?php else: ?>

        
<!--Body content-->
<div id="content" class="clearfix">
    <div class="contentwrapper"><!--Content wrapper-->

        <div class="heading">

            <h3><?= $sections_actual['name'] ?></h3>                    

            <div class="resBtnSearch">
                <a href="#"><span class="icon16 icomoon-icon-search-3"></span></a>
            </div>

            <div class="search">

                <form id="searchform" action="search.html">
                    <input type="text" id="tipue_search_input" class="top-search" placeholder="Search here ..." />
                    <input type="submit" id="tipue_search_button" class="search-btn" value=""/>
                </form>

            </div><!-- End search -->

            <ul class="breadcrumb">
                <li>You are here:</li>
                <li>
                    <a href="#" class="tip" title="back to dashboard">
                        <span class="icon16 icomoon-icon-screen-2"></span>
                    </a> 
                    <span class="divider">
                        <span class="icon16 icomoon-icon-arrow-right-3"></span>
                    </span>
                </li>
                <li class="active"><?= $sections_actual['name'] ?></li>
            </ul>

        </div><!-- End .heading-->
        
        <div class="row-fluid">
            <div class="span">
                <div class="box">

                    <div class="title">

                        <h4>
                            <span class="icon16 icomoon-icon-equalizer-2"></span>
                            <span>Sections controls</span>
                            
                             
                            
                            <form class="box-form right" action="">
                                <a style="margin-right: 5px;" href="<?= $this->_getUrl() ?>/manager/method/add">Добавить</a> 
                                
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    <span class="icon16 icomoon-icon-cog-2"></span>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="#"><span class="icon-pencil"></span> Скрыть </a></li>
                                    <li><a href="#"><span class="icon-pencil"></span> Отобразить </a></li>
                                    <li><a class="delete" href="#"><span class="icon-trash"></span> Удалить </a></li>
                                </ul>
                            </form>
                            
                        </h4>
                        
                        <a href="#" class="minimize"> Минимизация </a>
                    </div>
                    
                    <div class="content noPad clearfix">
                        
                        
                        <ul id="menu-to-edit" class="menu ui-sortable sortable section clearfix">
                            <li id="head">
                                <div class="item-sorttable w-20 left font-bold">#</div>
                                <div class="item-sorttable w-20 left font-bold">ID</div>
                                <div class="item-sorttable w-100 left font-bold">TimeCreated</div>
                                <div class="item-sorttable w-100 left font-bold">Alias</div>
                                <div class="item-sorttable w-100 left font-bold">UserID</div>
                                <div class="item-sorttable w-100 left font-bold">Type</div>
                                <div class="item-sorttable w-100 left font-bold">Name</div>
                                <div class="item-sorttable w-100 left font-bold">Controller</div>
                                <div class="item-sorttable w-100 left font-bold">Action</div>
                              
                                <div id="masterCh" class="item-sorttable ch left font-bold"><input type="checkbox" name="checkbox" value="all" class="styled" /></div>
                                <div class="w-50 action right font-bold">Actions</div>
                            </li>
                           
                                <?php 
                               //var_dump($section_list); die('stop');
                              if(is_array($section_list) and count($section_list) > 0): ?>
                                <?php 
                                $lavel = 0;
                                foreach($section_list as $_key => $_item): ?>
                                    <li id="menu-item-<?= $_item['SectionID'] ?>" class="menu-item menu-item-depth-<?= $lavel ?> menu-item-edit-inactive"> <?php //custom ?>
                                        
                                        <dl class="menu-item-bar clearfix">
                                            <dt class="menu-item-handle clearfix">
                                                <div class="item-sorttable w-20 sections-list-collumn left">  
                                                    <a class="item-edit tabledrag" href="#"><?= $lavel ?></a>
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
                                                        <a href="<?= $this->_getUrl() ?>/manager/method/edit/id/<?= $_item['SectionID'] ?>" title="Редактировать Section" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
                                                        <a class="delete" href="<?= $this->_getUrl() ?>/delete/id/<?= $_item['SectionID'] ?>" title="Удалить Section" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
                                                    </div>
                                                </div>
                                            </dt>
                                        </dl>
                                        <ul class="menu-item-transport"></ul>
                                        
                                      
                                    </li>  
                                    
                                    <?php
                                      // childs
                                      if(isset($_item['childs']) and !empty($_item['childs'])) :
                                          $this -> renderView('childs', array('childs_list' => $_item['childs'], 'lavel' => $lavel));
                                      endif; 
                                    ?>
                                        
                                <?php endforeach; ?>
                              <?php endif; ?>  
                            
                        </ul>
                        
                        
                    </div>

                </div><!-- End .box -->
            </div> <!-- End span -->
            
        </div>

    </div><!-- End contentwrapper -->
</div><!-- End #content -->


<?php endif; ?>