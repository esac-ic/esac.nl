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
                    {{ html()->label('URL name', 'urlName') }}
                    {{ html()->text('urlName', ($page != null) ? $page->urlName : "")->class('form-control')->required() }}
                </div>
            @endif
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group form-check">
                    {{ html()->checkbox('menuItem', ($page != null && $page->menuItem), 0)->class('form-check-input')->id('menuItem') }}
                    {{ html()->label('Show page in menu', 'menuItem')->class('form-check-label') }}
                </div>
                <div class="form-group form-check">
                    {{ html()->checkbox('login', ($page != null && $page->login), 0)->class('form-check-input')->id('login') }}
                    {{ html()->label('User needs to be logged in to view the page', 'login')->class('form-check-label') }}
                </div>
            </div>
        </div>
        <div class="form-row mt-2" id="menuSettingRow1">
            <div class="form-group col-md-6">
                {{ html()->label('Item type: ', 'itemType') }}
                {{ html()->select('itemType')
                    ->class('form-control')
                    ->id('menuType')
                    ->options([
                        'standAlone' => 'Standalone menu item',
                        'subItem' => 'Sub menu item'
                    ])
                    ->value($page != null && $page->parent_id != null ? 'subItem' : 'standAlone') }}
            </div>
            <div id="parentItemBox" class="col-md-6 hidden">
                {{ html()->label('If page is a sub page, who is the parent?', 'parentItem') }}
                {{ html()->select('parentItem')
                    ->class('form-control')
                    ->id('parentItem')
                    ->options($pages->pluck('name', 'id'))
                    ->value($page?->parent_id) }}
            </div>
        </div>
        <div class="form-row" id="menuSettingRow2">
            <div class="form-group col-md-6">
                {{ html()->label('After which menu item', 'afterItem') }}
                {{ html()->select('afterItem')
                    ->class('form-control')
                    ->id('afterItem') }}
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
