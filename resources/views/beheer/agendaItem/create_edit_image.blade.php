<div class="card mt-4">
    <div class="card-header">
        <h3>{{$fields['title_image']}}</h3>
    </div>
    <div class="card-body">
        <span>{{'Thumbnail image'}}</span>
        <div class="form-group mt-2">
            <div class="custom-file">
                {{ html()->file('thumbnail')
                    ->class('custom-file-input')
                    ->id('thumbnail')
                    ->attributeIf($agendaItem == NULL, 'required', 'required') }}
                {{ html()->label('Choose file', 'customFile')->class('custom-file-label') }}
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
