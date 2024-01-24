<x-app-layout>
    @extends('layouts.app')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>
   
    @section('content')
        <h1>Liste des Posts</h1>

        @foreach($posts as $post)
            <div class="flex items-center">
                <img src="CMS_Laravel\resources\views\layouts\img\1.jpg">
                <strong>Andrew Alfred</strong>
                <span>Technical advisor</span>
                <h2>{{ $post->title }}</h2>
                <p>{{ $post->content }}</p>
                <p>Publié le {{ $post->published_at }}</p>
                
            </div>
            <hr>
        @endforeach
    @endsection
</x-app-layout>


