<div class="card">
    <div class="card-header"><h3>{{$fields['title_menu']}}</h3></div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                {!! Form::label('name', 'Name') !!}
                {!! Form::text('name', ($page != null) ? $page->name : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
            @if($page === null || $page->editable)
                <div class="form-group col-md-6">
                    {!! Form::label('urlName', 'URL name') !!}
                    {!! Form::text('urlName', ($page != null) ? $page->urlName : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            @endif
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group form-check">
                    {!! Form::checkbox("menuItem",0,($page != null) ? ($page->menuItem) ? "checked" : "" : "",["class" => "form-check-input","id" => "menuItem"]) !!}
                    {!! Form::label("menuItem", 'Show page in menu', ["class" => "form-check-label"]) !!}
                </div>
                <div class="form-group form-check">
                    {!! Form::checkbox("login",0,($page != null) ? ($page->login) ? "checked" : "" : "",["class" => "form-check-input", "id" => "login"]) !!}
                    {!! Form::label("login", 'User needs to be logged in to view the page', ["class" => "form-check-label"]) !!}
                </div>
            </div>
        </div>
        <div class="form-row mt-2" id="menuSettingRow1">
            <div class="form-group col-md-6">
                <label for="itemType"> {{'Item type: '}} </label>
                <select class="form-control" name="itemType" id="menuType">
                    @if($page != null  &&$page->parent_id != null)
                        <option value="standAlone">{{'Standalone menu item'}}</option>
                        <option value="subItem" selected>{{'Sub menu item'}}</option>
                    @else
                        <option value="standAlone" selected>{{'Standalone menu item'}}</option>
                        <option value="subItem">{{'Sub menu item'}}</option>
                    @endif
                </select>
            </div>
            <div id="parentItemBox" class="col-md-6 hidden">
                <label for="parentItem"> {{'If page is a sub page, who is the parent?'}} </label>
                <select class="form-control" name="parentItem" id="parentItem">
                    @foreach($pages as $item)
                        @if($page != null  && $item->id === $page->parent_id)
                            <option value="{{$item->id}}" selected >{{$item->name}}</option>
                        @else
                            <option value="{{$item->id}}">{{$item->name}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-row" id="menuSettingRow2">
            <div class="form-group col-md-6">
                <label for="afterItem"> {{'After which menu item'}} </label>
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
            $("#afterItem").append('<option value="-1" selected>Not after an item</option>');
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
