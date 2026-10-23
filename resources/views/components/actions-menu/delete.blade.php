@props(['action', 'title', 'confirm', 'button' => 'Remove'])
<div class="dropdown-divider"></div>
<form method="post" action="{{ $action }}" data-confirm-title="{{ $title }}" data-confirm="{{ $confirm }}" data-confirm-button="{{ $button }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="dropdown-item text-danger"><span class="ion-trash-a mr-2" aria-hidden="true"></span>{{ $slot }}</button>
</form>
