<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;


class MenuController extends Controller
{
    
    public function menu()
    {
        $menus = Menu::all();
        return view('menu', compact('menus'));
    }

    public function show($id){
        try {
            $menu = Menu::findOrFail($id);

            // Retourner les détails du menu au format JSON
            return response()->json([
                'nom' => $menu->nom
                // Ajoutez d'autres champs au besoin
            ]);
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            \Log::error('Erreur lors de la récupération des détails du menu: ' . $e->getMessage());

            // Renvoyer une réponse JSON avec un message d'erreur
            return response()->json(['error' => 'Erreur interne du serveur.'], 500);
        }
    }

    public function save(Request $request)
    {
        // // Validez et traitez les données du formulaire
        // $request->validate([
        //     'menu_id' => 'required|integer|min:1',
        //     'num_titles' => 'required|integer|min:1',
        //     'titles.*' => 'required|string',
        // ]);

        // Construisez le JSON du menu en fonction des informations du formulaire
        $menuJson = $request->getContent();

        $json = [
            "menuId" => $request->menuId,
            "titlesUriMap" => []
        ];
        
        foreach($request->title as $key => $title) $json["titlesUriMap"][$title] = $request->url[$key];

        // Récupérez l'utilisateur authentifié
        $user = auth()->user();

        // Créez ou mettez à jour le site associé à l'utilisateur
        $site = $user->site()->updateOrCreate([], [
            'menu_preferences' => json_encode($json),
            // Ajoutez d'autres champs au besoin
        ]);

        return response()->json(['success' => 'Choix de menu sauvegardé avec succès.']);
    }


}