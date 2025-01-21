<div class="card mt-4">
    <div class="card-header">
        <h3>{{$fields['title_image']}}</h3>
    </div>
    <div class="card-body">
        <span>{{'Thumbnail image'}}</span>
        <div class="form-group mt-2">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="thumbnail" name="thumbnail" @if($agendaItem == NULL)required="required"@endif>
              <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('.custom-file-input').on('change', function() { 
           let fileName = $(this).val().split('\\').pop(); 
           $(this).next('.custom-file-label').addClass("selected").html(fileName); 
        });
    </script>
@endpush