<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Site;


class MenuController extends Controller
{
    
    public function menu()
    {
        $menus = Menu::all();
        $sites = Site::where('user_id', auth()->user()->id)->first();
        return view('menu', compact('menus', 'sites'));
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
                'nom' => $menu->nom,
                'image_path' => asset('storage/' . $menu->image_path)
                
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
        

        $json = [
            "menuId" => $request->menuId,
            "titlesUriMap" => []
        ];
        
        foreach($request->title as $key => $title) $json["titlesUriMap"][$title] = $request->url[$key];

        // Récupérez l'utilisateur authentifié
        $user = auth()->user();

        

        // Créez ou mettez à jour le site associé à l'utilisateur
        $site = $user->site()->updateOrCreate([], [
            'menu_preferences' => json_encode($json)
            
            // Ajoutez d'autres champs au besoin
        ]);

        return response()->json(['success' => 'Choix de menu sauvegardé avec succès.']);

    }

    public function saveImage(Request $request)
    {
        // Validation des données
        $request->validate([
            'siteName' => 'required|string|max:255',
        ]);

        // Récupérer le nom du site depuis la requête
        $siteName = $request->input('siteName');

        // Calculer la largeur de l'image en fonction de la longueur du texte
        $imageWidth = strlen($siteName) * 50; // Ajustez le facteur de multiplication selon votre police et votre taille de texte

        // Créer une nouvelle image avec la largeur calculée
        $image = imagecreatetruecolor($imageWidth, 100); // Ajustez la hauteur selon vos besoins

        // Couleurs
        $backgroundColor = imagecolorallocate($image, 255, 255, 255); // Blanc
        $textColor = imagecolorallocate($image, 0, 0, 0); // Noir

        // Remplir l'image avec la couleur de fond
        imagefill($image, 0, 0, $backgroundColor);

        // Calculer la position horizontale du texte pour le centrer
        $textWidth = imagefontwidth(5) * strlen($siteName); // Largeur du texte en pixels
        $textX = ($imageWidth - $textWidth) / 2; // Position horizontale du texte
        $textY = (100 - $textWidth) / 2; // Position verticale du texte

        // Dessiner le texte au centre de l'image
        imagestring($image, 7, $textX, $textY, $siteName, $textColor); // 5 est la taille de la police, ajustez-la selon vos besoins
      

        // Chemin de stockage de l'image
        $imagePath = 'images/menu/' . $siteName . '.png';

        // Enregistrement de l'image dans le stockage
        imagepng($image, storage_path('app/public/' . $imagePath));
        //imagedestroy($image);

        $user = auth()->user();
        $site = $user->site()->updateOrCreate([], [
            'menu_image' => $imagePath
            
            // Ajoutez d'autres champs au besoin
        ]);

        // Retourner le chemin de l'image sauvegardée
        $menus = Menu::all();
        $sites = Site::where('user_id', auth()->user()->id)->first(); 
        return view('menu',compact('menus', 'sites'))->with('success', 'Image de menu sauvegardée avec succès.');
    }

}