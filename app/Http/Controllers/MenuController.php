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


    public function sauvegarder(Request $request){
        $data = $request->validate([
            'nom' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        
        // Traitement de l'image s'il est téléchargé
        if ($request->hasFile('image')) {
            $data['image']  = $request->file('image')->store('images/menu', 'public');
        }
        // Créez un nouveau menu
        $menu = Menu::create([
            'nom' => $data['nom'],
            'image_path' => $data['image']
            // Ajoutez d'autres champs au besoin
        ]);


        return response()->json(['success' => 'Menu créé avec succès.']);
        alert ("Menu créé avec succès.");
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
        
        $data = $request->validate([
           'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Exemple de validation d'image

        ]);

        // Traitement de l'image s'il est téléchargé
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/menu', 'public');

        }

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
            'menu_image' => $imagePath
            
            // Ajoutez d'autres champs au besoin
        ]);

        return response()->json(['success' => 'Choix de menu sauvegardé avec succès.']);

    }


}