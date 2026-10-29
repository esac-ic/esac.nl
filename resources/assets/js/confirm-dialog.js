import $ from 'jquery';

/**
 * Asks for confirmation in the page's own dialog (<x-confirm-dialog />), resolving to whether the action was confirmed.
 * Cancel has the focus, so pressing Enter by accident does nothing.
 */
export function confirmDialog({ title = 'Are you sure?', message = '', button = 'Confirm', danger = true }) {
    return new Promise(resolve => {
        const $dialog = $('#confirm-dialog');
        let confirmed = false;

        $dialog.find('#confirm-dialog-title').text(title);
        $dialog.find('#confirm-dialog-message').text(message);
        $dialog.find('#confirm-dialog-confirm')
            .text(button)
            .toggleClass('btn-danger', danger)
            .toggleClass('btn-primary', !danger)
            .off('click')
            .one('click', () => {
                confirmed = true;
                $dialog.modal('hide');
            });
        $dialog.off('shown.bs.modal').one('shown.bs.modal', () => $dialog.find('.modal-footer [data-dismiss=modal]').trigger('focus'));
        $dialog.off('hidden.bs.modal').one('hidden.bs.modal', () => resolve(confirmed));
        $dialog.modal('show');
    });
}
