<div class="card">
    <div class="card-header"><h3>{{$fields['title_menu']}}</h3></div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                {{ html()->label('Name', 'name') }}
                {{ html()->text('name', ($page != null) ? $page->name : "")->class('form-control')->required() }}
            </div>
            @if($page === null || $page->editable)
                <div class="form-group col-md-6">
                    {{ html()->label('URL name', 'url_name') }}
                    {{ html()->text('url_name', ($page != null) ? $page->url_name : "")->class('form-control')->required() }}
                </div>
            @endif
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group form-check">
                    {{ html()->checkbox('menu_item', ! session()->hasOldInput() && $page != null && $page->menu_item)->class('form-check-input')->id('menuItem') }}
                    {{ html()->label('Show page in menu', 'menuItem')->class('form-check-label') }}
                </div>
                <div class="form-group form-check">
                    {{ html()->checkbox('login', ! session()->hasOldInput() && $page != null && $page->login)->class('form-check-input')->id('login') }}
                    {{ html()->label('User needs to be logged in to view the page', 'login')->class('form-check-label') }}
                </div>
            </div>
        </div>
        <div class="form-row mt-2" id="menuSettingRow1">
            <div class="form-group col-md-6">
                <label for="itemType"> {{'Item type: '}} </label>
                <select class="form-control" name="itemType" id="menuType">
                    <option value="standAlone" @selected($page?->parent_id == null)>{{'Standalone menu item'}}</option>
                    <option value="subItem" @selected($page?->parent_id != null)>{{'Sub menu item'}}</option>
                </select>
            </div>
            <div id="parentItemBox" class="col-md-6 hidden">
                <label for="parentItem"> {{'If page is a sub page, who is the parent?'}} </label>
                <select class="form-control" name="parentItem" id="parentItem">
                    @foreach($pages as $item)
                        <option value="{{$item->id}}" @selected($item->id === $page?->parent_id)>{{$item->name}}</option>
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
    <script type="module">
        $(document).ready(function() {
            $( "#menuType" ).change(function() {
                $("#afterItem").empty();
                if($( "#menuType" ).val() === "subItem" ){
                    $( "#parentItemBox" ).show();
                    $.getJSON("{{ route('api.menuItems.index') }}?type=" + $('#menuType').val() + "&parentId=" + $('#parentItem').val() , function(data){
                        fillAfterDropDownMenu(data);
                    });
                } else {
                    $( "#parentItemBox" ).hide();
                    $.getJSON("{{ route('api.menuItems.index') }}?type=" + $('#menuType').val(), function(data){
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
                $.getJSON("{{ route('api.menuItems.index') }}?type=" + $('#menuType').val() + "&parentId=" + $('#parentItem').val() , function(data){
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
