

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-gray overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        @if(isset($error))
                            <p>{{ $error }}</p>
                        @else
                            <!-- Utilisez les données des préférences de menu et de thème pour construire votre site -->
                            @if($menuPreferences->menuId == 10)
                                <nav class="flex flex-col items-start">
                                    <ul class="space-y-2">
                                        @foreach($menuPreferences->titlesUriMap as $title => $url)
                                            <li><a href="{{ $url }}" class="text-gray-800 hover:text-blue-500">{{ $title }}</a></li>
                                        @endforeach
                                    </ul>
                                </nav>
                            @elseif($menuPreferences->menuId == 11)
                                <nav class="flex items-center justify-center">
                                    <ul class="flex space-x-4">
                                        @foreach($menuPreferences->titlesUriMap as $title => $url)
                                            <li><a href="{{ $url }}" class="text-gray-800 hover:text-blue-500">{{ $title }}</a></li>
                                        @endforeach
                                    </ul>
                                </nav>
                            @elseif($menuPreferences->menuId == 13)
                                <!-- Hamburger Menu -->
                                <div class="hamburger-menu">
                                    <label for="checked" class="menu-icon">&#9776;</label>
                                    <input type="checkbox" id="checked">
                                    <ul class="menu">
                                        @foreach($menuPreferences->titlesUriMap as $title => $url)
                                             <li><a href="{{ $url }}" class="text-gray-800 hover:text-blue-500">{{ $title }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>

                            @endif


                            <!-- Utilisez les autres données du site pour construire le reste de votre page -->
                        @endif
                    </div>
                    <div class="flex justify-center ">
                        @if ($site)
                        <!-- Vérifiez si un chemin d'image est défini -->
                            @if ($site->menu_image)
                                <img  src="{{ asset('storage/' . $site->menu_image) }}" alt="Menu Image">
                            @else
                                <p>Aucune image de menu n'est disponible</p>
                            @endif
                        @endif
                    </div>
                    <!-- Partie du body du site  -->
                    <div class="container mx-auto mt-8 flex">
                        <br>

                        <!-- Partie droite du body  -->
                        <div class="w-2/3 pl-4 border-l border-gray-300">
                            <!-- Affichage du premier article par défaut -->
                            @if($articles->isNotEmpty())
                            <div class="p-4 border rounded-lg shadow-md">
                                <h2 class="text-lg font-semibold mb-2">{{ $articles[0]->title }}</h2>
                                <p class="text-gray-700">{{ $articles[0]->content }}</p>

                                <!-- Liste des commentaires -->
                                <div class="mt-4">
                                    <h3 class="text-xl font-semibold mb-2">Commentaires</h3>
                                    
                                    @if($comments->isNotEmpty())
                                    <ul class="space-y-2">
                                        @foreach($comments as $comment)
                                        <li>
                                            <p class="font-semibold">{{ $comment->nom }}</p>
                                            <p>{{ $comment->contenu }}</p>
                                        </li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p>Aucun commentaire pour cet article.</p>
                                    @endif
                                </div>

                               

                            </div>
                            @else
                            <p>Aucun article disponible</p>
                            @endif
                        </div>


                        <!-- Partie gauche du body  -->
                        <div class="w-1/3 pr-4 overflow-y-auto max-h-screen">
                            <h1 class="text-2xl font-semibold mb-4">Liste des articles</h1>
                            <div class="space-y-4">
                                <!-- Boucle sur tous les articles -->
                                @foreach($articles as $article)
                                <div class="p-4 border rounded-lg shadow-md">
                                    <h2 class="text-lg font-semibold mb-2">{{ $article->title }}</h2>
                                    <p class="text-gray-700">{{ $article->content }}</p>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection

</x-app-layout>
