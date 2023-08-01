@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.user.server.detail_ser')</li>
    </ol>
</nav>
<!-- END BREADCRUMBS -->
<div class="row justify-content-center align-items-center">
    <div class="col col-sm-10 align-self-center">
        <h2 class="text-black" style="font-weight: bold;">{{ $server_all->label }}</h5>
            <div class="container-fluid">
                <div class="row"> 
                @if ($logged_in_user->role == "admin")  
                    <div class="btn btn-info mr-2 mb-3 " style="margin-top: 1%; border-radius: 50px; width: 85px; height: 60%;">
                        <h6 class="text-white" style="font-weight: bold;"><a class="text-white" href="{{route('frontend.user.editserver' ,['id'=>$server_all->id])}}"><i class="fas fa-edit"></i>&nbsp;&nbsp;</a><a class="text-white" href="{{route('frontend.user.editserver' ,['id'=>$server_all->id])}}">@lang('labels.frontend.user.server.edit')</a></h6>
                    </div>
                @elseif ($logged_in_user->role == "user")
                    <div style="margin-top: 4%;">
                    </div>
                @endif
                </div>
                <div class="row" id="content">
                    <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-4">
                                        <div class="card-header" style="font-weight: bold;">{{ $server_all->label }}</div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.domain') :</dt>
                                                <dd class="col-md-6">
                                                    <a>{{ $server_all->ip }}</a>
                                                </dd>
                                            </dl>
                                        </li>
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6;">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.status') :</dt>
                                                @if($server_all->status =='up' && $server_all->pattern <= 2000)
                                                <p class="text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: #1adc47; color: #FFF; display: inline-block; padding: 6px 0.1px;">
                                                    <span class="fa fa-arrow-up">&nbsp;&nbsp;&nbsp;</span>@lang('labels.frontend.user.server.up')
                                                </p>
                                                @elseif ($server_all->status =='up' && $server_all->pattern >= 2000)
                                                <p class="text-center" style="font-weight: bold; border-radius: 4px; width: 100px; background: #ffc107; color: #FFF; display: inline-block; padding: 6px 0.1px;">
                                                    <span class="fas fa-exclamation">&nbsp;&nbsp;&nbsp;</span>@lang('labels.frontend.user.server.warning')
                                                </p>
                                                @else
                                                <p class="text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: red; color: #FFF; display: inline-block; padding: 6px 0.1px;">
                                                    <span class="fa fa-arrow-down">&nbsp;&nbsp;&nbsp;</span>@lang('labels.frontend.user.server.down')
                                                </p>
                                                </dd>
                                                @endif
                                            </dl>
                                        </li>
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6;">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.latency') :</dt>
                                                <dd class="col-md-6">{{ $server_all->pattern }}</dd>
                                            </dl>
                                        </li>
                                    <ul>
                                </div>
                                <div class="col-md-4">
                                        <div class="card-header" style="font-weight: bold;">@lang('labels.frontend.user.server.setting')</div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.web_ser') :</dt>
                                                <dd class="col-md-6">{{ $server_all->web_server }}</dd>
                                            </dl>
                                        </li>
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.monitoring') :</dt>
                                                @if($server_all->email =='Yes' && $server_all->sms =='Yes')
                                                <dd class="col-md-4">
                                                    <i class="fas fa-envelope-open-text fa-1x" style="color:red;" aria-hidden="true"></i>
                                                    <i class="fa fa-comments-o" style="color:green;"></i>
                                                </dd>
                                                @elseif ($server_all->email =='Yes' && $server_all->sms !='Yes')
                                                <dd class="col-md-4">
                                                    <i class="fas fa-envelope-open-text fa-1x" style="color:red;" aria-hidden="true"></i>
                                                </dd>
                                                @else
                                                <dd class="col-md-4">
                                                    <i class="fa fa-comments-o" style="color:green;"></i>
                                                </dd>
                                                @endif
                                            </dl>
                                        </li>
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.timeout') :</dt>
                                                <dd class="col-md-6">{{ $server_all->timeout }}</dd>
                                            </dl>
                                        </li>
                                    <ul>
                                </div>
                                <div class="col-4">
                                        <div class="card-header" style="font-weight: bold;">@lang('labels.frontend.user.server.status')</div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-5">@lang('labels.frontend.user.server.reg_date') :</dt>
                                                <dd class="col-md-7">{{date('d-m-Y@H:i', strtotime($server_all->created_at))}}</dd>
                                            </dl>
                                        </li>
                                        @if($log_down != '')
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-5">@lang('labels.frontend.user.server.downtime') :</dt>
                                                <dd class="col-md-7">{{date('d-m-Y@H:i', strtotime($log_down-> created_at))}}</dd>
                                                <p id="down" hidden>{{$log_down -> created_at}}</p>
                                                <dd class="col-md-7" style="margin-left: 40%;" id="downN"></dd>
                                            </dl>
                                        </li>
                                        @else
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-5">@lang('labels.frontend.user.server.downtime') :</dt>
                                                <dd class="col-md-7">{{date('d-m-Y@H:i')}}</dd>
                                                <p id="down" hidden></p>
                                                <dd class="col-md-7" style="margin-left: 40%;">( 0 hrs 0 min)</dd>
                                            </dl>
                                        </li>
                                        @endif
                                        @if($log_up != '')
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-5">@lang('labels.frontend.user.server.uptime') :</dt>
                                                <dd class="col-md-7">{{date('d-m-Y@H:i', strtotime($log_up-> created_at))}}</dd>
                                                <p id="up" hidden>{{$log_up -> created_at}}</p>
                                                <dd class="col-md-7" style="margin-left: 40%;" id="upN"></dd>
                                            </dl>
                                        </li>
                                        @endif
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-5">@lang('labels.frontend.user.server.ssl_exp') :</dt>
                                                @if($server_all->certificate_status =='valid')
                                                <dd class="col-md-7">{{date('d-m-Y@H:i', strtotime($server_all->certificate_expiration_date))}}</dd>
                                                @else($server_all->certificate_status =='invalid')
                                                <dd class="col-md-7">@lang('labels.frontend.user.server.not_app')</dd>
                                                @endif
                                            </dl>
                                        </li>
                                    <ul>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 3%;">
                                <div class="col-8">
                                        <div class="card-header" style="font-weight: bold;">@lang('labels.frontend.user.server.output')</div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-4">@lang('labels.frontend.user.server.last_pos') :</dt>
                                                @if($log_positive != '')
                                                <dd class="col-md-8">{{ $log_positive-> status_log}}</dd>
                                                @else
                                                <dd class="col-md-8"></dd>
                                                @endif
                                            </dl>
                                        </li>
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-4">@lang('labels.frontend.user.server.last_err_out') :</dt>
                                                @if($log_error != '')
                                                <dd class="col-md-8">{{ $log_error-> status_log}}</dd>
                                                @else
                                                <dd class="col-md-8"></dd>
                                                @endif
                                            </dl>
                                        </li>
                                    <ul>
                                </div>
                                <div class="col-4">
                                        <div class="card-header" style="font-weight: bold;">@lang('labels.frontend.user.server.services')</div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.api') :</dt>
                                                <dd class="col-md-6">
                                                @if($server_all->api =='up')
                                                <p class="tooltipu text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: #1adc47; color: #FFF; display: inline-block; padding: 6px 0.1px; cursor: pointer;">
                                                    {{ $server_all->api_code }}
                                                    <span class="tooltiptext">@lang('labels.frontend.user.server.api_200')</span>
                                                </p>
                                                @elseif ($server_all->api =='redirection')
                                                <p class="tooltipu text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: #ffc107; color: #FFF; display: inline-block; padding: 6px 0.1px; cursor: pointer;">
                                                    {{ $server_all->api_code }}
                                                    <span class="tooltiptext">@lang('labels.frontend.user.server.api_300')</span>
                                                </p>
                                                @else
                                                <p class="tooltipu text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: red; color: #FFF; display: inline-block; padding: 6px 0.1px; cursor: pointer;">
                                                    {{ $server_all->api_code }}
                                                    <span class="tooltiptext">@lang('labels.frontend.user.server.api_400')</span>
                                                </p>
                                                </dd>
                                                @endif
                                            </dl>
                                        </li>
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.webserver') :</dt>
                                                <dd class="col-md-6">
                                                @if($server_all->uptime_status =='up')
                                                <p class="text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: #1adc47; color: #FFF; display: inline-block; padding: 6px 0.1px;">
                                                    <span class="fa fa-arrow-up">&nbsp;&nbsp;&nbsp;</span>@lang('labels.frontend.user.server.up')
                                                </p>
                                                @else
                                                <p class="text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: red; color: #FFF; display: inline-block; padding: 6px 0.1px;">
                                                    <span class="fa fa-arrow-down">&nbsp;&nbsp;&nbsp;</span>@lang('labels.frontend.user.server.down')
                                                </p>
                                                </dd>
                                                @endif
                                                </dd>
                                            </dl>
                                        </li>
                                        <li class="list-group-item" style="border-right: 1px solid #E6E6E6; border-left: 1px solid #E6E6E6">
                                            <dl class="row">
                                                <dt class="col-md-6">@lang('labels.frontend.user.server.database') :</dt>
                                                <dd class="col-md-6">
                                                @if($server_all->database_status =='up')
                                                <p class="text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: #1adc47; color: #FFF; display: inline-block; padding: 6px 0.1px;">
                                                    <span class="fa fa-arrow-up">&nbsp;&nbsp;&nbsp;</span>@lang('labels.frontend.user.server.up')
                                                </p>
                                                @else
                                                <p class="text-center" style="font-weight: bold; border-radius: 4px; width: 90px; background: red; color: #FFF; display: inline-block; padding: 6px 0.1px;">
                                                    <span class="fa fa-arrow-down">&nbsp;&nbsp;&nbsp;</span>@lang('labels.frontend.user.server.down')
                                                </p>
                                                </dd>
                                                @endif
                                                </dd>
                                            </dl>
                                        </li>
                                    <ul>
                                </div>
                            </div>
                    </div><!-- card-body -->
                </div><!-- card -->
                <div class="row" id="content">
                    <div class="container-fluid" style="margin-bottom: 3%; margin-top: 3%; margin-right: 1%;">
                        <a href="{{ url('server/'.$server_all->id.'/metric/'.$server_all->metric.''.$server_all->disk) }}"><button type="button" class="btn btn-info" style="float: right;">@lang('labels.frontend.user.server.disk_utili')</button></a>
                        <a href="{{ url('server/'.$server_all->id.'/metric/'.$server_all->metric.''.$server_all->host_utilization) }}"><button type="button" class="btn btn-info" style="float: right; margin-right: 1%;">@lang('labels.frontend.user.server.host_utili')</button></a>
                        <a href="{{ route('frontend.user.serverlatency' ,['id'=>$server_all->id]) }}"><button type="button" class="btn btn-info" style="float: right; margin-right: 1%;">@lang('labels.frontend.user.server.laten_fil')</button></a>
                    </div>
                    <div class="container-fluid" style="margin-bottom: 3%; width: 75%;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>  
            </div>
    </div><!-- col-md-8 -->
