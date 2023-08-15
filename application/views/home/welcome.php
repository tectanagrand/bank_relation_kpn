<!-- begin breadcrumb -->
<!-- <ol class="breadcrumb pull-right">
    <li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
</ol> -->
<!-- end breadcrumb -->
<!-- begin page-header -->
<h1 class="page-header"></h1>
<!-- end page-header -->

<!-- begin row -->
<div class="row">
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-success">
            <div class="stats-icon"><i class="fa fa-dollar-sign"></i></div>
            <div class="stats-info">
                <h4>Cash Onhand</h4>
                <p><?php
                    if (isset($service[0])) {
                        print_r(number_format($service[0], 0));
                    } else {
                        print_r('0');
                    }
                    ?></p>	
            </div>
            <div class="stats-link">
                <a href="javascript:;" onclick="getChart('PROD');">View Chart <i class="fa fa-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-info">
            <div class="stats-icon"><i class="fa fa-arrow-alt-circle-down"></i></div>
            <div class="stats-info">
                <h4>Cash Going In</h4>
                <p><?php
                    if (isset($service[1])) {
                        print_r(number_format($service[1], 0));
                    } else {
                        print_r('0');
                    }
                    ?></p>	
            </div>
            <div class="stats-link">
                <a href="javascript:;" onclick="getChart('CPO');">View Chart <i class="fa fa-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-grey-darker">
            <div class="stats-icon"><i class="fa fa-arrow-alt-circle-up"></i></div>
            <div class="stats-info">
                <h4>Cash Going Out</h4>
                <p><?php
                    if (isset($service[2])) {
                        print_r(number_format($service[2], 0));
                    } else {
                        print_r('0');
                    }
                    ?></p>	
            </div>
            <div class="stats-link">
                <a href="javascript:;" onclick="getChart('BJR');">View Chart <i class="fa fa-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
    <!-- begin col-3 -->
    <div class="col-lg-3 col-md-6">
        <div class="widget widget-stats bg-green-lighter">
            <div class="stats-icon"><i class="fa fa-balance-scale"></i></div>
            <div class="stats-info">
                <h4>Profit /Loss</h4>
                <p><?php
                    if (isset($service[3])) {
                        print_r(number_format($service[3], 0));
                    } else {
                        print_r('0');
                    }
                    ?></p>	
            </div>
            <div class="stats-link">
                <a href="javascript:;">View Detail <i class="fa fa-arrow-alt-circle-right"></i></a>
            </div>
        </div>
    </div>
    <!-- end col-3 -->
</div>
<!-- end row -->
<!-- begin row -->
<div class="row">
    <!-- begin col-8 -->
    <div class="col-lg-8">
        <!-- begin panel -->
        <div class="panel panel-success" data-sortable-id="index-1">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title" id="titleChart">Cash FLow Chart</h4>
            </div>
            <div class="panel-body">
                <div id="interactive-chart" class="height-sm"></div>
            </div>
        </div>
        <!-- end panel -->					
    </div>
    <!-- end col-8 -->
    <!-- begin col-4 -->
    <div class="col-lg-4">
        <!-- begin panel -->
        <div class="panel panel-success" data-sortable-id="index-6">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-redo"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
                    <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
                </div>
                <h4 class="panel-title">Summary</h4>
            </div>
            <div class="panel-body p-t-0">
                <div class="table-responsive">
                    <table class="table table-valign-middle">
                        <thead>
                            <tr>	
                                <th>Source</th>
                                <th>Total</th>
                                <th>Trend</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><label class="label label-success">Cash Onhand</label></td>
                                <td><?php
                                    if (isset($service[0])) {
                                        print_r(number_format($service[0], 0));
                                    } else {
                                        print_r('0');
                                    }
                                    ?><!--<span class="text-success"><i class="fa fa-arrow-up"></i></span>--></td>
                                <td><div id="sparkline-unique-visitor"></div></td>
                            </tr>
                            <tr>
                                <td><label class="label label-info">Cash Going In</label></td>
                                <td><?php
                                    if (isset($service[1])) {
                                        print_r(number_format($service[1], 0));
                                    } else {
                                        print_r('0');
                                    }
                                    ?></td>
                                <td><div id="sparkline-bounce-rate"></div></td>
                            </tr>
                            <tr>
                                <td><label class="label label-default">Cash Going Out</label></td>
                                <td><?php
                                    if (isset($service[2])) {
                                        print_r(number_format($service[2], 0));
                                    } else {
                                        print_r('0');
                                    }
                                    ?></td>
                                <td><div id="sparkline-total-page-views"></div></td>
                            </tr>
                            <tr>
                                <td><label class="label label-primary">Profit & Loss</label></td>
                                <td><?php
                                    if (isset($service[3])) {
                                        print_r(number_format($service[3], 0));
                                    } else {
                                        print_r('0');
                                    }
                                    ?></td>
                                <td><div id="sparkline-avg-time-on-site"></div></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end panel -->
    </div>
    <!-- end col-4 -->
</div>
<!-- end row -->

<script>
    $(document).ready(function () {
        Dashboard.init();
    });
</script>