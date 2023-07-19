<div class="card">
    <div class="card-header"><h3>{{$fields['title_menu']}}</h3></div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                {!! Form::label('NL_text', trans('menuItems.nameNl')) !!}
                {!! Form::text('NL_text', ($page != null) ? $page->text->NL_text : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('EN_text', trans('menuItems.nameEn')) !!}
                {!! Form::text('EN_text', ($page != null) ? $page->text->EN_text : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
        </div>
        <div class="form-row">
            @if($page === null || $page->editable)
                <div class="form-group col-md-6">
                    {!! Form::label('urlName', trans('menuItems.urlName')) !!}
                    {!! Form::text('urlName', ($page != null) ? $page->urlName : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            @endif
            <div class="col-md-6">
                <div class="form-check">
                    {!! Form::checkbox("menuItem",0,($page != null) ? ($page->menuItem) ? "checked" : "" : "",["class" => "form-check-input","id" => "menuItem"]) !!}
                    {!! Form::label("menuItem", trans("menuItems.showInMenu"), ["class" => "form-check-label"]) !!}
                </div>
                <div class="form-check">
                    {!! Form::checkbox("login",0,($page != null) ? ($page->login) ? "checked" : "" : "",["class" => "form-check-input", "id" => "login"]) !!}
                    {!! Form::label("login", trans("menuItems.needLogin"), ["class" => "form-check-label"]) !!}
                </div>
            </div>
        </div>
        <div class="form-row mt-2" id="menuSettingRow1">
            <div class="form-group col-md-6">
                <label for="itemType"> {{trans('menuItems.itemType')}} </label>
                <select class="form-control" name="itemType" id="menuType">
                    @if($page != null  &&$page->parent_id != null)
                        <option value="standAlone">{{trans('menuItems.Standalone')}}</option>
                        <option value="subItem" selected>{{trans('menuItems.SubItem')}}</option>
                    @else
                        <option value="standAlone" selected>{{trans('menuItems.Standalone')}}</option>
                        <option value="subItem">{{trans('menuItems.SubItem')}}</option>
                    @endif
                </select>
            </div>
            <div id="parentItemBox" class="col-md-6 hidden">
                <label for="parentItem"> {{trans('menuItems.ifSubItem')}} </label>
                <select class="form-control" name="parentItem" id="parentItem">
                    @foreach($pages as $item)
                        @if($page != null  && $item->id === $page->parent_id)
                            <option value="{{$item->id}}" selected >{{$item->text->text()}}</option>
                        @else
                            <option value="{{$item->id}}">{{$item->text->text()}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row" id="menuSettingRow2">
            <div class="form-group col-md-6">
                <label for="afterItem"> {{trans('menuItems.after')}} </label>
                <select class="form-control" name="afterItem" id="afterItem">
                </select>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $( "#menuType" ).change(function() {
                $("#afterItem").empty();
                if($( "#menuType" ).val() === "subItem" ){
                    $( "#parentItemBox" ).show();
                    $.getJSON("{{url("/api/menuItems?type=")}}" + $('#menuType').val() + "&parentId=" + $('#parentItem').val() , function(data){
                        fillAfterDropDownMenu(data);
                    });
                } else {
                    $( "#parentItemBox" ).hide();
                    $.getJSON("{{url("/api/menuItems?type=")}}" + $('#menuType').val(), function(data){
                        fillAfterDropDownMenu(data);
                    });
                }
            });

            $('#menuItem').change(function () {
                if($('#menuItem').is(':checked')){
                    $('#menuSettingRow1').show();
                    $('#menuSettingRow2').show();
                } else {
                    $('#menuSettingRow1').hide();
                    $('#menuSettingRow2').hide();
                }
            });

            $('#parentItem').change(function(){
                $.getJSON("{{url("/api/menuItems?type=")}}" + $('#menuType').val() + "&parentId=" + $('#parentItem').val() , function(data){
                    fillAfterDropDownMenu(data);
                });
            });

            $( "#menuType" ).change();
            $( "#menuItem" ).change();
        });


        function fillAfterDropDownMenu(data){
            $("#afterItem").empty();
            $("#afterItem").append('<option value="-1" selected>{{trans('menuItems.notAfter')}}</option>');
            $.each(data, function(){
                @if($page!= null && $page->after  != null)
                if(this.id == {{$page->after}}){
                    $("#afterItem").append('<option value="'+ this.id +'" selected>'+ this.name +'</option>');
                } else {
                    $("#afterItem").append('<option value="' + this.id + '">' + this.name + '</option>');
                }
                @else
                    $("#afterItem").append('<option value="'+ this.id +'">'+ this.name +'</option>');
                @endif
            });
        }
    </script>
@endpush