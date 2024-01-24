

@extends('layouts.app')

@section('content')
    <h1>Détails du Post</h1>

    <div>
        <h2>{{ $post->title }}</h2>
        <p>{{ $post->content }}</p>
        <p> Publié le {{ $post->published_at }}</p>
    </div>

    <h2>Commentaires</h2>
    @foreach($post->comments as $comment)
        <p>{{ $comment->content }}</p>
        <p>Par : {{ $comment->user->name }} le {{ $comment->created_at }}</p>
        <hr>
    @endforeach

    <!-- Formulaire pour ajouter un commentaire a faire avec la creation de route,controlleur,model -->
    <form method="post" action="{{ url('/comments') }}">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <label for="content">Ajouter un commentaire :</label>
        <textarea name="content" id="content" cols="30" rows="5" required></textarea>
        <button type="submit">Ajouter</button>
    </form>
@endsection
