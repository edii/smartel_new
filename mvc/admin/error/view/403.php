<?php if($validate): ?>
<div class="container-fluid">

    <div class="errorContainer">
        <div class="page-header">
            <h1 class="center">403 <small>error</small></h1>
        </div>

        <h2 class="center">Access to this page is forbidden</h2>

        <div class="center">
            <a href="javascript: history.go(-1)" class="btn" style="margin-right:10px;"><span class="icon16 icomoon-icon-arrow-left-10"></span>Go back</a>
            <a href="dashboard.html" class="btn"><span class="icon16 icomoon-icon-screen"></span>Dashboard</a>
        </div>

    </div>

</div>
<?php endif; ?>