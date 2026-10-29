@props(['title', 'model' => null, 'resource' => null, 'action' => null, 'method' => null, 'cancel' => null, 'submit' => 'Save'])
<x-model-form :$model :$resource :$action :$method :$cancel :$submit>
    <x-card :$title>
        {{ $slot }}
    </x-card>
</x-model-form>
