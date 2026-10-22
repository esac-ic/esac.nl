<div class="modal fade" id="exportUserEventLogModal" tabindex="-1" role="dialog" aria-labelledby="exportUserEventLogModal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title">{{'Export event log'}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>    
        
            <div class="modal-body">
                <form name="export-user-events-form" id="export-user-events-form" method="post" action="{{ route('beheer.user-event-log.export') }}">
                    @csrf    
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>{{"Event types"}}</label>
                            @foreach ($eventTypes as $eventType)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked name="eventTypes[]" value="{{$eventType}}" id={{$eventType . "Check"}}>
                                <label class="form-check-label" for={{$eventType . "Check"}}>{{$eventType}}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <x-date-picker name="startDate" id="startDate" label="Start date" :value="now(config('app.display_timezone'))->subWeeks(1)->format('d-m-Y H:i')" />
                        </div>
                        <div class="form-group col-md-6">
                            <x-date-picker name="endDate" id="endDate" label="End date" :value="now(config('app.display_timezone'))->format('d-m-Y H:i')" />
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="my-4 mx-auto">
                            <button type="submit" class="btn btn-primary px-4"><i class="ion-android-download"></i> {{"Export to excel"}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
@section('modal_javascript')
    {{-- Date-time pickers for the period, keeping the end date after the start date --}}
    @vite(['resources/assets/js/vendor/moment.js', 'resources/assets/js/vendor/tempusdominus.js'])

    <script type="module">
        $('#startDateBox, #endDateBox').datetimepicker({
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
    </script>
@endsection