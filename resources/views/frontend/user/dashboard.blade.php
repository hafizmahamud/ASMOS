@extends('frontend.layouts.appdashboard')

@section('title', app_name() . ' | ' . __('navs.frontend.dashboard') )

@section('content')
<!-- START BREADCRUMBS -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-alt">
    <li class="breadcrumb-item">@lang('labels.frontend.auth.home')</li>
    </ol>
</nav>
<!-- END BREADCRUMBS -->
<body id="test" onload="test(this);">
    <div class="row justify-content-center align-items-center">
        @include('includes.partials.messages')
        <div class="col-lg-10 m-b-10 d-flex flex-column">
            <div class="row menu">
            </div>
            <div class="row menu" style="margin-top: 2%;">
                <div class="col d-flex">
                    <hgroup class="hgroup" style="float:right;">
                        <h6 class="text-white" style="font-weight: bold;"><i class="fa fa-stop condition"></i>Critical</h6>
                    </hgroup>
                    <hgroup class="hgroup" style="margin-left: 6%;">
                        <h6 class="text-white" style="font-weight: bold;"><i class="fa fa-stop condition_warning"></i>Warning</h6>
                    </hgroup>
                    <hgroup class="hgroup" style="margin-left: 6%;">
                        <h6 class="text-white" style="font-weight: bold;"><i class="fa fa-stop condition_good"></i>Good</h6>
                    </hgroup>
                </div>
            </div>
            <div class="row menu" id="here">
            @foreach ($server_all as $server)
            @if ($server->active =='Yes')
                <div class="col-sm-5 col-md-5 col-lg-4 col-xl-3" style="margin-top: 1%;">
                @if($server->status =='down' || $server->database_status =='down' || $server->uptime_status =='down')
                    <div class="card" style="border-radius: 30px; background-image: linear-gradient(to left, #af0404, #ff0000);">
                    <input type="hidden" id="down" value="1">
                @elseif($server->pattern >= 2000 || $server->api =='down' || $server->api =='redirection')
                    <div class="card" style="border-radius: 30px; background-image: linear-gradient(to left, #be9400, #ffc400);">
                @elseif($server->status =='up' && $server->database_status =='up' && $server->uptime_status =='up' && $server->api =='up')
                    <div class="card" style="border-radius: 30px; background-image: linear-gradient(to left, #53a000, #6bce00);">
                @endif
                        <div class="card-body d-flex d-xl-flex align-items-xl-center">
                        @if ($server->label =='eSPEK')
                            <img src="{{ asset('img/backend/brand/icon%20espek.png') }}" class="eicon">
                        @elseif ($server->label =='eTOUCH')
                            <img src="{{ asset('img/backend/brand/icon%20–%20etouch.png') }}" class="eicon">
                        @elseif ($server->label =='eSECURE')
                            <img src="{{ asset('img/backend/brand/icon%20esecure.png') }}" class="eicon">
                        @elseif ($server->label =='eLEARN')
                            <img src="{{ asset('img/backend/brand/icon%20elearn.png') }}" class="eicon">
                        @elseif ($server->label =='eSTK')
                            <img src="{{ asset('img/backend/brand/icon%20STK.png') }}" class="eicon">
                        @elseif ($server->label =='GALERI')
                            <img src="{{ asset('img/backend/brand/icon%20Galery.png') }}" class="eicon">
                        @elseif ($server->label =='PINTU')
                            <img src="{{ asset('img/backend/brand/icon%20Pintu.png') }}" class="eicon">
                        @elseif ($server->label =='eSTAY')
                            <img src="{{ asset('img/backend/brand/icon%20estay.png') }}" class="eicon">
                        @elseif ($server->label =='SSO')
                            <img src="{{ asset('img/backend/brand/icon%20sso.png') }}" class="eicon">
                        @elseif ($server->label =='KOHA')
                            <img src="{{ asset('img/backend/brand/icon%20KOHA.png') }}" class="eicon">
                        @elseif ($server->label =='eGEO')
                            <img src="{{ asset('img/backend/brand/icon%20egeo.png') }}" class="eicon">
                        @else
                            <img src="{{ asset('img/backend/brand/default.png') }}" class="eicon">
                        @endif
                            <hgroup style="width:100%;">
                                <h5 class="hover text-white" style="font-weight: bold;font-family: Poppins, sans-serif;font-size: 20px; text-align:center;"><a class="text-white" href="{{ route('frontend.user.serverdetail' ,['id'=>$server->id]) }}" style="margin-bottom: 10px;" id="system_name">{{ $server->label }}</a><a class="text-white" style="font-weight: bold;" href="{{ route('frontend.user.serverdetail' ,['id'=>$server->id]) }}"></a>
                                    <span class="tooltip">
                                        <ul>
                                            <li>Name: {{ $server->label }}</li>
                                            <li>IP: {{ $server->ip }} </li>
                                            <li>Issue: {{ $server->issue }}</li>
                                        </ul>
                                    </span>
                                </h5>
                                <input type="hidden" name="api" id="api" value="$server->api">
                                <input type="hidden" name="webserver" id="webserver" value="$server->uptime_status">
                                <input type="hidden" name="ssl" id="ssl" value="$server->certificate_expiration_date">
                                <input type="hidden" name="database" id="database" value="$server->database_status">
                                <div style="width: 100%; height:2px; background-color: #ffffff1d; margin-bottom: 10px; opacity: 0.5s;"></div>
                                    <h2 class="text-light" style="font-size: 12px; font-weight: bold;text-align:center;">{{ $server->pattern }} ms</h2>
                        </div>
                    </div>
                </div>
            @endif
            @endforeach
            </div>
            <button type="hidden" id="beep" onclick='beep();' hidden>Beep!</button>
        </div>
    </div>
</body>
@endsection
@section('page-js-files')
@stop

@section('page-js-script')
<script type="text/javascript">
    function test(object) {
        $("document").ready(function(){
            setTimeout(function(){
                $("div.alert").remove();
            }, 3000 );
        });
        setTimeout(function(){
            location.href = '/dashboard';
        }, 60000);
        down = document.getElementById("down").value;
        if (down == 1){
            $(document).ready(function(){
                $("#beep").trigger('click');
            });
        }
    }

    function beep() {
        var beep = (function () {
            var ctxClass = window.audioContext ||window.AudioContext || window.AudioContext || window.webkitAudioContext
            var ctx = new ctxClass();
            return function (duration, type, finishedCallback) {

                duration = +duration;

                // Only 0-4 are valid types.
                type = (type % 3) || 1;

                if (typeof finishedCallback != "function") {
                    finishedCallback = function () {};
                }

                var osc = ctx.createOscillator();

                osc.type = type;
                //osc.type = "sine";

                osc.connect(ctx.destination);
                if (osc.noteOn) osc.noteOn(0);
                if (osc.start) osc.start();

                setTimeout(function () {
                    if (osc.noteOff) osc.noteOff(0);
                    if (osc.stop) osc.stop();
                    finishedCallback();
                }, duration);

            };

        })();
        
        document.getElementsByTagName("button")[0].addEventListener("click", function () {
            var button = this;
            button.disabled = true;
            beep(1000, 2, function () {
                button.disabled = false;
            });

        });
    };

</script>
@stop
