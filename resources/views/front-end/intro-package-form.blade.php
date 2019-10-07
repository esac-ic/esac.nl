<div class="card mt-4">
    <div class="card-header">
        <h3>{{trans('user.introPackageOptions')}}</h3>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-12">
                {!! Form::label('package_type', trans('user.introPackage')) !!}
                {!! Form::select('package_type',trans('user.packageTypes'), null, ['placeholder' => trans('user.packagePlaceholder'), 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-row" id="intro-form-options" style="display: none">
            <div class="form-group col-md-6">
                {!! Form::label('shirt_size', trans('user.tshirt')) !!}
                {!! Form::select('shirt_size',trans('user.shirtSizes'), 's', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('intro_weekend', trans('user.introWeekend')) !!}
                {!! Form::select('intro_weekend',trans('user.weekendDates'), 'intro1', ['class' => 'form-control']) !!}
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#package_type').on('change', function(){
            if($(this).val() === "") {
                $('#intro-form-options').hide();
            } else {
                $('#intro-form-options').show();
            }
        });
    </script>
@endpush