<div class="card mt-4">
    <div class="card-header"><h3>{{$fields['title_page']}}</h3></div>
    <div class="card-body">
        <div class="form-group">
            {{ html()->label('Content', 'content') }}
            {{ html()->textarea('content')
                ->value(($page != null) ? $page->content : '')
                ->class('form-control')
                ->id('content') }}
        </div>
    </div>
</div>
