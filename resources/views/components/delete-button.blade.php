@props(['action', 'confirm'])
<form method="post" action="{{ $action }}" onsubmit="return confirm('{{ $confirm }}');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger"><em class="ion-trash-a"></em> {{ $slot->isEmpty() ? 'Remove' : $slot }}</button>
</form>
