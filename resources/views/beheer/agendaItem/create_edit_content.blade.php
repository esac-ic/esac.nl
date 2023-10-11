<div class="card mt-4">
    <div class="card-header">
        <h3>{{$fields['title_content']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            {!! Form::label('title', 'Title') !!}
            {!! Form::text('title', ($agendaItem != null) ? $agendaItem->title : "", ['class' => 'form-control','required' => 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('shortDescription', 'Short description (max. 100 characters)') !!}
            {!! Form::text('shortDescription', ($agendaItem != null) ? $agendaItem->shortDescription : "", ['class' => 'form-control','required' => 'required', 'maxlength' => 100 ]) !!}
        </div>
        <div class="form-group">
            {{Form::label('content',  'Content')}}
            {{Form::textarea('text',($agendaItem != null) ?  $agendaItem->text : "",array('class' => 'form-control', 'id' => 'content_en'))}}
        </div>
    </div>
</div>
