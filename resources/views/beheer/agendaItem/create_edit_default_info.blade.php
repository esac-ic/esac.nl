<div class="card">
    <div class="card-header">
        <h3>{{$fields['title_info']}}</h3>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-6">
                {!! Form::label('startDate', 'Start date') !!}
                <div class="input-group date" id="startDateBox" data-target-input="nearest">
                    <input type='text' class="form-control datetimepicker-input" id="startDate" name="startDate" data-target="#startDateBox" value="{{($agendaItem != null) ? \Carbon\Carbon::parse($agendaItem->startDate)->format('d-m-Y H:i') : \Carbon\Carbon::now()->addHours(1)->format('d-m-Y H:i')}}" required/>
                     <div class="input-group-append" data-target="#startDateBox" data-toggle="datetimepicker">
                         <div class="input-group-text"><i class="ion-calendar"></i></div>
                     </div>
                 </div>
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('startDate', 'End date') !!}
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
                {!! Form::label('applicationForm', 'Application form') !!}
                {!! Form::select('applicationForm',$applicationForms, ($agendaItem != null) ? $agendaItem->application_form_id : "", ['class' => 'form-control','required' => 'required','id' => 'applicationForm',]) !!}
            </div>
            <div class="form-group col-md-6" id="subscription_endDate_box">
                {!! Form::label('subscription_endDate', 'Last possible registration date') !!}
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
                {!! Form::label('category', 'Category') !!}
                {!! Form::select('category',$agendaItemCategories, ($agendaItem != null) ? $agendaItem->category : "", ['class' => 'form-control','required' => 'required']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('climbing_activity', 'Show certificates in the event item') !!}
                {!! Form::checkbox('climbing_activity', 1, ($agendaItem != null) ? $agendaItem->climbing_activity : true, ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>