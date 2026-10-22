<div class="modal fade" id="export-member-log-modal" tabindex="-1" role="dialog" aria-labelledby="export-member-log-modal-title"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="export-member-log-modal-title">Export the member log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>    
        
            <div class="modal-body">
                <form method="post" action="{{ route('admin.member-log.export') }}">
                    @csrf    
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Types</label>
                            @foreach ($types as $type)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked name="types[]" value="{{ $type }}" id="type-{{ $type }}">
                                <label class="form-check-label" for="type-{{ $type }}">{{ $type }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <x-date-picker name="start_date" id="startDate" label="Start date" :value="now(config('app.display_timezone'))->subWeeks(1)->format('d-m-Y H:i')" />
                        </div>
                        <div class="form-group col-md-6">
                            <x-date-picker name="end_date" id="endDate" label="End date" :value="now(config('app.display_timezone'))->format('d-m-Y H:i')" />
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="my-4 mx-auto">
                            <button type="submit" class="btn btn-primary px-4"><i class="ion-android-download"></i> Export to excel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    
@push('scripts')
    {{-- Date-time pickers for the period, keeping the end date after the start date --}}
    <script src="{{mix("js/vendor/moment.js")}}"></script>
    <script src="{{mix("js/vendor/tempusdominus.js")}}"></script>

    <script>
        $('#startDateBox, #endDateBox').datetimepicker({
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
@endpush
