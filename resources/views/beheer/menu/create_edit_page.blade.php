<div class="card mt-4">
    <div class="card-header"><h3>{{$fields['title_page']}}</h3></div>
    <div class="card-body">
        <div class="form-group">
            {{Form::label('content_nl',  trans('menuItems.content_nl'))}}
            {{Form::textarea('content_nl',($page != null) ?  $page->content->NL_text : "",array('class' => 'form-control', 'id' => 'content_nl'))}}
        </div>
        <div class="form-group">
            {{Form::label('content_en',  trans('menuItems.content_en'))}}
            {{Form::textarea('content_en',($page != null) ?  $page->content->EN_text : "",array('class' => 'form-control', 'id' => 'content_en'))}}
        </div>
    </div>
</div>