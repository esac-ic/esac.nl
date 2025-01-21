<div class="card mt-4">
    <div class="card-header">
        <h3>{{$fields['title_content']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-group">
            {{ html()->label('Title', 'title') }}
            {{ html()->text('title')
                ->value(($agendaItem != null) ? $agendaItem->title : "")
                ->class('form-control')
                ->required() }}
        </div>
        <div class="form-group">
            {{ html()->label('Short description (max. 100 characters)', 'shortDescription') }}
            {{ html()->text('shortDescription')
                ->value(($agendaItem != null) ? $agendaItem->shortDescription : "")
                ->class('form-control')
                ->required()
                ->attribute('maxlength', 100) }}
        </div>
        <div class="form-group">
            {{ html()->label('Content', 'content') }}
            {{ html()->textarea('text')
                ->value(($agendaItem != null) ? $agendaItem->text : "")
                ->class('form-control')
                ->id('content_en') }}
        </div>
    </div>
</div>
