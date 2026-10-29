@props(['action', 'title', 'confirm', 'button' => 'Remove'])
<div class="dropdown-divider"></div>
<form method="post" action="{{ $action }}" data-confirm-title="{{ $title }}" data-confirm="{{ $confirm }}" data-confirm-button="{{ $button }}">
    @csrf
    @method('DELETE')
    <button type="submit" class="dropdown-item text-danger"><x-heroicon-o-trash class="mr-2" />{{ $slot }}</button>
</form>
