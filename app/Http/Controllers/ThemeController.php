<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ThemeController extends Controller
{
    public function theme()
    {
        $themes = Theme::all();
        return view('theme', compact('themes'));
    }

    public function show($id)
    {
       try {
            $theme = Theme::findOrFail($id);

            // Retourner les détails du menu au format JSON
            return response()->json([
                'name' => $theme->name,
                'police' => $theme->police,
                'background-color' => $theme->background_color,
                'couleur-sep' => $theme->separation_color

                // Ajoutez d'autres champs au besoin
            ]);
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            \Log::error('Erreur lors de la récupération des détails du thème : ' . $e->getMessage());

            // Renvoyer une réponse JSON avec un message d'erreur
            return response()->json(['error' => 'Erreur interne du serveur.'], 500);
        }
    }

    public function save( Request $request){
        $json = [
            "police" => $request->police,
            "background-color" => $request->background_color,
            "couleur-sep" => $request->separation_color
        ];

        $user = auth()->user();

        // Créez ou mettez à jour le site associé à l'utilisateur
        $site = $user->site()->updateOrCreate([], [
            'theme_preferences' => json_encode($json)

            // Ajoutez d'autres champs au besoin
        ]);
        
         return view('theme');

    }


}
