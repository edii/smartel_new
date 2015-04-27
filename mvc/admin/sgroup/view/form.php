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
                                <input type="hidden" name="sgroup[SectionGroupID]" value="<?= $this->getParam('id') ?>" />
                                <input type="hidden" name="sgroup[TimeSaved]" value="<?= date('Y-m-d H:i:s') ?>" />
                            <?php endif; ?>
                            <!-- add -->
                            <?php if($this->getParam('method') == 'add'): ?>
                                <input type="hidden" name="sgroup[TimeCreated]" value="<?= date('Y-m-d H:i:s') ?>" />
                            <?php endif; ?>     
                                
                            
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionGroupCode</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[SectionGroupCode]" value="<?= (isset($listing)) ? $listing['SectionGroupCode'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">OwnerID</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[OwnerID]" value="<?= (isset($listing)) ? $listing['OwnerID'] : '' ?>" />
                                    </div>
                                </div>
                            </div>    
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">UserID</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[UserID]" value="<?= (isset($listing)) ? $listing['UserID'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">PermAll</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[PermAll]" value="<?= (isset($listing)) ? $listing['PermAll'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionGroupName</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[SectionGroupName]" value="<?= (isset($listing)) ? $listing['SectionGroupName'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">AccessGroups</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[AccessGroups]" value="<?= (isset($listing)) ? $listing['AccessGroups'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionGroupType</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[SectionGroupType]" value="<?= (isset($listing)) ? $listing['SectionGroupType'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionGroupPosition</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[SectionGroupPosition]" value="<?= (isset($listing)) ? $listing['SectionGroupPosition'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionGroupModule</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[SectionGroupModule]" value="<?= (isset($listing)) ? $listing['SectionGroupModule'] : '' ?>" />
                                    </div>
                                </div>
                            </div>
                                
                                
                            <div class="form-row row-fluid">
                                <div class="span12">
                                    <div class="row-fluid">
                                        <label class="form-label span4" for="normal">SectionGroupViewOptions</label>
                                        <input class="span8" id="normalInput" type="text" name="sgroup[SectionGroupViewOptions]" value="<?= (isset($listing)) ? $listing['SectionGroupViewOptions'] : '' ?>" />
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