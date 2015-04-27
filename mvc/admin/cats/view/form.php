<?php if(!$validate): ?>
    <!-- error fatall or other -->
<?php else: ?>

<!--Body content-->
<div id="content" class="clearfix">
    <div class="contentwrapper"><!--Content wrapper-->

        <div class="heading">

            <h3>Dashboard</h3>                    

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
                <li class="active">Dashboard</li>
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
                                <input type="hidden" name="cats[CatsID]" value="<?= $this->getParam('id') ?>" />
                                <input type="hidden" name="cats[cats_mod_date]" value="<?= date('Y-m-d H:i:s') ?>" />
                            <?php endif; ?>
                            <!-- add -->
                            <?php if($this->getParam('method') == 'add'): ?>
                                <input type="hidden" name="cats[cats_create_date]" value="<?= date('Y-m-d H:i:s') ?>" />
                            <?php endif; ?>     
                                
                            
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">cats_parent_id</label>
                                        <input class="span8" id="normalInput" type="text" name="cats[cats_parent_id]" value="<?= (isset($listing)) ? $listing['cats_parent_id'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">cats_name</label>
                                        <input class="span8" id="normalInput" type="text" name="cats[cats_name]" value="<?= (isset($listing)) ? $listing['cats_name'] : '' ?>" />
                                    </div>
                                </div>
                            </div>    
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">cats_desc</label>
                                        <input class="span8" id="normalInput" type="text" name="cats[cats_desc]" value="<?= (isset($listing)) ? $listing['cats_desc'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">cats_url</label>
                                        <input class="span8" id="normalInput" type="text" name="cats[cats_url]" value="<?= (isset($listing)) ? $listing['cats_url'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">type</label>
                                        <input class="span8" id="normalInput" type="text" name="cats[type]" value="<?= (isset($listing)) ? $listing['type'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">lang_id</label>
                                        <input class="span8" id="normalInput" type="text" name="cats[lang_id]" value="<?= (isset($listing)) ? $listing['lang_id'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">guid</label>
                                        <input class="span8" id="normalInput" type="text" name="cats[guid]" value="<?= (isset($listing)) ? $listing['guid'] : '' ?>" />
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