@extends('layouts.app') <!-- Assurez-vous que le nom du layout est correct -->

@section('content')
    <div class="container mx-auto mt-8 flex">

        <!-- Section Liste des Menus (à gauche) -->
        <div class="w-1/2 pr-4 overflow-y-auto max-h-screen">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-semibold">Choix du Thème</h1>
            </div>

            <!-- Si le formulaire n'est pas affiché -->
            <div id="listeDesThemes">
                <!-- Boucle sur les posts (peut être dynamique selon le backend) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($themes as $theme)
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <img src="{{ asset('storage/' . $theme->image_path) }}" alt="{{ $theme->name }}"
                                class="w-full h-32 object-cover mb-2 rounded">
                            <h2 class="text-xl font-semibold mb-2">{{ $theme->name }}</h2>
                            <button class="text-blue-500 mt-4 inline-block"
                                onclick="afficherThemeComplet({{ $theme->id }})">Choisir</button>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Section Affichage Complet (à droite) -->
        <div class="w-1/2 pl-4 border-l border-gray-300" id="themeComplet">
            <!-- Le contenu complet du post sera affiché ici -->
            
        </div>

    </div>

    <script>
        function afficherThemeComplet(themeId) {
            fetch(`/theme/${themeId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur de réponse du serveur.');
                    }
                    return response.json();
                })
                .then(theme => {
                    // Construire le HTML pour afficher les détails du thème complet
                    let themeDetailsHTML = `
                        <div class="border rounded-lg p-4">
                            <h1 class="text-2xl font-semibold mb-2">${theme.name}</h1>
                            <img src="${theme.image_path}" alt="${theme.name}" class="w-full h-64 object-cover mb-4 rounded">
                        </div>`;

                    // Ajouter un formulaire pour spécifier les détails du thème
                    themeDetailsHTML += `
                        <form id="themeForm" action="{{ route('save.theme') }}" method="POST">
                            @csrf
                            <div class="mt-4">
                                <label for="police" class="block font-semibold">Police :</label>
                                <input type="text" id="police" name="police" class="border-gray-300 border py-2 px-3 rounded mt-1">
                            </div>
                            <div class="mt-4">
                                <label for="background_color" class="block font-semibold">Couleur de l'arrière-plan :</label>
                                <input type="color" id="background_color" name="background_color" class="border-gray-300 border py-2 px-3 rounded mt-1">
                            </div>
                            <div class="mt-4">
                                <label for="separation_color" class="block font-semibold">Couleur de la séparation :</label>
                                <input type="color" id="separation_color" name="separation_color" class="border-gray-300 border py-2 px-3 rounded mt-1">
                            </div>
                            <button type="submit" id="enregistrement" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Enregistrer</button>
                        </form>`;

                    // Afficher les détails du thème dans la partie droite
                    document.getElementById('themeComplet').innerHTML = themeDetailsHTML;

                    // Écouter l'événement de soumission du formulaire pour enregistrer le thème
                    document.getElementById('themeForm').addEventListener('submit', (event) => {
                        event.preventDefault(); // Empêcher le comportement par défaut du formulaire
                        const formData = new FormData(document.getElementById('themeForm'));
                        // Envoyer les données du formulaire à votre route
                        fetch('/save-theme', {
                            method: 'POST',
                            body: formData
                        })
            
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Erreur de réponse du serveur.');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Réponse du serveur :', data);
                        })
                        .catch(error => {
                            console.error('Erreur lors de la soumission du formulaire :', error.message);
                        });
                    });
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des détails du thème :', error.message);
                });
        }
    </script>
@endsection
