<!-- begin sidebar scrollbar -->
<div data-scrollbar="true" data-height="100%">
    <!-- begin sidebar user -->
    <ul class="nav">
        <li class="nav-profile">
            <a href="javascript:;" data-toggle="nav-profile">
                <div class="cover with-shadow"></div>
                <div class="image">
                    <img src="<?php echo base_url() ?>/assets/img/user/user-13.jpg" alt="" />
                </div>
                <div class="info">
                    <b class="caret pull-right"></b><?php echo $this->session->userdata('username'); ?><small><?php echo $this->session->userdata('DEPARTMENT'); ?></small>
                </div>
                <a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
            </a>
        </li>
        <li>
            <ul class="nav nav-profile">
                <li><a href="javascript:;"><i class="fa fa-cog"></i> Settings</a></li>
                <li><a href="javascript:;"><i class="fa fa-question-circle"></i> Helps</a></li>
            </ul>
        </li>
    </ul>
    <!-- end sidebar user -->
    <!-- begin sidebar nav -->
    <ul class="nav">
        <li class="nav-header">Navigation</li>
        <li id="home">
            <a href="<?php site_url(); ?>">
                <i class="fa fa-th-large"></i><span>Dashboard</span> 
            </a>
        </li>
        <?php echo $Menu; ?>
        <!--        <li class="has-sub active">
                    <a href="javascript:;">
                        <b class="caret"></b>
                        <i class="fa fa-th-large"></i>
                        <span>Dashboard</span>
                    </a>
                    <ul class="sub-menu">
                        <li class="active"><a href="<?php echo site_url() ?>/Dashboardproduction">Cash In</a></li>
                        <li class="active"><a href="<?php echo site_url() ?>/Dashboardbrondol">Cash Out</a></li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a href="javascript:;">
                        <b class="caret"></b>
                        <i class="fa fa-list"></i>
                        <span>Cashflow</span>
                    </a>
                    <ul class="sub-menu">
                        <li><a href="<?php echo site_url() ?>/Presensi">Cash In</a></li>
                        <li><a href="<?php echo site_url() ?>/AutoPresence">Cash Out</a></li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a href="javascript:;">
                        <b class="caret"></b>
                        <i class="fa fa-list"></i>
                        <span>Master Data</span>
                    </a>
                    <ul class="sub-menu">
                        <li class="has-sub">
                            <a href="<?php echo site_url() ?>/Company"> Company </a>
                        </li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a href="javascript:;">
                        <b class="caret"></b>
                        <i class="fa fa-list"></i>
                        <span>Report</span>
                    </a>
                    <ul class="sub-menu">
                        <li class="has-sub">
                            <a href="<?php echo site_url() ?>/AllReport"> All report </a>
                        </li>
                    </ul>
                </li>
                <li class="has-sub">
                    <a href="javascript:;">
                        <b class="caret"></b>
                        <i class="fa fa-cog fa-spin"></i>
                        <span>Setting</span>
                    </a>
                    <ul class="sub-menu">
                        <li class="has-sub">
                            <a href="<?php echo site_url() ?>/ExtSystem"> External System</a>
                        </li>
                    </ul>
                </li>-->
        <!--
                <li class="active"><a href="<?php echo site_url() ?>/Dashboardproduction">Harvesting Capacity</a></li>
        <li class="has-sub">
                <a href="javascript:;">
                <b class="caret"></b>
                    <i class="fa fa-money-bill-alt"></i>
                    <span>Bank</span>
            </a>
                <ul class="sub-menu">
                    <li class="active"><a href="<?php echo site_url() ?>/payment">Voucher</a></li>
                    <li><a href="index_v2.html">Sales</a></li>
                </ul>
        </li>
        
        <li class="has-sub">
                <a href="javascript:;">
                <b class="caret"></b>
                    <i class="fa fa-list"></i>
                    <span>Master Data</span>
            </a>
                <ul class="sub-menu">
                                        <li class="has-sub">
                                                <a href="javascript:;">
                                                    <b class="caret"></b>
                                                    Employee
                                                </a>
                                                <ul class="sub-menu">
                                                        <li><a href="<?php echo site_url() ?>/Employee">Employee Master</a></li>
                                                        <li><a href="javascript:;">Menu 3.2</a></li>
                                                </ul>
                                        </li>
                </ul>
        </li>
                                
        -->					
        <!-- begin sidebar minify button -->
        <li><a href="javascript:;" class="sidebar-minify-btn" data-click="sidebar-minify"><i class="fa fa-angle-double-left"></i></a></li>
        <!-- end sidebar minify button -->
    </ul>
    <!-- end sidebar nav -->
</div>
<!-- end sidebar scrollbar -->