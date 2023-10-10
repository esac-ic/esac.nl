<div class="card mt-4">
    <div class="card-header">
        <h3>{{$fields['title_content']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            {!! Form::label('title', trans('AgendaItems.title_en')) !!}
            {!! Form::text('title', ($agendaItem != null) ? $agendaItem->title : "", ['class' => 'form-control','required' => 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('shortDescription', trans('AgendaItems.shortDescription_en')) !!}
            {!! Form::text('shortDescription', ($agendaItem != null) ? $agendaItem->shortDescription : "", ['class' => 'form-control','required' => 'required', 'maxlength' => 100 ]) !!}
        </div>
        <div class="form-group">
            {{Form::label('content',  trans('menuItems.content_en'))}}
            {{Form::textarea('text',($agendaItem != null) ?  $agendaItem->text : "",array('class' => 'form-control', 'id' => 'content_en'))}}
        </div>
    </div>
</div>