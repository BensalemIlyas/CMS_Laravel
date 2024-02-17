

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @section('content')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div>
                        @if ($site)
                        <!-- Vérifiez si un chemin d'image est défini -->
                            @if ($site->menu_image)
                                <img  src="{{ asset('storage/' . $site->menu_image) }}" alt="Menu Image">
                            @else
                                <p>Aucune image de menu n'est disponible</p>
                            @endif
                        @endif
                    </div>
                    <div class="p-6 text-gray-900">
                        @if(isset($error))
                            <p>{{ $error }}</p>
                        @else
                            <!-- Utilisez les données des préférences de menu et de thème pour construire votre site -->
                            <h1>njnjnj</h1>
                            @if($menuPreferences->menuId == 1)
                                <nav>
                                    <ul>
                                        @foreach($menuPreferences->titlesUriMap as $title => $url)
                                            <li><a href="{{ $url }}">{{ $title }}</a></li>
                                        @endforeach
                                    </ul>
                                </nav>
                            @elseif($menuPreferences->menuId == 2)
                                <nav class="horizontal-menu">
                                    <ul>
                                        @foreach($menuPreferences->titlesUriMap as $title => $url)
                                            <li><a href="{{ $url }}">{{ $title }}</a></li>
                                        @endforeach
                                    </ul>
                                </nav>
                            @elseif($menuPreferences->menuId == 3)
                                <!-- Hamburger Menu -->
                                <div class="hamburger-menu">
                                    <label for="checked" class="menu-icon">&#9776;</label>
                                    <input type="checkbox" id="checked">
                                    <ul class="menu">
                                        @foreach($menuPreferences->titlesUriMap as $title => $url)
                                            <li><a href="{{ $url }}">{{ $title }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>

                            @endif


                            <!-- Utilisez les autres données du site pour construire le reste de votre page -->
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endsection

</x-app-layout>
