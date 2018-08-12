@section('application_box')
    <script>
      var form_field_box_template = 
      '<div id="row_nr">' +
            '<div class="card mt-4">' +
                  '<div class="card-header clearfix">' +
                        '<h3 class="float-left">{{trans('ApplicationForm.selectFormRows')}}</h3>' +
                        '<div class="btn-group float-right">' +
                              '<button class="btn btn-danger delete_row" id="nr">' +
                                    '<span class="ion-trash-a"></span>' +
                              '</button> ' +
                              '<button class="btn btn-success" id="add_row" >' +
                                    '<span class="ion-plus"></span>' +
                              '</button>' +
                        '</div>' +
                  '</div>' +
                  '<div class="card-body">' +
                        '<div class="form-row">' +
                              '<div class="form-group col-md-5">' +
                                    '{!! Form::label("NL_text_row_nr", trans("ApplicationForm.nameRowNl")) !!}' +
                                    '{!! Form::text("NL_text_row_nr", "", ["class" => "form-control","required" => "required"]) !!}' +
                              '</div>' +
                              '<div class="form-group col-md-5"> ' +
                                    '{!! Form::label("EN_text_row_nr", trans("ApplicationForm.nameRowEn")) !!} ' +
                                    '{!! Form::text("EN_text_row_nr", "", ["class" => "form-control","required" => "required"]) !!}' +
                              '</div>' +
                        '</div>' +
                        '<div class="form-row">' +
                              '<div class="form-group col-md-5">' +
                                    '{!! Form::label("row_type_nr", trans("ApplicationForm.SelectRowType")) !!}' +
                                    '{!! Form::select("row_type_nr",trans("ApplicationFormRowTypes"),"",["class" => "form-control","required" => "required"]) !!}' +
                              '</div>' +
                        '</div>' +
                        '<div class="form-check"> ' +
                              '{!! Form::checkbox("row_required_nr",0,false,["class" => "form-check-input", "id" => "row_required_nr"]) !!}' +
                              '{!! Form::label("row_required_nr", trans("ApplicationForm.rowRequired"), ["class" => "form-check-label"]) !!}' +
                        '</div>' +
                  '</div>' +
            '</div>' +
      '</div>';
    </script>
@endsection