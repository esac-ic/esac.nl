@extends('layouts.beheer')
<!-- @use('App\Enums\UserEventTypes') -->

@section('title')
{{'User event log export'}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{Session::get('message')}}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    
    <div class="row mb-3">
        <div class="col-md-6">
            <h1>{{'User event log export'}}</h1>
        </div>
        
        <div class="col-md-6">
            <div class="btn-group mt-2 float-md-right" role="group" aria-label="Actions">
                @if(app('request')->input('back') != 'false')
                    <a href="{{url('/userEventLog/')}}" class="btn btn-primary">
                        <em class="ion-android-arrow-back"></em> {{'Back'}}
                    </a>
                @endif
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <!-- Why is this unescaped data? -->
        <!-- {!! Form::open(['method' => 'post', 'url' => 'userEventLog/export', 'enctype' => 'multipart/form-data']) !!} -->
        <form name="export-user-events-form" id="export-user-events-form" method="post" action="{{url('userEventLog/export')}}">
            @csrf    
            
            <!-- checkboxes for event types -->
             <div class="form-row">
                <div class="form-group">
                    <label>Event types</label>
                    @foreach (\App\Enums\UserEventTypes::values() as $eventType)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="eventTypes[]" value="{{$eventType}}" id={{$eventType . "Check"}}>
                            <label class="form-check-label" for={{$eventType . "Check"}}>{{$eventType}}</label>
                        </div>
                    @endforeach
                </div>
             </div>
            
            <!-- Start and end date selection -->
            <div class="form-row">
                <div class="form-group col-md-6">
                    <!-- {!! Form::label('startDate', 'Start date') !!} -->
                    <label for="startDate">Start date</label>
                    <div class="input-group date" id="startDateBox" data-target-input="nearest">
                        <input type='text' class="form-control datetimepicker-input" id="startDate" name="startDate" data-target="#startDateBox" value="{{\Carbon\Carbon::now()->subWeeks(1)->format('d-m-Y H:i')}}" required/>
                        <div class="input-group-append" data-target="#startDateBox" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <!-- {!! Form::label('endDate', 'End date') !!} -->
                    <label for="endDate">End date</label>
                    <div class="input-group date" id="endDateBox" data-target-input="nearest">
                        <input type='text' class="form-control datetimepicker-input" id="endDate" name="endDate" data-target="#endDateBox" value="{{\Carbon\Carbon::now()->format('d-m-Y H:i')}}" required/>
                        <div class="input-group-append" data-target="#endDateBox" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="ion-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <p>TODO: user filtering</p>
                </div>
            </div>
            
            <div class="my-4">
                <button type="submit" class="btn btn-primary">Submit</button>
                <!-- {!! Form::submit('Save', ['class'=> 'btn btn-primary'] ) !!} -->
                <!-- {!! Form::close() !!} -->
                <!-- <a class="btn btn-danger btn-close" href="{ ($agendaItem == null) ? ('/agendaItems') : ('/agendaItems/' . $agendaItem->id)}}">{{'Cancel'}}</a> -->
            </div>
        </form>
    </div>
    
    
@endsection

@push('scripts')
<!-- not entirely sure what these scripts do -->
<script src="{{mix("js/vendor/moment.js")}}"></script>
<script src="{{mix("js/vendor/tempusdominus.js")}}"></script>

<script>
    $('#startDateBox, #endDateBox, #subscription_endDateBox').datetimepicker({
        locale: 'nl',
        icons: {
            time: 'ion-clock',
            date: 'ion-calendar',
            up: 'ion-android-arrow-up',
            down: 'ion-android-arrow-down',
            previous: 'ion-chevron-left',
            next: 'ion-chevron-right',
            today: 'ion-calendar',
            clear: 'ion-trash-a',
            close: 'ion-close'
        }
    });

    $(function () {
        $('#startDateBox').datetimepicker();
        $('#endDateBox').datetimepicker({
            useCurrent: false
        });
        $("#startDateBox").on("change.datetimepicker", function (e) {
            $('#endDateBox').datetimepicker('minDate', e.date);
        });
        $("#endDateBox").on("change.datetimepicker", function (e) {
            $('#startDateBox').datetimepicker('maxDate', e.date);
        });
    });

    $(document).ready(function() {
        $('#content_en').summernote(summernoteSettings);
    });
</script>
@endpush