@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
		<li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
		<li class="breadcrumb-item active">@lang('labels.frontend.user.log.log')</li>
    </ol>
</nav>
<!-- END BREADCRUMBS -->
<div class="card-body">
	
	<a href="#" class="crunchify-top" style="margin-bottom: 3%; font-weight: bold;">
		<span class="fa fa-arrow-up"></span>
	</a>

	<div class="row justify-content-center align-items-center">
		@include('includes.partials.messages')
		<div class="col-lg-10 m-b-10 d-flex flex-column">
			<div class="table-responsive">
				<h4 class="text-black" style="font-weight: bold;">@lang('labels.frontend.user.log.log')</h4>
					<!-- Modal -->
					<div class="modal fade" id="deleteModal" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h6 class="modal-title" style="font-weight: bold;">@lang('labels.frontend.user.log.delete')</h6>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body">
									<p>@lang('labels.frontend.user.log.del_log')</p>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn" data-dismiss="modal">@lang('labels.frontend.user.server.cancel')</button>
									<a href="{{ url('log/delete') }}" class="btn btn-danger btn-cons m-b-10 pull-right" id="modalDelete"><span class="bold">@lang('labels.frontend.user.server.dele')</span>   
									</a>
								</div>
							</div>
						</div>
					</div>
					<!-- END Modal -->
					<form method="post" id="myForm" action="{{ url('/log/filter') }}">
						@method('PATCH')
						@csrf
						<div class="row">
						@if($start =='')
							<div class="col-4 col-md-2" style="margin-left: 1%; margin-top: 2%;">
								<div class="form-group input-daterange">
									<label style="font-weight: bold;">@lang('labels.frontend.user.server.from')</label>
									<input onchange="startDate()" type="text" name="from_date" id="start" readonly class="form-control" required/>
								</div>
							</div>
						@else
							<div class="col-4 col-md-2" style="margin-left: 1%; margin-top: 2%;">
								<div class="form-group input-daterange">
									<label style="font-weight: bold;">@lang('labels.frontend.user.server.from')</label>
									<input onchange="startDate()" type="text" name="from_date" value="{{$start}}" id="start" readonly class="form-control" required/>
								</div>
							</div>
						@endif
						@if($end =='')
							<div class="col-4 col-md-2" style="margin-top: 2%;">
								<div class="form-group input-daterange">
									<label style="font-weight: bold;">@lang('labels.frontend.user.server.to')</label>
									<input onchange="checkingEndDate()" type="text"  name="to_date" id="end" readonly class="form-control" required/>
									<input type="hidden" name="id" value="">
								</div>
							</div>
						@else
							<div class="col-4 col-md-2" style="margin-top: 2%;">
								<div class="form-group input-daterange">
									<label style="font-weight: bold;">@lang('labels.frontend.user.server.to')</label>
									<input onchange="checkingEndDate()" type="text"  name="to_date" value="{{$end}}" id="end" readonly class="form-control" required/>
									<input type="hidden" name="id" value="">
								</div>
							</div>
						@endif
							<div class="col-4 col-md-2" style="margin-top: 4.5%;">
								<div class="form-group">
									<input onchange="allowsubmit()" type="hidden" readonly class="form-control"/>
									<a href="{{ url('log') }}"><button style="height: 40px; width: 70px;" type="button" name="refresh" id="refresh" class="btn btn-warning btn-sm">@lang('labels.frontend.user.server.clear')</button></a>
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
						<!-- </div> -->
					</form>
						<div class="col-4 col-md-2" style="margin-left: 14%; margin-top: 2%;">
							<div class="form-group">
								<label style="font-weight: bold;">@lang('labels.frontend.user.server.add_server')</label>
								<select id="dropdownServer" class="form-control">
									<option value=''>Select Server</option>
								@foreach ($server_all as $server)	
									<option value='{{ $server->label}}'>{{ $server->label}}</option>
								@endforeach
								</select>
							</div>
						</div>
						<div class="col-4 col-md-2" style="margin-top: 2%;">
							<div class="form-group">
								<label style="font-weight: bold;">@lang('labels.frontend.user.log.services')</label>
								<select id="dropdownServices" class="form-control">
									<option value=''>Select Service</option>
									<option value='Network'>@lang('labels.frontend.user.server.network')</option>
									<option value='Web Server'>@lang('labels.frontend.user.server.webserver')</option>
									<option value='Api'>@lang('labels.frontend.user.server.api')</option>
									<option value='Database'>@lang('labels.frontend.user.server.database')</option>
									<option value='SSL Cert'>@lang('labels.frontend.user.server.ssl_certi')</option>
								</select>
							</div>
						</div>
					</div>
					<div>
						<div class="col-4 col-md-2" style="margin-left:1%; margin-top: 2%;">
							<div class="form-group">
								<input type="text" id="searchLog" class="form-control" placeholder="Search">
							</div>
						</div>
						<div class="pull-right" style="float: right; margin-right: 2%;">
							<div>
								<div class="btn btn-danger mr-2 mb-3" style="border-radius: 50px; width: 125px; height: 60%;">
									<h6 class="text-white" style="font-weight: bold;"><a class="text-white" href="#deleteModal" data-toggle="modal" data-id="log"><i class="fa fa-trash"></i>&nbsp;&nbsp;</a><a class="text-white" href="#deleteModal" data-toggle="modal" data-id="log">@lang('labels.frontend.user.log.clear_log')</a></h6>
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>
                    <div class="card-body">
						<table class="table table-hover table-striped demo-table-dynamic table-responsive-block dataTable no-footer"
                            id="tableLog">
							<thead style="margin-top: 2%;">
								<tr>
									<!--class="d-none d-lg-table-cell"-->
									<th class="text-center" style="width: 1%;">@lang('labels.frontend.user.server.no')</th>
									<th class="text-center" style="width: 10%;">@lang('labels.frontend.user.server.add_server')</th>
									<th class="text-center" style="width: 5%;">@lang('labels.frontend.user.log.date')</th>
									<th class="text-center" style="width: 5%;">@lang('labels.frontend.user.log.services')</th>
									<th class="text-center" style="width: 4%;">@lang('labels.frontend.user.log.status')</th>
									<th class="text-center" style="width: 18%;">@lang('labels.frontend.user.log.message')</th>
								</tr>
							</thead>
							<tbody>
							@foreach ($log_all as $log)
								<tr>
									<td class="text-center"></td>
									<td class="text-center">{{ $log->label}}</td>
									<td class="text-center">{{ date('d-m-Y@H:i', strtotime($log->created_at))}}</td>
									<td class="text-center">{{ $log->services }}</td>
									<td class="text-center">{{ $log->status }}</td>
									<td>{{ $log->status_log }}</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('page-js-files')
