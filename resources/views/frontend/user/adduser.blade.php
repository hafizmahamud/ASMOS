@extends('frontend.layouts.app')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.user.log.users')</li>
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
                <h4 class="text-black" style="font-weight: bold;">@lang('labels.frontend.user.add_user.users')</h4>
                    <div class="btn btn-success mr-2 mb-3" style="margin-top: 1%; border-radius: 20px; width: 120px; height: 30%;">
                        <h6 class="text-white" style="font-weight: bold;"><a class="text-white" href="{{route('frontend.auth.register')}}"><i class="fa fa-plus"></i>&nbsp;&nbsp;</a><a class="text-white" href="{{route('frontend.auth.register')}}">@lang('labels.frontend.user.server.add_new')</a></h6>
                    </div>
                    <div style="background-color: white;">
                        <div style="background-color: white;">
							<div class="pull-right" style="float: right; margin-top: -2%; margin-right: 1%;">
                                <div>
                                  	<input type="text" id="searchUser" class="form-control pull-right"
                                    	placeholder="Search">
                                </div>
							</div>
							<div class="clearfix"></div>
						</div>
                        <div class="card-body" style="margin-top: 1%;">            
                            <table class="table table-hover table-striped demo-table-dynamic table-responsive-block dataTable no-footer"
                                id="tableUser">
                                <thead>
                                    <tr>
                                        <!--class="d-none d-lg-table-cell"-->
                                        <th class="text-center" style="width: 1%;">@lang('labels.frontend.user.server.no')</th>
                                        <th class="text-center" style="width: 15%;">@lang('labels.frontend.user.add_user.users')</th>
                                        <th class="text-center" style="width: 5%;">@lang('labels.frontend.user.add_user.role')</th>
                                        <th class="text-center" style="width: 15%;">@lang('labels.frontend.user.add_user.email')</th>
                                        <th class="text-center" style="width: 5%;">@lang('labels.frontend.user.add_user.mobile')</th>
                                        <th class="text-center" style="width: 18%;">@lang('labels.frontend.user.server.add_server')</th>
                                        <th class="text-center" style="width: 5%;">&#32</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user_all as $user)
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center">
                                            <a href="{{route('frontend.user.editUser' ,['id'=>$user[0]->id])}}" style="color:black;">
											{{ $user[0]->first_name }} {{ $user[0]->last_name }}
											</a>
                                        </td>
                                        <td class="text-center">{{ $user[0]->role }}</td>
                                        <td class="text-center">{{ $user[0]->email }}</td>
                                        <td class="text-center">{{ $user[0]->mobile }}</td>
                                        <td class="text-center">
                                        @foreach ($user as $u)
                                        {{ $u->label }} , 
                                        @endforeach
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('frontend.user.editUser' ,['id'=>$user[0]->id])}}">
                                                <i class="fa fa-edit" style="color:orange;"></i>
                                                <span class="tooltip-text" style="font-weight: bold;">
													Edit
												</span>
                                            </a>
                                            @if($user[0]->active == 1)
											<a href="#deactivateModal" data-toggle="modal" data-id="{{ $user[0]->id }}" data-label="{{ $user[0]->first_name }} {{ $user[0]->last_name }}">
												<i class="fa fa-check-square" style="color: light blue;"></i>
												<span class="tooltip-text" style="font-weight: bold;">
													Deactivate
												</span>
											</a>
											@else
											<a href="#activateModal" data-toggle="modal" data-id="{{ $user[0]->id }}" data-label="{{ $user[0]->first_name }} {{ $user[0]->last_name }}">
												<i class="fa fa-window-close" style="color: red;"></i>
												<span class="tooltip-text" style="font-weight: bold;">
													Activate
												</span>
											</a>
											@endif
                                            <a href="#deleteModal" data-toggle="modal" data-id="{{ $user[0]->id }}" data-username="{{ $user[0]->first_name }} {{ $user[0]->last_name }}">
												<i class="fa fa-trash" style="color:black;"></i>
                                                <span class="tooltip-text" style="font-weight: bold;">
													Delete
												</span>
											</a>
                                        </td>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title" style="font-weight: bold;">@lang('labels.frontend.user.profile.del_user')</h6>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p id="element"></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn" data-dismiss="modal">@lang('labels.frontend.user.server.cancel')</button>
                                                            <a href="#" class="btn btn-danger btn-cons m-b-10 pull-right" id="modalDelete"><span class="bold">@lang('labels.frontend.user.server.dele')</span>   
                                                            </a>
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                            </div>
                                            <!-- Delete Modal -->
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
                                    </tr>
                                    @endforeach
									@foreach ($user_exclude as $user_n)
                                    <tr>
                                        <td class="text-center"></td>
                                        <td class="text-center">
                                            <a href="{{route('frontend.user.editUser' ,['id'=>$user_n->id])}}" style="color:black;">
											{{ $user_n->first_name }} {{ $user_n->last_name }}
											</a>
                                        </td>
                                        <td class="text-center">{{ $user_n->role }}</td>
                                        <td class="text-center">{{ $user_n->email }}</td>
                                        <td class="text-center">{{ $user_n->mobile }}</td>
                                        <td class="text-center">No Data
                                        </td>
                                        <td class="text-center">
                                            <a href="{{route('frontend.user.editUser' ,['id'=>$user_n->id])}}">
                                                <i class="fa fa-edit" style="color:orange;"></i>
                                                <span class="tooltip-text" style="font-weight: bold;">
													Edit
												</span>
                                            </a>
                                            @if($user_n->active == 1)
											<a href="#deactivateModal" data-toggle="modal" data-id="{{ $user_n->id }}" data-label="{{ $user_n->first_name }} {{ $user_n->last_name }}">
												<i class="fa fa-check-square" style="color: light blue;"></i>
												<span class="tooltip-text" style="font-weight: bold;">
													Deactivate
												</span>
											</a>
											@else
											<a href="#activateModal" data-toggle="modal" data-id="{{ $user_n->id }}" data-label="{{ $user_n->first_name }} {{ $user_n->last_name }}">
												<i class="fa fa-window-close" style="color: red;"></i>
												<span class="tooltip-text" style="font-weight: bold;">
													Activate
												</span>
											</a>
											@endif
                                            <a href="#deleteModal" data-toggle="modal" data-id="{{ $user_n->id }}" data-username="{{ $user_n->first_name }} {{ $user_n->last_name }}">
												<i class="fa fa-trash" style="color:black;"></i>
                                                <span class="tooltip-text" style="font-weight: bold;">
													Delete
												</span>
											</a>
                                        </td>
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal" role="dialog">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title" style="font-weight: bold;">@lang('labels.frontend.user.profile.del_user')</h6>
                                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p id="element"></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn" data-dismiss="modal">@lang('labels.frontend.user.server.cancel')</button>
                                                            <a href="#" class="btn btn-danger btn-cons m-b-10 pull-right" id="modalDelete"><span class="bold">@lang('labels.frontend.user.server.dele')</span>   
                                                            </a>
                                                        </div>
                                                    </div>
                                                
                                                </div>
                                            </div>
                                            <!-- Delete Modal -->
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
    var tableUser = $('#tableUser').DataTable({
        "bAutoWidth": false,
		"pageLength": 25,
        "responsive": true,
		dom: 'Brtip',
    });
	$('#searchUser').keyup(function () {
		tableUser
			.search($(this).val()).
		draw();
	});

    $(function () {
		$('#deleteModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var username = button.data('username');
			document.getElementById("element").innerHTML = 'Are you sure you want to delete this user "' + username + '" ?';
			$('#modalDelete').attr('href', 'adduser/delete/' + id);
		});
	});
    $(function () {
		$('#deactivateModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var label = button.data('label');
			document.getElementById("deactivate").innerHTML = 'Are you sure you want to deactivate this user "' + label + '" ?';
			$('#modalDeactivate').attr('href', 'user/deactivate/' + id);
		});
	});
	$(function () {
		$('#activateModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var label = button.data('label');
			document.getElementById("activate").innerHTML = 'Are you sure you want to activate this user "' + label + '" ?';
			$('#modalActivate').attr('href', 'user/activate/' + id);
		});
	});

    $("document").ready(function(){
        setTimeout(function(){
            $("div.alert").remove();
        }, 3000 );
    });

    $(document).ready(function () {
        tableUser.on('order.dt search.dt', function () {
            tableUser.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
            tableUser.cell(cell).invalidate('dom');
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
@stop
