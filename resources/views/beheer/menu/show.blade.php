@extends('layouts.beheer')

@section('title')
{{trans('menuItems.menuItem')}}
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{trans('menuItems.menuItem')}}</h1>
        </div>

        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                <a href="{{url('/pages/'.$page->id . '/edit' )}}" class="btn btn-primary">
                    <span title="{{trans("menu.edit")}}" class="ion-edit" aria-hidden="true"></span>
                    {{trans("menu.edit")}}
                </a>
                <a href="{{url('/pages/')}}" class="btn btn-primary">
                    <span title="{{trans("menu.back")}}" class="ion-android-arrow-back" aria-hidden="true"></span>
                    {{trans("menu.back")}}
                </a>
                @if($page->deletable)
                    {{ Form::open(array('url' => 'pages/' .$page->id, 'method' => 'delete')) }}
                    <button type="submit" class="btn btn-danger"><span class="ion-trash-a"></span> {{trans('menu.delete')}}</button>
                    {{ Form::close() }}
                @endif
            </div>
        </div>
    </div>
    <div class="card mt-4">
        <div class="card-header">
            <h3>{{trans('menuItems.menuItem')}} {{$page->name}}</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped" style="width:100%">
                <tr>
                    <td>{{trans('menuItems.name')}}</td>
                    <td>{{$page->name}}</td>
                </tr>
                <tr>
                    <td>{{trans('menuItems.SubItemVan')}}</td>
                    <td>{{($page->partner != null) ?$page->partner->name : ""}}</td>
                </tr>
                <tr>
                    <td>{{trans('menuItems.urlName')}}</td>
                    <td>{{$page->urlName}}</td>
                </tr>
                <tr>
                    <td>{{trans('menuItems.needLogin')}}</td>
                    <td>{{($page->login)? trans("menu.yes") : trans("menu.no")}}</td>
                </tr>
                <tr>
                    <td>{{trans('menuItems.afterItem')}}</td>
                    <td>{{($page->after != null) ?$page->afterItem->name : ""}}</td>
                </tr>
                @if($subItems != null && count($subItems) > 0)
                    <tr>
                        <td rowspan="{{count($subItems) == 0 ? 1 : count($subItems)}}">{{trans('menuItems.subItems')}}</td>
                        @if( count($subItems) > 0)
                            <td>
                                {{$subItems[0]->name}}
                            </td>
                        @else
                            <td> </td>
                        @endif
                    </tr>
                    @for ($i = 1; $i < count($subItems); $i++)
                        <tr>
                            <td>
                                {{$subItems[$i]->name}}
                            </td>
                        </tr>
                    @endfor
                @endif
            </table>
        </div>
    </div>
@endsection
