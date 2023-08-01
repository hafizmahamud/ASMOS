@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.serverdetail' ,['id'=>$server_all->id]) }}">@lang('labels.frontend.user.server.detail_ser')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.user.server.latency_ser')</li>
    </ol>
</nav>
<!-- END BREADCRUMBS -->
<div class="row justify-content-center align-items-center">
    <div class="col col-sm-10 align-self-center">
        <h2 class="text-black" style="font-weight: bold; margin-bottom: 2%;">{{ $server_all->label }}</h5>
        <div class="container-fluid">
            <form method="post" action="{{ url('server/filter/latency') }}">
                @method('PATCH')
                @csrf
                <div class="row" style="width:600px; margin:0 auto;">
                @if($from =='')
                    <div class="col-12 col-md-4">
                        <div class="form-group input-daterange">
                            <input onchange="startDate()" type="text" name="from_date" id="start" readonly class="form-control" required/>
                        </div>
                    </div>
                @else
                    <div class="col-12 col-md-4">
                        <div class="form-group input-daterange">
                            <input onchange="startDate()" type="text" name="from_date" id="start" readonly value="{{ $from }}" class="form-control" required/>
                        </div>
                    </div>
                @endif
                        <p>@lang('labels.frontend.user.server.to')</p>
                @if($to =='')
                    <div class="col-12 col-md-4">
                        <div class="form-group input-daterange">
                            <input onchange="checkingEndDate()" type="text"  name="to_date" id="end" readonly class="form-control" required/>
                        </div>
                    </div>
                @else
                    <div class="col-12 col-md-4">
                        <div class="form-group input-daterange">
                            <input onchange="checkingEndDate()" type="text"  name="to_date" id="end" readonly value="{{ $to }}" class="form-control" required/>
                        </div>
                    </div>
                @endif
                    <input type="hidden" name="id" value="{{ $server_all->id }}">
                    <input onchange="allowsubmit()" type="hidden" readonly class="form-control"/>
                    <button type="submit" name="filter" style="height: 40px; margin-right: 1%; width: 70px;" id="filter" class="btn btn-info btn-sm">@lang('labels.frontend.user.server.filter')</button>
                    <a href="{{ route('frontend.user.serverlatency' ,['id'=>$server_all->id]) }}"><button style="height: 40px; width: 70px;" type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">@lang('labels.frontend.user.server.clear')</button></a>
                </div>
            </form>
            <div class="row justify-content-center align-items-center" id="content" style="margin-bottom: 3%; margin-top: 3%">
                <div class="col col-sm-8 align-self-center">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
            <!-- The Modal -->
            <div class="modal" id="myModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-body">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <p class="text-center" style="margin-top: 3%" id="alert"></p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        </div>
    </div>      
</div>
@endsection


@section('page-js-files')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
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

<script>
$(document).ready(function(){

    var date = new Date();

        $('.input-daterange').datepicker({
        todayBtn: 'linked',
        format: 'yyyy-mm-dd',
        autoclose: true
        })

});
</script>

<script>
document.getElementById("filter").disabled = true;

    function checkingEndDate() {
        var startDate = document.getElementById('start').value;
        var endDate = document.getElementById('end').value;

        if (startDate == '') {
            $("#myModal").modal('show');
            document.getElementById("alert").innerHTML = 'Please select start date first.';
            document.getElementById('end').value = '';
        }
        if (endDate < startDate) {
            $("#myModal").modal('show');
            document.getElementById("alert").innerHTML = 'End date must be greater than start date.';
            document.getElementById('end').value = '';
        }
        allowsubmit();
    }

    function startDate() {
        var startDate = document.getElementById('start').value;
        var endDate = document.getElementById('end').value;

        if (endDate != '') {
            if (endDate < startDate) {
                $("#myModal").modal('show');
                document.getElementById("alert").innerHTML = 'Start date must be lower than end date.';
                document.getElementById('start').value = '';
            }
        }
        allowsubmit();
    }

    function allowsubmit() {
        var startDate = document.getElementById('start').value;
        var endDate = document.getElementById('end').value;

        if (endDate == '' && startDate == '') {
            document.getElementById("filter").disabled = true;
        } else if (endDate != '' && startDate == ''){
            document.getElementById("filter").disabled = true;
        } else if (endDate == '' && startDate != ''){
            document.getElementById("filter").disabled = true;
        } else {
            document.getElementById("filter").disabled = false;
        }
    }
</script>
@stop


    

