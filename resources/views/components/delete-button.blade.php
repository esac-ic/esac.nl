@props(['action', 'confirm', 'icon' => false])
{{-- With icon, only the trash icon shows, and the slot is its title. --}}
<form method="post" action="{{ $action }}" onsubmit="return confirm(@js($confirm));" @class(['d-inline' => $icon])>
    @csrf
    @method('DELETE')
    @if ($icon)
        <button type="submit" class="btn btn-link p-0"><span title="{{ $slot }}" class="ion-trash-a" aria-hidden="true"></span></button>
    @else
        <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{ $slot->isEmpty() ? 'Remove' : $slot }}</button>
    @endif
</form>
