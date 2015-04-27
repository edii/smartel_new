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
                                        <label class="form-label span4" for="normal">lang_id</label>
                                        <input class="span8" id="normalInput" name="post[lang_id]" value="<?= (isset($listing)) ? $listing['lang_id'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">post_description</label>
                                        <textarea class="span8 elastic" id="textarea1" name="post[post_description]" rows="3"><?= (isset($listing)) ? $listing['post_description'] : '' ?></textarea>
                                    </div>
                                </div>
                            </div>    
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">post_intro</label>
                                        <textarea class="span8 elastic" id="textarea2" name="post[post_intro]" rows="3"><?= (isset($listing)) ? $listing['post_intro'] : '' ?></textarea>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">post_title</label>
                                        <input class="span8" id="normalInput" type="text" name="post[post_title]" value="<?= (isset($listing)) ? $listing['post_title'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">post_status</label>
                                        <input class="span8" id="normalInput" type="text" name="post[post_status]" value="<?= (isset($listing)) ? $listing['post_status'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">post_name</label>
                                        <input class="span8" id="normalInput" type="text" name="post[post_name]" value="<?= (isset($listing)) ? $listing['post_name'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">post_excerpt</label>
                                        <input class="span8" id="normalInput" type="text" name="post[post_excerpt]" value="<?= (isset($listing)) ? $listing['post_excerpt'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">to_ping</label>
                                        <input class="span8" id="normalInput" type="text" name="post[to_ping]" value="<?= (isset($listing)) ? $listing['to_ping'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">pinged</label>
                                        <input class="span8" id="normalInput" type="text" name="post[pinged]" value="<?= (isset($listing)) ? $listing['pinged'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">post_type</label>
                                        <input class="span8" id="normalInput" type="text" name="post[post_type]" value="<?= (isset($listing)) ? $listing['post_type'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">cats_id</label>
                                        <input class="span8" id="normalInput" type="text" name="post[cats_id]" value="<?= (isset($listing)) ? $listing['cats_id'] : '' ?>" />
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