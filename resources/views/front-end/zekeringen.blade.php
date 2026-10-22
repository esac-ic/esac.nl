{{--
TODO: toevoegen van voting knoppen zodat mensen kunnen plussen en minnen
Werking van deze pagina
Zekeringen kunnen worden toegevoegd via deze pagina als mensen ingelogd zijn.
Als iemand niet ingelogd is ziet diegene de knop ook niet.
TODO: idee, mensen hun eigen zekeringen laten verwijderen?
TODO: voeg toe moet gewoon plusje worden
--}}

@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container intro-container">
        <div class="row d-flex align-items-stretch">
            <div class="col-sm-8 d-flex flex-wrap">
                <div class="card w-100">
                    <div class="card-body">
                        <h2 class="card-title">{{'Er wordt gezekerd dat...'}}</h2>
                        {!! $content !!}
                    </div>
                </div>     
            </div>
            <div class="col-sm-4 d-flex flex-wrap">
                <div class="card w-100">
                    <div class="card-body">
                        <h4 class="card-title">{{'Add zekering'}}</h4>
                        @if (!Auth::guest())
                            {{ html()->form('POST', route('api.zekeringen.store'))->open() }}
                            <div class="form-group">
                                {{ html()->label('Inhoud', 'zekering_content') }}
                                {{ html()->textarea('text')->text(old('text', ''))->class('form-control')->required()->maxlength(150)->id('zekering_content')->cols(50)->rows(3) }}
                            </div>
                            <div class="form-group">
                                {{ html()->submit('Voeg toe')->class('btn btn-primary') }}
                            </div>
                            @csrf
                            {{ html()->form()->close() }}
                        @else
                            {{'You need to be logged in to view the zekeringen'}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <zekeringen></zekeringen>
</div>
@endsection

@push('scripts')
    <script>
        var LOGDIN = "{{Auth::guest() ? "0" : "1"}}";
        var ADMIN = "{{!Auth::guest() && Auth::getUser()->hasRole(\App\Enums\Role::ActivityAdministrator->value) ? "1" : "0"}}"
    </script>
@endpush
