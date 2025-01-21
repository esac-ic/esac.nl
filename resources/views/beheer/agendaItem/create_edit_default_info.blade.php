<div class="card">
    <div class="card-header">
        <h3>{{$fields['title_info']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                {{ html()->label('Start date', 'startDate') }}
                <div class="input-group date" id="startDateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="startDate" name="startDate" data-target="#startDateBox" value="{{($agendaItem != null) ? \Carbon\Carbon::parse($agendaItem->startDate)->format('d-m-Y H:i') : \Carbon\Carbon::now()->addHours(1)->format('d-m-Y H:i')}}" required/>
                     <div class="input-group-append" data-target="#startDateBox" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="ion-calendar"></i></div>
                     </div>
                 </div>
            </div>
            <div class="form-group col-md-6">
                {{ html()->label('End date', 'startDate') }}
                <div class="input-group date" id="endDateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="endDate" name="endDate" data-target="#endDateBox" value="{{($agendaItem != null) ? \Carbon\Carbon::parse($agendaItem->endDate)->format('d-m-Y H:i') : \Carbon\Carbon::now()->addHours(1)->format('d-m-Y H:i')}}" required/>
                     <div class="input-group-append" data-target="#endDateBox" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="ion-calendar"></i></div>
                     </div>
                 </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                {{ html()->label('Application form', 'applicationForm') }}
                {{ html()->select('applicationForm', $applicationForms)
                    ->value(($agendaItem != null) ? $agendaItem->application_form_id : "")
                    ->class('form-control')
                    ->required()
                    ->id('applicationForm') }}
            </div>
            <div class="form-group col-md-6" id="subscription_endDate_box">
                {{ html()->label('Last possible registration date', 'subscription_endDate') }}
                <div class="input-group date" id="subscription_endDateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="subscription_endDate" name="subscription_endDate" data-target="#subscription_endDateBox" value="{{($agendaItem != null) ? \Carbon\Carbon::parse($agendaItem->subscription_endDate)->format('d-m-Y H:i') : \Carbon\Carbon::now()->subDays(7)->format('d-m-Y H:i')}}" required/>
                     <div class="input-group-append" data-target="#subscription_endDateBox" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="ion-calendar"></i></div>
                     </div>
                 </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                {{ html()->label('Category', 'category') }}
                {{ html()->select('category', $agendaItemCategories)
                    ->value(($agendaItem != null) ? $agendaItem->category : "")
                    ->class('form-control')
                    ->required() }}
            </div>
            <div class="form-group col-md-6">
                {{ html()->label('Show certificates in the event item', 'climbing_activity') }}
                {{ html()->checkbox('climbing_activity')
                    ->value(1)
                    ->checked(($agendaItem != null) ? $agendaItem->climbing_activity : true)
                    ->class('form-control') }}
            </div>
        </div>
    </div>
</div>