<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" rel="stylesheet">  
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" charset="utf8" src="/js/zipfile.js"></script>
<script type="text/javascript" charset="utf8" src="/js/pdfmake.js"></script>
<script type="text/javascript" charset="utf8" src="/js/pdfmakefont.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
@stop

@section('page-js-script')
<script>
    var tableLog = $('#tableLog').DataTable({
		"bAutoWidth": false,
		"pageLength": 25,
		"order": [[ 2, "desc" ]],
		dom: 'Brtip',
		"scrollCollapse": true,
		buttons: [
            {
                extend: 'excel',
                title: 'System Log Server',
            },
            {
                extend: 'pdf',
                title: 'System Log Server',
            },
            {
                extend: 'print',
                title: 'System Log Server',
            }
        ]
    });
	$('#searchLog').keyup(function () {
		tableLog
			.search($(this).val()).
		draw();
	});
	$('#dropdownServer').on('change', function () {
		tableLog
			.columns(1)
			.search(this.value).
		draw();
	});
	$('#dropdownServices').on('change', function () {
		tableLog
			.columns(3)
			.search(this.value).
		draw();
	});

	$("document").ready(function(){
		setTimeout(function(){
			$("div.alert").remove();
		}, 3000 );
	});

	$(document).ready(function () {
        tableLog.on('order.dt search.dt', function () {
            tableLog.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
            tableLog.cell(cell).invalidate('dom');
            });
        }).draw();
	});
	
</script>

<script>            
	jQuery(document).ready(function() {
		var offset = 220;
		var duration = 500;
		jQuery(window).scroll(function() {
			if (jQuery(this).scrollTop() > offset) {
				jQuery('.crunchify-top').fadeIn(duration);
			} else {
				jQuery('.crunchify-top').fadeOut(duration);
			}
		});
 
		jQuery('.crunchify-top').click(function(event) {
			event.preventDefault();
			jQuery('html, body').animate({scrollTop: 0}, duration);
			return false;
		})
	});
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
            document.getElementById("myForm").submit();
        }
    }
</script>
@stop
