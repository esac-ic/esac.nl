@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container intro-container">
        <div class="row d-flex align-items-stretch">
            <div class="col-sm-8 d-flex flex-wrap">
                <div class="card w-100">
                    <div class="card-body">
                        <h2 class="card-title">Rumour has it that...</h2>
                        {!! $content !!}
                    </div>
                </div>     
            </div>
            <div class="col-sm-4 d-flex flex-wrap">
                <div class="card w-100">
                    <div class="card-body">
                        <h4 class="card-title">Add zekering</h4>
                        @auth
                            {{ html()->form('POST', route('api.zekeringen.store'))->open() }}
                            <x-field name="text" id="zekering_content" label="Inhoud" type="textarea" required maxlength="150" rows="3" />
                            <div class="form-group">
                                {{ html()->submit('Add')->class('btn btn-primary') }}
                            </div>
                            {{ html()->form()->close() }}
                        @else
                            You need to be logged in to view the zekeringen
                        @endauth
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
        @auth
        var LOGDIN = "1";
        @else
        var LOGDIN = "0";
        @endauth
        @can('manage-zekeringen')
        var ADMIN = "1";
        @else
        var ADMIN = "0";
        @endcan
    </script>
@endpush
