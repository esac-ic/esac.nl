@extends('layouts.master')

@section('title')
{{ $curPageName }}
@endsection

@section('main')
@component('includes.menu')
    @slot('navClass', 'position-fixed bg-dark')
    @slot('navId', 'scrollNav')
@endcomponent
@include('includes.header')

@yield('content')

@include('includes.footer')
@endsection


@push('scripts')
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
@endpush
