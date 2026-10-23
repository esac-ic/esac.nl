<div class="modal fade" id="export-member-log-modal" tabindex="-1" role="dialog" aria-labelledby="export-member-log-modal-title"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title" id="export-member-log-modal-title">Export the member log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>    
        
            <div class="modal-body">
                <form method="post" action="{{ route('admin.member-log.export') }}">
                    @csrf    
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Types</label>
                            @foreach ($types as $type)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked name="types[]" value="{{ $type->value }}" id="type-{{ $type->value }}">
                                <label class="form-check-label" for="type-{{ $type->value }}">{{ $type->label() }}</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <x-field type="datetime-local" name="start_date" label="Start date" :value="now(config('app.display_timezone'))->subWeeks(1)->toDateTimeLocalString('minute')" col="6" required />
                        <x-field type="datetime-local" name="end_date" label="End date" :value="now(config('app.display_timezone'))->toDateTimeLocalString('minute')" col="6" required />
                    </div>
                    
                    <div class="form-row">
                        <div class="my-4 mx-auto">
                            <button type="submit" class="btn btn-primary px-4"><i class="ion-android-download"></i> Export to excel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
