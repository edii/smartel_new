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
                            <span>Меню</span>
                            
                             
                            
                            <form class="box-form right" action="">
                                <a style="margin-right: 5px;" href="<?= $this->_getUrl() ?>/manager/method/add">Добавить</a> 
                                
                                <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
                                    <span class="icon16 icomoon-icon-cog-2"></span>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?= $this->_getUrl() ?>/manager/method/hide"><span class="icon-pencil"></span> Скрыть </a></li>
                                    <li><a href="<?= $this->_getUrl() ?>/manager/method/show"><span class="icon-pencil"></span> Отобразить </a></li>
                                    <li><a class="delete" href="<?= $this->_getUrl() ?>/manager/method/delete"><span class="icon-trash"></span> Удалить </a></li>
                                </ul>
                            </form>
                            
                        </h4>
                        
                        <a href="#" class="minimize"> Минимизация </a>
                    </div>
                    <div class="content noPad">                     
                        <table class="table table-bordered" id="checkAll">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>ID</th>
                                <th>Назва групи</th>
                                <th>Тип групи</th>
                                <th>Позиція групи</th>
                                
                                 <th id="masterCh" class="ch"><input type="checkbox" name="checkbox" value="all" class="styled" /></th>
                                <th>Actions</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php if(is_array($listing) and count($listing) > 0): ?>
                                <?php foreach($listing as $_key => $_item): ?>
                                    <tr>
                                        <td><?= ($_key + 1) ?></td>
                                        <td><?= $_item->SectionGroupID ?></td>
                                        <td><?= $_item->SectionGroupName ?></td>
                                        <td><?= $_item->SectionGroupType ?></td>
                                        <td><?= $_item->SectionGroupPosition ?></td>
                                        <td class="chChildren"><input type="checkbox" name="checkbox" value="1" class="styled" /></td>
                                        <td>
                                            <div class="controls center">
                                                <a href="<?= $this->_getUrl() ?>/manager/method/edit/id/<?= $_item -> SectionGroupID ?>" title="Редактировать групу" class="tip"><span class="icon12 icomoon-icon-pencil"></span></a>
                                                <a class="delete" href="<?= $this->_getUrl() ?>/delete/id/<?= $_item -> SectionGroupID ?>" title="Удалить групу" class="tip"><span class="icon12 icomoon-icon-remove"></span></a>
                                            </div>
                                        </td>
                                      </tr>
                                <?php endforeach; ?>
                              <?php endif; ?>  
                            </tbody>
                        </table>
                    </div>

                </div><!-- End .box -->
            </div> <!-- End span -->
            
        </div>
        

    </div><!-- End contentwrapper -->
</div><!-- End #content -->

<?php endif; ?>