{{-- The one confirmation dialog: forms with data-confirm open it, and scripts call window.confirmDialog(). --}}
<div class="modal fade" id="confirm-dialog" tabindex="-1" role="dialog" aria-labelledby="confirm-dialog-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirm-dialog-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p class="mb-0" id="confirm-dialog-message"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-dialog-confirm"></button>
            </div>
        </div>
    </div>
</div>
