<div class="card mt-4">
    <div class="card-header">
        <h3>{{'Intro package options'}}</h3>
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-12">
                {!! Form::label('package_type', 'Intro pakket') !!}
                {!! Form::select('package_type','Array', null, ['placeholder' => 'No introduction package...', 'class' => 'form-control']) !!}
            </div>
        </div>
        <div class="form-row" id="intro-form-options" style="display: none">
            <div class="form-group col-md-6">
                {!! Form::label('shirt_size', 'ESAC Intro shirt') !!}
                {!! Form::select('shirt_size','Array', 's', ['class' => 'form-control']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('intro_weekend', 'Intro weekend') !!}
                {!! Form::select('intro_weekend','Array', 'intro1', ['class' => 'form-control']) !!}
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
