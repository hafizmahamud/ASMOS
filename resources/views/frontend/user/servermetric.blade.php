@extends('frontend.layouts.appservermetric')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1"> -->
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.dashboard') }}">@lang('labels.frontend.auth.home')</a></li>
    <li class="breadcrumb-item"><a href="{{ route('frontend.user.serverdetail' ,['id'=>$server_all->id]) }}">@lang('labels.frontend.user.server.detail_ser')</a></li>
    <li class="breadcrumb-item active">@lang('labels.frontend.user.server.metric_ser')</li>
    </ol>
</nav>
<!-- END BREADCRUMBS -->
<div class="row justify-content-center align-items-center">
    <div class="col col-sm-10 align-self-center">
        <h6 align="center" class="text-black" style="font-weight: bold;font-size: 30px; margin-bottom: 1%; margin-top: 2%;">@lang('labels.frontend.user.server.per_mon') {{ $server_all->label }}</h6>
        <div class="container-2">
        </div>
        <div class="col-md-12">
            <div id="app" ></div>
        </div>
    </div>
</div>
@endsection



@section('page-js-files')
<script type="text/javascript" src="/js/bundle.js"></script>
<script type="text/javascript" src="js/2a4bc48691ba3c042ebb.worker.js"></script>
<script type="text/javascript" src="/js/config.js"></script>
@stop 


@section('page-js-script')

@stop 