</div>
@endsection
@section('page-js-files')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop
    
@section('page-js-script')
<!-- Latency Line Chart-->
<script type="text/javascript">
    var myChart = {
            type: 'line',
            data: {
                labels: <?php echo json_encode($date); ?>,
    // labels: month,
                datasets: [{
                    label: 'Latency',
                    backgroundColor: 'rgba(64, 224, 208, 0.3)',
                    borderColor: '	#40E0D0',
                    borderWidth: 1,
                    data: <?php echo json_encode($latency); ?>
                    }]},
            options: {
            scales: {
            yAxes: [{
                ticks: {
                beginAtZero:true
                }}],    
                },
            }

        }
    var ctx = document.getElementById('myChart').getContext('2d');
    new Chart(ctx, myChart);
</script>
<script type="text/javascript">
    var down = document.getElementById("down").innerHTML;
    var up = document.getElementById("up").innerHTML;
    n  = new Date();
    d = new Date(down);
    u = new Date(up);

    var uptime = n - u;
    var days = Math.floor(uptime / 86400000);
    var diffHrs = Math.floor((uptime % 86400000) / 3600000);
    var diffMins = Math.floor(((uptime % 86400000) % 3600000) / 60000);
    if (days == 0){
        document.getElementById("upN").innerHTML = "( " + diffHrs + " hrs " + diffMins + " mins)";
    }else {
        document.getElementById("upN").innerHTML = "(" + days + " days " + diffHrs + " hrs " + diffMins + " mins)";
    }
    
    if ( d > u ){
        var downtime = n - d;
        var days = Math.floor(downtime / 86400000);
        var diffHrs = Math.floor((downtime % 86400000) / 3600000);
        var diffMins = Math.floor(((downtime % 86400000) % 3600000) / 60000);
        if (days == 0){
            document.getElementById("downN").innerHTML = "( " + diffHrs + " hrs " + diffMins + " mins )";
        }else {
            document.getElementById("downN").innerHTML = "(" + days + " days " + diffHrs + " hrs " + diffMins + " mins)";
        }

    } else if (d < u ){
        var downtime = u - d;
        var days = Math.floor(downtime / 86400000);
        var diffHrs = Math.floor((downtime % 86400000) / 3600000);
        var diffMins = Math.floor(((downtime % 86400000) % 3600000) / 60000);
        if (days == 0){
            document.getElementById("downN").innerHTML = "( " + diffHrs + " hrs " + diffMins + " mins )";
        }else {
            document.getElementById("downN").innerHTML = "(" + days + " days " + diffHrs + " hrs " + diffMins + " mins)";
        }
    }
</script>
@stop


