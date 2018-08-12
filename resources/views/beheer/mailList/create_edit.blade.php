@extends('layouts.beheer')

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}} </li>
            @endforeach
        </ul>
    @endif
    <div class="card">
        <div class="card-header">
            <h3>{{$fields['title']}}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['method' => $fields['method'], 'url' => $fields['url']]) !!}
            <input type="hidden" name="id" value="{{($mailList != null) ? $mailList->address : ""}}">
            <div class="form-group">
                {!! Form::label('address', trans('MailList.address')) !!}
                <div class="input-group">
                    {!! Form::text('address', ($mailList != null) ? explode('@',$mailList->address)[0] : "", ['class' => 'form-control','required' => 'required','aria-describedby' => 'basic-addon3']) !!}
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon3">@esac.nl</span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                {!! Form::label('name', trans('MailList.name')) !!}
                {!! Form::text('name', ($mailList != null) ? $mailList->name : "", ['class' => 'form-control','required' => 'required','aria-describedby' => 'basic-addon3']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('description', trans('MailList.description')) !!}
                {!! Form::text('description', ($mailList != null) ? $mailList->description : "", ['class' => 'form-control','required' => 'required','aria-describedby' => 'basic-addon3']) !!}
            </div>
            <div class="form-group">
                {!! Form::label('acces_level', trans('MailList.acces_level')) !!}
                {!! Form::select('acces_level',trans('MailListAccesLevels'), ($mailList != null) ? $mailList->acces_level : "", ['class' => 'form-control','required' => 'required','aria-describedby' => 'passwordHelpBlock']) !!}
                <div id="acces_level_help_block" class="form-text text-muted">Help blok over acces level</div>
            </div>
        </div>
    </div>

    <div class="my-4">
        {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{ ($mailList == null) ? ('/mailList') : ('/mailList/' . $mailList->address)}}">{{trans('menu.cancel')}}</a>
    </div>
@endsection

@push('scripts')
<script>
    $(function(){
        var acces_level_help_text_lookup = {
            'readonly': '{{trans('MailListAccesLevelHelpText.readonly')}}',
            'members': '{{trans('MailListAccesLevelHelpText.members')}}',
            'everyone': '{{trans('MailListAccesLevelHelpText.everyone')}}'
        };
       $(document).on('change','#acces_level',function(){
           var selected_acces_level = $('#acces_level').val();
           $('#acces_level_help_block').text(acces_level_help_text_lookup[selected_acces_level]);
       });

       $('#acces_level').change();
    });

</script>
@endpush