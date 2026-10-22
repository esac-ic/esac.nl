<div class="card">
    <div class="card-header">
        <h3>{{$fields['title_info']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                {{ html()->label('Start date', 'startDate') }}
                <div class="input-group date" id="startDateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="startDate" name="start_date" data-target="#startDateBox" value="{{ ($agendaItem?->start_date ?? now(config('app.display_timezone')))->format('d-m-Y H:i') }}" required/>
                     <div class="input-group-append" data-target="#startDateBox" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="ion-calendar"></i></div>
                     </div>
                 </div>
            </div>
            <div class="form-group col-md-6">
                {{ html()->label('End date', 'startDate') }}
                <div class="input-group date" id="endDateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="endDate" name="end_date" data-target="#endDateBox" value="{{ ($agendaItem?->end_date ?? now(config('app.display_timezone')))->format('d-m-Y H:i') }}" required/>
                     <div class="input-group-append" data-target="#endDateBox" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="ion-calendar"></i></div>
                     </div>
                 </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                {{ html()->label('Application form', 'applicationForm') }}
                {{ html()->select('applicationForm', $applicationForms, ($agendaItem != null) ? $agendaItem->application_form_id : "")->class('form-control')->required()->id('applicationForm') }}
            </div>
            <div class="form-group col-md-6" id="subscription_endDate_box">
                {{ html()->label('Last possible registration date', 'subscription_endDate') }}
                <div class="input-group date" id="subscription_endDateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="subscription_endDate" name="subscription_end_date" data-target="#subscription_endDateBox" value="{{ ($agendaItem ? ($agendaItem->subscription_end_date ?? now(config('app.display_timezone'))) : now(config('app.display_timezone'))->subDays(7))->format('d-m-Y H:i') }}" required/>
                     <div class="input-group-append" data-target="#subscription_endDateBox" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="ion-calendar"></i></div>
                     </div>
                 </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                {{ html()->label('Category', 'category') }}
                {{ html()->select('category', $agendaItemCategories, ($agendaItem != null) ? $agendaItem->category : "")->class('form-control')->required() }}
            </div>
            <div class="form-group col-md-6">
                {{ html()->label('Show certificates in the event item', 'climbing_activity') }}
                {{ html()->checkbox('climbing_activity', ! session()->hasOldInput() && (($agendaItem != null) ? $agendaItem->climbing_activity : true), 1)->class('form-control') }}
            </div>
        </div>
    </div>
</div>