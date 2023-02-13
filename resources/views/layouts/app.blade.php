<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Scripts -->
    <link rel="icon" href="{{asset('/images/logo.png')}}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="manifest" href="{{ asset('js/manifest.json') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Styles -->
    <link href="{{ asset('css/fontsawesome/all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body style="visibility: hidden">
    <div id="app">
        <app></app>
    </div>
    
</body>
<script src="{{ asset('js/app.js') }}" defer></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.allowed-chars/1.0.2/jquery.allowed-chars.min.js"></script> --}}
    <script type="text/javascript">
        @auth
            window.Permissions = {!! json_encode(Auth::user()->allPermissions, true) !!};
        @else
            window.Permissions = [];
        @endauth
        $(document).on('keypress', '.number-only', function(e) {
            if (isNaN(this.value + "" + String.fromCharCode(e.charCode))) return false;
        });
        $(document).on('keydown', '.dis-space', function(e) {
            if (e.keyCode == 32) return false;
        });
        $(document).on('keypress', '.digitsOnly',function(e){
            if(e.which !=8 && e.which !=47 && isNaN(String.fromCharCode(e.which))){
                e.preventDefault();
            }
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function(){
            function greet(){
                $("body").css("visibility",'visible');
            }
            setTimeout(greet, 300);
            
            $(".btn_collapse_toggle").click(function(){
                $(this).find('.collapse_toggle').toggleClass('hide_cn');
            });
            // check scrren 
            var $window = $(window);
            var $pane = $('#pane1');
            function checkWidth() {
                var windowsize = $window.width();
                if (windowsize <820) {
                    $('body').addClass('is_hide_navbar');
                }else{
                    $('body').removeClass('is_hide_navbar');
                }
            }
            checkWidth();
            $(window).resize(checkWidth);

            $(document).on('keypress keyup blur', '.text_search',function(e){
                $(this).closest('.cus_select').find('.list').addClass('show');
            });
            $(document).click(function (event) {
                if (!$(event.target).closest('.text_search').length) {
                    $('.cus_select').find('.list').removeClass('show');
                }            
            });
            $(document).on('keypress keyup blur','.numericOnly',function(event){
                $(this).val($(this).val().replace(/[^0-9\.]/g,''));
                if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                    event.preventDefault();
                }
            });
        });
        
    </script>
</html>
