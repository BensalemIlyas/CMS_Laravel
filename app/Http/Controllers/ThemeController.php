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
        $background_color ='#f3f1f1';
        $police ='#121212' ;
        $couleur_sep = '#29b9d6';
        return view('theme', compact('themes','background_color','police','couleur_sep'));
    }

    public function show($id)
    {
       try {
            $theme = Theme::findOrFail($id);

            // Retourner les détails du menu au format JSON
            return response()->json([
                'name' => $theme->name,
                'police' => $theme->police,
                'background_color' => $theme->background_color,
                'couleur_sep' => $theme->separation_color

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
            "background_color" => $request->background_color,
            "couleur_sep" => $request->separation_color
        ];

        $user = auth()->user();

        // Créez ou mettez à jour le site associé à l'utilisateur
        $site = $user->site()->updateOrCreate([], [
            'theme_preferences' => json_encode($json)

            // Ajoutez d'autres champs au besoin
        ]);
        $user->update(['site' => true]);

        $background_color = $request->background_color;
        $police = $request->police;
        $couleur_sep = $request->separation_color;
        return view('theme', compact('background_color','police','couleur_sep'));

    }


}
