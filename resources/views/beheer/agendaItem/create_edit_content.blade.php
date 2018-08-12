<div class="card mt-4">
    <div class="card-header">
        <h3>{{$fields['title_content']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            {!! Form::label('NL_title', trans('AgendaItems.title_nl')) !!}
            {!! Form::text('NL_title', ($agendaItem != null) ? $agendaItem->agendaItemTitle->NL_text : "", ['class' => 'form-control','required' => 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('EN_title', trans('AgendaItems.title_en')) !!}
            {!! Form::text('EN_title', ($agendaItem != null) ? $agendaItem->agendaItemTitle->EN_text : "", ['class' => 'form-control','required' => 'required']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('NL_shortDescription', trans('AgendaItems.shortDescription_nl')) !!}
            {!! Form::text('NL_shortDescription', ($agendaItem != null) ? $agendaItem->agendaItemShortDescription->NL_text : "", ['class' => 'form-control','required' => 'required', 'maxlength' => 100 ]) !!}
        </div>
        <div class="form-group">
            {!! Form::label('EN_shortDescription', trans('AgendaItems.shortDescription_en')) !!}
            {!! Form::text('EN_shortDescription', ($agendaItem != null) ? $agendaItem->agendaItemShortDescription->EN_text : "", ['class' => 'form-control','required' => 'required', 'maxlength' => 100 ]) !!}
        </div>
        <div class="form-group">
            {{Form::label('content_nl',  trans('menuItems.content_nl'))}}
            {{Form::textarea('NL_text',($agendaItem != null) ? $agendaItem->agendaItemText->NL_text : "",array('class' => 'form-control', 'id' => 'content_nl'))}}
        </div>
        <div class="form-group">
            {{Form::label('content_en',  trans('menuItems.content_en'))}}
            {{Form::textarea('EN_text',($agendaItem != null) ?  $agendaItem->agendaItemText->EN_text : "",array('class' => 'form-control', 'id' => 'content_en'))}}
        </div>
    </div>
</div>