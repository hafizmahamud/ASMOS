@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.user.server.add_server')</li>
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
				<h4 class="text-black" style="font-weight: bold;">@lang('labels.frontend.user.server.add_server')</h4>
					<div class="btn btn-success mr-2 mb-3" style="margin-top: 1%; border-radius: 50px; width: 120px; height: 60%;">
						<h6 class="text-white" style="font-weight: bold;"><a class="text-white" href="{{route('frontend.user.addserver')}}"><i class="fa fa-plus"></i>&nbsp;&nbsp;</a><a class="text-white" href="{{route('frontend.user.addserver')}}">@lang('labels.frontend.user.server.add_new')</a></h6>
					</div>
					<!-- <div class="btn btn-info mr-2 mb-3 " style="margin-top: 1%; border-radius: 50px; width: 115px; height: 60%;">
						<h6 class="text-white" style="font-weight: bold;"><a class="text-white" href="{{ url('update/server') }}"><i class="fas fa-sync-alt"></i>&nbsp;&nbsp;</a><a class="text-white" href="{{ url('update/server') }}">@lang('labels.frontend.user.server.update')</a></h6>
					</div> -->
					<div style="background-color: white;">
						<div style="background-color: white; ">
							<div class="pull-right" style="float: right; margin-top: -2%; margin-right: 2%;">
                                <div>
                                  	<input type="text" id="searchServer" class="form-control pull-right"
                                    	placeholder="Search">
                                </div>
							</div>
							<div class="clearfix"></div>
						</div>
						<div class="card-body" style="margin-top: 1%;">
							<table class="table table-hover table-striped demo-table-dynamic table-responsive-block dataTable no-footer"
                                id="tableServer">
								<thead>
									<tr>
										<th class="text-center" style="width: 1%;">@lang('labels.frontend.user.server.no')</th>
										<th class="text-center" style="width: 12%;">@lang('labels.frontend.user.server.label')</th>
										<th class="text-center" style="width: 18%;">@lang('labels.frontend.user.server.domain')</th>
										<th class="text-center" style="width: 13%;">@lang('labels.frontend.user.server.latency')</th>
										<th class="text-center" style="width: 15%;">@lang('labels.frontend.user.server.cert_ex')</th>
										<th class="text-center" style="width: 8%;">@lang('labels.frontend.user.server.monitoring')</th>
										<th class="text-center" style="width: 8%;">&#32</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($server_all as $server)
									<tr>
										<td class="text-center"></td>
										<td class="text-center">
											<a href="{{ route('frontend.user.serverdetail' ,['id'=>$server->id]) }}" style="color:black;">
											{{ $server->label }}
											</a>
										</td>
										<td class="text-center">{{ $server->ip }}</td>
										<td class="text-center">{{ $server->pattern }} ms</td>
										@if($server->certificate_status =='valid')
										<td class="text-center">{{date('d-m-Y@H:i', strtotime($server->certificate_expiration_date))}}</td>
										@else($server->certificate_status =='invalid')
										<td class="text-center">@lang('labels.frontend.user.server.not_app')</td>
										@endif
										<td class="text-center">
											@if($server->email =='Yes')
											<i class="fas fa-envelope-open-text fa-1x" style="color:red;" aria-hidden="true"></i>
											@endif
											@if($server->sms =='Yes')
											<i class="fa fa-comments-o" style="color:green;"></i>
											@endif
										</td>
										<td class="text-center">
											<a href="{{route('frontend.user.editserver' ,['id'=>$server->id])}}">
												<i class="fa fa-edit" style="color:orange;"></i>
												<span class="tooltip-text" style="font-weight: bold;">
													Edit
												</span>
											</a>
											@if($server->active =='Yes')
											<a href="#deactivateModal" data-toggle="modal" data-id="{{ $server->id }}" data-label="{{ $server->label }}">
												<i class="fa fa-check-square" style="color: light blue;"></i>
												<span class="tooltip-text" style="font-weight: bold;">
													Deactivate
												</span>
											</a>
											@else
											<a href="#activateModal" data-toggle="modal" data-id="{{ $server->id }}" data-label="{{ $server->label }}">
												<i class="fa fa-window-close" style="color: red;"></i>
												<span class="tooltip-text" style="font-weight: bold;">
													Activate
												</span>
											</a>
											@endif
											<a href="#deleteModal" data-toggle="modal" data-id="{{ $server->id }}" data-label="{{ $server->label }}">
												<i class="fa fa-trash" style="color:black;"></i>
												<span class="tooltip-text" style="font-weight: bold;">
													Delete
												</span>
											</a>
											<!-- deleteModal -->
											<div class="modal fade" id="deleteModal" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<h6 class="modal-title" style="font-weight: bold;">@lang('labels.frontend.user.server.delete')</h6>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<div class="modal-body">
															<p id="delete"></p>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn" data-dismiss="modal">@lang('labels.frontend.user.server.cancel')</button>
															<a href="#" class="btn btn-danger btn-cons m-b-10 pull-right" id="modalDelete"><span class="bold">@lang('labels.frontend.user.server.dele')</span>   
															</a>
														</div>
													</div>
												
												</div>
											</div>
											<!-- End deleteModal -->
											<!-- deactivateModal -->
											<div class="modal fade" id="deactivateModal" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<h6 class="modal-title" style="font-weight: bold;">@lang('labels.frontend.user.server.deactivate')</h6>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<div class="modal-body">
															<p id="deactivate"></p>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn" data-dismiss="modal">@lang('labels.frontend.user.server.cancel')</button>
															<a href="#" class="btn btn-danger btn-cons m-b-10 pull-right" id="modalDeactivate"><span class="bold">@lang('labels.frontend.user.server.deactivate')</span>   
															</a>
														</div>
													</div>
												
												</div>
											</div>
											<!-- End deactivateModal -->
											<!-- activateModal -->
											<div class="modal fade" id="activateModal" role="dialog">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<h6 class="modal-title" style="font-weight: bold;">@lang('labels.frontend.user.server.activate')</h6>
															<button type="button" class="close" data-dismiss="modal">&times;</button>
														</div>
														<div class="modal-body">
															<p id="activate"></p>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn" data-dismiss="modal">@lang('labels.frontend.user.server.cancel')</button>
															<a href="#" class="btn btn-success btn-cons m-b-10 pull-right" id="modalActivate"><span class="bold">@lang('labels.frontend.user.server.activate')</span>   
															</a>
														</div>
													</div>
												
												</div>
											</div>
											<!-- End activateModal -->
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('page-js-files')
<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">  
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="/js/maxcdn.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@stop

