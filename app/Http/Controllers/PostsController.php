<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostsController extends Controller
{
    //

    public function post()
    {
        $posts = Post::all();
        return view('post', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('posts.show', compact('post'));
    }

    public function store(Request $request)
    {
        // Validation des données du formulaire
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'published_at' => 'required|date',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Exemple de validation d'image
        ]);

        
        // Traitement de l'image s'il est téléchargé
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/posts', 'public');
            $validatedData['image_path'] = $imagePath;
        }

        // Ajout de la date de publication aux données validées
        $validatedData['published_at'] = $request->input('published_at');
         
        // Création du post avec les données validées
        $post = Post::create($validatedData);

        // Redirection ou autre logique après la création du post
        return redirect()->route('posts')->with('success', 'Le post a été créé avec succès.');
    }

}
