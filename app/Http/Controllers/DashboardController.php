<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Site;


class DashboardController extends Controller{

    public function site(){
        $user = auth()->user();
        $site = Site::where('user_id', $user->id)->first();

        if ($site) {
            $menuPreferences = json_decode($site->menu_preferences);
            $themePreferences = json_decode($site->theme_preferences);
            

            return view('dashboard', compact('menuPreferences', 'themePreferences'));
        } else {
            $error = "Aucun site trouvé pour cet utilisateur.";
            return view('dashboard', compact('error'));
        }
    }
}