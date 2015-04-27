<?php if(!$validate): ?>
    <!-- error fatall or other -->
<?php else: ?>

<div id="header">

    <div class="navbar">
        <div class="navbar-inner">
          <div class="container-fluid">
            <a class="brand" href="dashboard.html">Rotor (v2.0).<span class="slogan">admin</span></a>
            <div class="nav-no-collapse">
                <ul class="nav">
                    <li class="active"><a href="dashboard.html"><span class="icon16 icomoon-icon-screen-2"></span> <span class="txt">Dashboard</span></a></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="icon16 icomoon-icon-cog"></span><span class="txt"> Settings</span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="menu">
                                <ul>
                                    <li>                                                    
                                        <a href="#"><span class="icon16 icomoon-icon-equalizer"></span>Site config</a>
                                    </li>
                                    <li>                                                    
                                        <a href="#"><span class="icon16 icomoon-icon-wrench"></span>Plugins</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="icon16 icomoon-icon-image-2"></span>Themes</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="icon16 icomoon-icon-envelop"></span><span class="txt">Messages</span><span class="notification">8</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="menu">
                                <ul class="messages">    
                                    <li class="header"><strong>Messages</strong> (10) emails and (2) PM</li>
                                    <li>
                                       <span class="icon"><span class="icon16 icomoon-icon-user-plus"></span></span>
                                        <span class="name"><a data-toggle="modal" href="#myModal1"><strong>Sammy Morerira</strong></a><span class="time">35 min ago</span></span>
                                        <span class="msg">I have question about new function ...</span>
                                    </li>
                                    <li>
                                       <span class="icon avatar"><img src="/style/admin/image/avatar.jpg" alt="" /></span>
                                        <span class="name"><a data-toggle="modal" href="#myModal1"><strong>George Michael</strong></a><span class="time">1 hour ago</span></span>
                                        <span class="msg">I need to meet you urgent please call me ...</span>
                                    </li>
                                    <li>
                                        <span class="icon"><span class="icon16 icomoon-icon-envelop"></span></span>
                                        <span class="name"><a data-toggle="modal" href="#myModal1"><strong>Ivanovich</strong></a><span class="time">1 day ago</span></span>
                                        <span class="msg">I send you my suggestion, please look and ...</span>
                                    </li>
                                    <li class="view-all"><a href="#">View all messages <span class="icon16 icomoon-icon-arrow-right-8"></span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>

                <ul class="nav pull-right usernav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <span class="icon16 icomoon-icon-bell"></span><span class="notification">3</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="menu">
                                <ul class="notif">
                                    <li class="header"><strong>Notifications</strong> (3) items</li>
                                    <li>
                                        <a href="#">
                                            <span class="icon"><span class="icon16 icomoon-icon-user-plus"></span></span>
                                            <span class="event">1 User is registred</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="icon"><span class="icon16 icomoon-icon-bubble-3"></span></span>
                                            <span class="event">Jony add 1 comment</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#">
                                            <span class="icon"><span class="icon16 icomoon-icon-new"></span></span>
                                            <span class="event">admin Julia added post with a long description</span>
                                        </a>
                                    </li>
                                    <li class="view-all"><a href="#">View all notifications <span class="icon16 icomoon-icon-arrow-right-8"></span></a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
                            <img src="/style/admin/image/avatar.jpg" alt="" class="image" /> 
                            <span class="txt"><?= $_session['email'] ?></span>
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="menu">
                                <ul>
                                    <li>
                                        <a href="#"><span class="icon16 icomoon-icon-user-plus"></span>Edit profile</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="icon16 icomoon-icon-bubble-2"></span>Approve comments</a>
                                    </li>
                                    <li>
                                        <a href="#"><span class="icon16 icomoon-icon-plus"></span>Add user</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="/<?= _request_uri ?>/home/logout/?logout=true"><span class="icon16 icomoon-icon-exit"></span><span class="txt"> Logout</span></a></li>
                </ul>
            </div><!-- /.nav-collapse -->
          </div>
        </div><!-- /navbar-inner -->
      </div><!-- /navbar --> 

</div><!-- End #header --> 
    

<?php endif; ?>