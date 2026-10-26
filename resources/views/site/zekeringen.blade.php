@extends('layouts.app')

@section('content')
<div class="container intro-container">
    <div class="row d-flex align-items-stretch">
        <div class="col-sm-8 d-flex flex-wrap">
            <div class="card w-100">
                <div class="card-body">
                    <h2 class="card-title">Er wordt gezekerd dat...</h2>
                    {!! $content !!}
                </div>
            </div>     
        </div>
        <div class="col-sm-4 d-flex flex-wrap">
            <div class="card w-100">
                <div class="card-body">
                    <h4 class="card-title">Add zekering</h4>
                    {{ html()->form('POST', route('zekeringen.store'))->open() }}
                    <x-field name="text" label="Text" type="textarea" required maxlength="150" rows="3" />
                    <div class="form-group">
                        {{ html()->submit('Add')->class('btn btn-primary') }}
                    </div>
                    {{ html()->form()->close() }}
                </div>
            </div>
        </div>
    </div>
</div>
<div data-vue="zekeringen" data-props="{{ json_encode(['canManage' => auth()->user()->can('manage-zekeringen')]) }}"></div>
@endsection
