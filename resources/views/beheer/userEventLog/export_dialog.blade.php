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
                <form name="export-user-events-form" id="export-user-events-form" method="post" action="{{url('userEventLog/export')}}">
                    @csrf    
                    
                    <!-- checkboxes for event types -->
                    <div class="form-row">
                        <div class="form-group">
                            <label>{{"Event types"}}</label>
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
                            <label for="startDate">Start date</label>
                            <div class="input-group date" id="startDateBox" data-target-input="nearest">
                                <input type='text' class="form-control datetimepicker-input" id="startDate" name="startDate" data-target="#startDateBox" value="{{\Carbon\Carbon::now()->addHour()->subWeeks(1)->format('d-m-Y H:i')}}" required/>
                                <div class="input-group-append" data-target="#startDateBox" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="ion-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="endDate">End date</label>
                            <div class="input-group date" id="endDateBox" data-target-input="nearest">
                                <input type='text' class="form-control datetimepicker-input" id="endDate" name="endDate" data-target="#endDateBox" value="{{\Carbon\Carbon::now()->addHour()->format('d-m-Y H:i')}}" required/>
                                <div class="input-group-append" data-target="#endDateBox" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="ion-calendar"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- TODO for later: add user filter selection. -->
                    <!-- <div class="form-row">
                        <duv class="form-group w-100"> 
                            <div class="accordion pr-3" id="accordion-1" data-children=".accordion-item">
                                <div class="accordion-item">
                                    <a class="bg-gray-100" data-toggle="collapse" data-parent="#accordion-1" href="#accordion-panel-1" aria-expanded="false" aria-controls="accordion-1">
                                        <h5>{{ "User filtering" }}</h5>
                                        <i class="h5 ion-chevron-right"></i>
                                    </a>
                                    <div id="accordion-panel-1" class="collapse" role="tabpanel">
                                        <p>By signing this form, you subscribe to the ESAC up until termination of your membership. Without notice, your membership of the ESAC is continued annually. Termination of membership should happen before the 1st of January by sending a letter to the address mentioned above or by sending an email to secretaris@esac.nl.</p>
                                        <p>The annual contribution will be taken from your bank account via direct debit. The amount of the contribution is determined by the general members’ assembly (GMA / ALV). If you subscribe on or after the 1st of August, you have to pay a reduced contribution (of which the amount is also determined by the GMA) for the rest of that calendar year.</p>
                                        <p>Being a student and being in the possession of a sports card of the Student Sports Centre Eindhoven are mandatory for all new members of the ESAC.</p>
                                        <p>If you participate in mountain sports activities, having an insurance which covers (extreme) mountain sports is mandatory. NKBV membership and insurance are strongly recommended. Other insurances are also possible, but you should thoroughly check the policy. You can’t hold the ESAC liable for any possible damage or injuries.</p>
                                    </div>
                                </div>  
                            </div> 
                        </div>
                    </div> -->
                    
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
    <!-- not entirely sure what these scripts do -->
    <script src="{{mix("js/vendor/moment.js")}}"></script>
    <script src="{{mix("js/vendor/tempusdominus.js")}}"></script>

    <script>
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
        
        $(document).ready(function() {
            $('#content_en').summernote(summernoteSettings);
        });
    </script>
@endsection