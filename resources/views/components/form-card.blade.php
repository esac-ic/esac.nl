@props(['title', 'model' => null, 'resource' => null, 'action' => null, 'method' => null, 'cancel' => null])
<x-model-form :$model :$resource :$action :$method :$cancel>
    <x-card :$title>
        {{ $slot }}
    </x-card>
</x-model-form>
