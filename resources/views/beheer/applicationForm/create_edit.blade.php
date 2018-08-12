@extends('layouts.beheer')

@section('title')
{{$fields['title']}}
@endsection

@section('content')
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            {{Session::get('message')}}
        </div>
    @endif
    @if ($errors->any())
        <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error}} </li>
            @endforeach
        </ul>
    @endif

    <div class="card">
        <div class="card-header">
            <h3>{{$fields['title']}}</h3>
        </div>
        <div class="card-body">
            {!! Form::open(['method' => $fields['method'], 'url' => $fields['url']]) !!}
            {{--hidden field with the amount of formrows--}}
            <input name="amount_of_formrows" type="hidden" id="amount_of_formrows" value="0">
            <div class="form-row">
                <div class="form-group col-md-6">
                    {!! Form::label('name', trans('ApplicationForm.nameNl')) !!}
                    {!! Form::text('NL_text', ($applicationForm != null) ? $applicationForm->applicationFormName->NL_text : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
                <div class="form-group col-md-6">
                    {!! Form::label('name', trans('ApplicationForm.nameEn')) !!}
                    {!! Form::text('EN_text', ($applicationForm != null) ? $applicationForm->applicationFormName->EN_text : "", ['class' => 'form-control','required' => 'required']) !!}
                </div>
            </div>
            <div id="formRowContainer">
                {{--Content is filled with javascript dynamicly--}}
            </div>
        </div>
    </div>

    <div class="my-4">
        {!! Form::submit(trans('menu.save'), ['class'=> 'btn btn-primary'] ) !!}
        {!! Form::close() !!}
        <a class="btn btn-danger btn-close" href="{{ ($applicationForm == null) ? ('/applicationForms') : ('/applicationForms/' . $applicationForm->id)}}">{{trans('menu.cancel')}}</a>
    </div>
    
{{--includes javascript variable with the render code for a application form box--}}
@include('beheer.applicationForm.application_form_box')
@endsection

@push('scripts')
    @yield('application_box')
    <script>
        String.prototype.replaceAll = function(search, replacement) {
            var target = this;
            return target.split(search).join(replacement);
        };
        var amount_of_boxs = 0;
        var form_field_box = $('#formRowContainer');
        var number_place_holder = "nr";

        //subscribe on events
        $(document).on('click','#add_row',add_empty_form_box);
        $(document).on('click','.delete_row',remove_row);

        $(document).ready(function() {
            @if($applicationForm != null)
                load_form_field();
            @else
                add_empty_form_box();
            @endif
        });

        function load_form_field(){
            @if($applicationForm)
                @foreach($applicationFormRows as $row)
                    add_empty_form_box();
                    $('#NL_text_row_' + amount_of_boxs).val("{{$row->applicationFormRowName->NL_text}}");
                    $('#EN_text_row_' + amount_of_boxs).val("{{$row->applicationFormRowName->EN_text}}");
                    $('#row_type_' + amount_of_boxs).val("{{$row->type}}");
                    @if($row->required === 1)
                           $('#row_required_' + amount_of_boxs).prop('checked', true);
                    @endif
                @endforeach
            @endif

        }

        function add_empty_form_box(){
            setAmountOfBoxes(getAmountOfBoxes() + 1);
            var field = form_field_box_template.replaceAll(number_place_holder,amount_of_boxs);
            form_field_box.append(field);
        }

        function remove_row(e){
            id = e.target.id;
            $('#row_' + id).remove();
            setAmountOfBoxes(getAmountOfBoxes() - 1);
        }

        function setAmountOfBoxes(amount){
            amount_of_boxs = amount;
            $('#amount_of_formrows').val(amount_of_boxs);
        }

        function getAmountOfBoxes(){
            return amount_of_boxs;
        }
    </script>
@endpush