@section('page-js-script')
<script>
    var tableServer = $('#tableServer').DataTable({
        "bAutoWidth": false,
		"pageLength": 25,
		dom: 'Brtip',
		"order": [[ 0, "desc" ]]
    });
	$('#searchServer').keyup(function () {
		tableServer
		.search($(this).val()).
		draw();
	});

	$(document).ready(function () {
        tableServer.on('order.dt search.dt', function () {
            tableServer.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
            tableServer.cell(cell).invalidate('dom');
            });
        }).draw();
	});

	$("document").ready(function(){
		setTimeout(function(){
			$("div.alert").remove();
		}, 3000 );
	});

	$(function () {
		$('#deleteModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var label = button.data('label');
			document.getElementById("delete").innerHTML = 'Are you sure you want to delete this server "' + label + '" ?';
			$('#modalDelete').attr('href', 'server/delete/' + id);
		});
	});

	$(function () {
		$('#deactivateModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var label = button.data('label');
			document.getElementById("deactivate").innerHTML = 'Are you sure you want to deactivate this server "' + label + '" ?';
			$('#modalDeactivate').attr('href', 'server/deactivate/' + id);
		});
	});
	$(function () {
		$('#activateModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var label = button.data('label');
			document.getElementById("activate").innerHTML = 'Are you sure you want to activate this server "' + label + '" ?';
			$('#modalActivate').attr('href', 'server/activate/' + id);
		});
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
@stop
