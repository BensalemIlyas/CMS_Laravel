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

   

    public function show($id){
        try {
            $post = Post::findOrFail($id);

            // Retourner les détails du post au format JSON
            return response()->json([
                'image_path' => asset('storage/' . $post->image_path),
                'title' => $post->title,
                'content' => $post->content,
                'published_at' => $post->published_at,
                // Ajoutez d'autres champs au besoin
            ]);
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            \Log::error('Erreur lors de la récupération des détails du post: ' . $e->getMessage());

            // Renvoyer une réponse JSON avec un message d'erreur
            return response()->json(['error' => 'Erreur interne du serveur.'], 500);
        }
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

        // Création du post
        $post = Post::create($validatedData);

        
         
        // Redirection ou autre logique après la création du post
        return redirect()->route('posts')->with('success', 'Le post a été créé avec succès.');
    }

}
