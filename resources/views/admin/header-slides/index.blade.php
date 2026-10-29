@extends('layouts.admin')

@section('title')
Header slides
@endsection

@section('content')
    <x-page-header title="Header slides">
        <x-button-link :href="route('admin.header-slides.create')" icon="plus">New slide</x-button-link>
    </x-page-header>
    <p class="text-muted">The site header shows one of these photos at random; the home page also shows its heading, message and link.</p>
    <x-data-table :columns="['Photo', 'Heading', 'Link', 'Actions']">
        @foreach ($headerSlides as $headerSlide)
            <tr>
                <td><img src="{{ $headerSlide->getFirstMediaUrl('image', 'wide') }}" alt="" class="img-thumbnail" width="160"></td>
                <td>{{ $headerSlide->heading }}</td>
                <td>{{ $headerSlide->link }}</td>
                <td>
                    <x-actions-menu :label="$headerSlide->heading">
                        <x-actions-menu.item :href="route('admin.header-slides.edit', $headerSlide)" icon="pencil-square">Edit</x-actions-menu.item>
                        <x-actions-menu.delete :action="route('admin.header-slides.destroy', $headerSlide)" title="Remove slide" :confirm="'Remove the slide '.$headerSlide->heading.'?'">Remove</x-actions-menu.delete>
                    </x-actions-menu>
                </td>
            </tr>
        @endforeach
    </x-data-table>
@endsection
