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
                            <span><?= $title ?></span>
                        </h4>

                    </div>
                    <div class="content">

                        <form class="form-horizontal" action="" method="POST" >
                            <input type="hidden" name="method" value="<?= ($this->getParam('method') == 'edit') ? 'edit': 'add' ?>" />
                            <!-- edit -->
                            <?php if((int)$this->getParam('id')): ?>
                                <input type="hidden" name="section[SectionID]" value="<?= $this->getParam('id') ?>" />
                                <input type="hidden" name="section[TimeSaved]" value="<?= date('Y-m-d H:i:s') ?>" />
                            <?php endif; ?>
                            <!-- add -->
                            <?php if($this->getParam('method') == 'add'): ?>
                                <input type="hidden" name="section[TimeCreated]" value="<?= date('Y-m-d H:i:s') ?>" />
                            <?php endif; ?>     
                                
                            
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionAlias</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionAlias]" value="<?= (isset($listing)) ? $listing['SectionAlias'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">OwnerID</label>
                                        <input class="span8" id="normalInput" type="text" name="section[OwnerID]" value="<?= (isset($listing)) ? $listing['OwnerID'] : '' ?>" />
                                    </div>
                                </div>
                            </div>    
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">UserID</label>
                                        <input class="span8" id="normalInput" type="text" name="section[UserID]" value="<?= (isset($listing)) ? $listing['UserID'] : '' ?>" />
                                    </div>
                                </div>
                            </div> 
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionType</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionType]" value="<?= (isset($listing)) ? $listing['SectionType'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        
                                        <?php
//                                            $_listOptions = array(
//                                                0 => array('t1' => 'test1'),
//                                                1 => array('t2' => 'test2'),
//                                                2 => array('t3' => 'test3'),
//                                            );
                                        
                                        /*$_listOptions = array(
                                              'value1' => array('disabled' => true),
                                              'value2' => array('label' => 'value 2'),
                                         );
                                            
                                           
                                            // echo \CHtml::dropDownList('test[]', 't3', $_listOptions);
                                            echo \CHtml::activeDropDownList(array('formName' => 'section', 't3' => true), 't3', $_listOptions);
                                         */
                                        ?>
                                        
                                        <label class="form-label span4" for="normal">SectionParentID</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionParentID]" value="<?= (isset($listing)) ? $listing['SectionParentID'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionIntroContent</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionIntroContent]" value="<?= (isset($listing)) ? $listing['SectionIntroContent'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionContent</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionContent]" value="<?= (isset($listing)) ? $listing['SectionContent'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionName</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionName]" value="<?= (isset($listing)) ? $listing['SectionName'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionController</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionController]" value="<?= (isset($listing)) ? $listing['SectionController'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionAction</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionAction]" value="<?= (isset($listing)) ? $listing['SectionAction'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionUrl</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionUrl]" value="<?= (isset($listing)) ? $listing['SectionUrl'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                            
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">hidden</label>
                                        <input class="span8" id="normalInput" type="text" name="section[hidden]" value="<?= (isset($listing)) ? $listing['hidden'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                            
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionInMenu</label>
                                        <input class="span8" id="normalInput" type="text" name="section[SectionInMenu]" value="<?= (isset($listing)) ? $listing['SectionInMenu'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                             
        
                            <div class="form-actions">
                               <button type="submit" class="btn btn-info">Save changes</button>
                               <button type="button" class="btn">Cancel</button>
                            </div>


                        </form>

                    </div>

                </div><!-- End .box -->

        </div>
        

    </div><!-- End contentwrapper -->
</div><!-- End #content -->

<?php endif; ?>