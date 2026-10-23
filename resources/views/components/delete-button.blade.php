@props(['action', 'title', 'confirm', 'button' => 'Remove'])
<form method="post" action="{{ $action }}" class="page-actions-destructive" data-confirm-title="{{ $title }}" data-confirm="{{ $confirm }}" data-confirm-button="{{ $button }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-outline-danger text-nowrap"><span class="ion-trash-a" aria-hidden="true"></span> {{ $slot->isEmpty() ? $button : $slot }}</button>
</form>
