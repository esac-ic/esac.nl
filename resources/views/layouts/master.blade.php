<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="verification" content="6364deb46fa2fe44c96bc77a821e8ab4" />
        <title>@yield('title') - Eindhovense Studenten Alpen Club</title>

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script>
            window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
                ]); ?>
        </script>

        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#000000">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="theme-color" content="#ffffff">

        <link href="https://fonts.googleapis.com/css?family=Rubik:300,400,400i,500" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">

        @stack('styles')
        <link rel="stylesheet" type="text/css" href="{{mix("css/app.css")}}">

    </head>
    <body>
        @yield('main')

        <script src="{{mix("/js/vendor/jquery.js")}}" type="text/javascript"></script>
        <script src="{{mix("/js/vendor/popper.js")}}" type="text/javascript"></script>
        <script src="{{mix("/js/vendor/bootstrap.js")}}" type="text/javascript"></script>
        @stack('scripts')
        <script src="{{mix("/js/app.js")}}" type="text/javascript"></script>
        <script type="text/javascript">
            const COOKIE_NAME = "lang";
            const COOKIE_LANG_SET_NAME = "langset";
            const DEFAULT_LANG = "nl";
            const FLAG_LOOKUP = {
                "en" : {"src" : "flag-united-kingdom", "name" : "{{trans('menu.en')}}"},
                "nl" : {"src" : "flag-the-netherlands", "name" : "{{trans('menu.nl')}}"}
            };
            var current_lang;

            $(document).ready(function() {
                sessionStorage.setItem("COOKIE_NAME","en");
                if(!getCookie(COOKIE_NAME)){
                    createCookie(COOKIE_NAME,DEFAULT_LANG);
                }
                new_lang = getCookie(COOKIE_NAME);
                if(new_lang !== DEFAULT_LANG){
                    setSelectedLang(new_lang);
                    if(!getCookie(COOKIE_LANG_SET_NAME)){
                        setLangServerSide(new_lang);
                    }
                }

            });

            $(document).on('click','#set_lang',function () {
                var new_lang =  $(this).attr('data-lang');
                setSelectedLang(new_lang);
                setLangServerSide(new_lang);
            });

            function setSelectedLang(new_lang){
                $('#selected_lang').attr("src",'{{asset('img/lang_icons')}}/' + FLAG_LOOKUP[new_lang]['src'] + '.png');
                var other_lang_list = $('#other_lang');
                other_lang_list.empty();
                for (var key in FLAG_LOOKUP) {
                    if(key !== new_lang){
                        other_lang_list.append('<a href="#" id="set_lang" class="dropdown-item" data-lang =' + key + '><img src="{{asset('img/lang_icons')}}/' + FLAG_LOOKUP[key]['src'] + '.png"> ' + FLAG_LOOKUP[key]['name'] + '</a>');
                    }
                }

                createCookie(COOKIE_NAME,new_lang);
                current_lang = new_lang;
            }

            function setLangServerSide(lang){
                $.get("{{url("")}}/api/setLanguage?language=" + lang , function(data){
                    console.log('new lang is set');
                    createCookie(COOKIE_LANG_SET_NAME,'set',1);
                    window.location.reload();
                });
            }
        </script>
    </body>
</html>