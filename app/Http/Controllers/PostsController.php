<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class PostsController extends Controller
{
    //

    public function post()
    {
        $posts = Post::all();
        return view('post', compact('posts'));
    }

    public function deletePost(Request $request){
        $response = ["success" => true,"token" => csrf_token()];
        try{
            Post::destroy($request->postId);
        }
        catch(\Throwable $e){
            $response["success"] = false;
            $response["errorMessage"] = $e->getMessage();
        }

        return response()->json($response);
    }


    public function show($id){
        try {
            $post = Post::findOrFail($id);
            $comments= Comment::where('article_id', $id)->get();

            // Retourner les détails du post au format JSON
            return response()->json([
                'image_path' => asset('storage/' . $post->image_path),
                'title' => $post->title,
                'content' => $post->content,
                'published_at' => $post->published_at,
                'comments' => $comments
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


    public function toggleStatus($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            $comment->statut = !$comment->statut; // Inversion du statut
            $comment->save();

            return response()->json([
                'success' => true,
                'message' => 'Statut du commentaire mis à jour avec succès',
                'statut' => $comment->statut]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour du statut du commentaire.'], 500);
        }
    }

    public function getStatus($id)
    {
        try {
            $comment = Comment::findOrFail($id);
            return response()->json([
                'success' => true,
                'message' => 'Statut du commentaire mis à jour avec succès',
                'commentStatut' => $comment->statut]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la mise à jour du statut du commentaire.'], 500);
        }
    }

}
