<div class="card mt-4">
    <div class="card-header">
        <h3>{{$fields['title_content']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            {{ html()->label('Title', 'title') }}
            {{ html()->text('title', ($agendaItem != null) ? $agendaItem->title : "")->class('form-control')->required() }}
        </div>
        <div class="form-group">
            {{ html()->label('Short description (max. 100 characters)', 'short_description') }}
            {{ html()->text('short_description', ($agendaItem != null) ? $agendaItem->short_description : "")->class('form-control')->required()->maxlength(100) }}
        </div>
        <div class="form-group">
            {{ html()->label('Content', 'content_en') }}
            {{ html()->textarea('text')->text(old('text', ($agendaItem != null) ? $agendaItem->text : ""))->class('form-control')->id('content_en')->cols(50)->rows(10) }}
        </div>
    </div>
</div>
