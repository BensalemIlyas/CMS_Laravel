<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Site;
use App\Models\Post;
use App\Models\Comment;


class DashboardController extends Controller{

    public function site(){
        $user = auth()->user();
        $site = Site::where('user_id', $user->id)->first();
        $articles = Post::all();
        $comments= Comment::where('statut', 0)->where('article_id', $articles[0]->id)->get();

       
        if ($site) {
            $menuPreferences = json_decode($site->menu_preferences);
            $themePreferences = json_decode($site->theme_preferences);
            

            return view('dashboard', compact('menuPreferences', 'themePreferences', 'site', 'articles', 'comments'));
        } else {
            $error = "Aucun site trouvé pour cet utilisateur.";
            return view('dashboard', compact('error'));
        }
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
}