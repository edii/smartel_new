<?php if(!$validate): ?>
    <!-- error fatall or other -->
<?php else: ?>

<!--Body content-->
<div id="content" class="clearfix">
    <div class="contentwrapper"><!--Content wrapper-->

        <div class="heading">

            <h3>Створення користувача</h3>                    

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
                <li class="active">Створення користувача</li>
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
                                <input type="hidden" name="users[userID]" value="<?= $this->getParam('id') ?>" />
                                <input type="hidden" name="users[TimeSaved]" value="<?= date('Y-m-d H:i:s') ?>" />
                            <?php endif; ?>
                            <!-- add -->
                            <?php if($this->getParam('method') == 'add'): ?>
                                <input type="hidden" name="users[TimeCreated]" value="<?= date('Y-m-d H:i:s') ?>" />
                            <?php endif; ?>     

                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">OwnerID</label>
                                        <input class="span8" id="normalInput" type="text" name="users[OwnerID]" value="<?= (isset($listing)) ? $listing['OwnerID'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">adminID</label>
                                        <input class="span8" id="normalInput" type="text" name="users[adminID]" value="<?= (isset($listing)) ? $listing['adminID'] : '' ?>" />
                                    </div>
                                </div>
                            </div> 
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">Ім'я</label>
                                        <input class="span8" id="normalInput" type="text" name="users[userName]" value="<?= (isset($listing)) ? $listing['userName'] : '' ?>" />
                                    </div>
                                </div>
                            </div>    
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">Логін</label>
                                        <input class="span8" id="normalInput" type="text" name="users[login]" value="<?= (isset($listing)) ? $listing['login'] : '' ?>" />
                                    </div>
                                </div>
                            </div> 
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">Email</label>
                                        <input class="span8" id="normalInput" type="text" name="users[email]" value="<?= (isset($listing)) ? $listing['email'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">userFields</label>
                                        <input class="span8" id="normalInput" type="text" name="users[userFields]" value="<?= (isset($listing)) ? $listing['userFields'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">userParentID</label>
                                        <input class="span8" id="normalInput" type="text" name="users[userParentID]" value="<?= (isset($listing)) ? $listing['userParentID'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">userLanguage</label>
                                        <input class="span8" id="normalInput" type="text" name="users[userLanguage]" value="<?= (isset($listing)) ? $listing['userLanguage'] : '' ?>" />
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