@extends('layouts.app') <!-- Assurez-vous que le nom du layout est correct -->

@section('content')
    <div class="container mx-auto mt-8 flex">
        <!-- Section Liste des Menus (à gauche) -->
        <div class="w-1/2 pr-4 overflow-y-auto max-h-screen">

        <form id="siteForm" action="{{ route('save.site') }}" method="POST">
            @csrf
            <div class="mb-4 ml-5">
                <label for="siteName" class="block text-gray-600 font-semibold">Nom du site :</label>
                <input type="text" id="siteName" name="siteName" class="border-gray-300 border  py-2 px-3 rounded mt-1 mr-4">
                <button type="submit" id="valider" class="bg-blue-500 text-white px-4 py-2 rounded">Valider</button>
            </div>
            <div class="mt-4">

            </div>
            <br>

                <div class="flex justify-center">
                    @if ($sites)
                <!-- Vérifiez si un chemin d'image est défini -->
                    @if ($sites->menu_image)
                        <img  src="{{ asset('storage/' . $sites->menu_image) }}" alt="Menu Image">
                    @else
                        <p>Aucune image de menu n'est disponible</p>
                    @endif
                @endif
                </div>
                <br>

        </form>

            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-semibold">Choix du Menu</h1>
            </div>

            <!-- Si le formulaire n'est pas affiché -->
            <div id="listeDesMenus">
                <!-- Boucle sur les posts (peut être dynamique selon le backend) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($menus as $menu)
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->nom }}"
                                class="w-full h-32 object-cover mb-2 rounded">
                            <h2 class="text-xl font-semibold mb-2">{{ $menu->nom }}</h2>
                            <button class="text-blue-500 mt-4 inline-block"
                                onclick="afficherMenuComplet({{ $menu->id }})">Choisir</button>
                        </div>
                    @endforeach
                </div>

            </div>

        </div>

        <!-- Section Affichage Complet (à droite) -->
        <div class="w-1/2 pl-4 border-l border-gray-300" id="menuComplet">
            <!-- Le contenu complet du post sera affiché ici -->

        </div>








    </div>

    <script>

        function afficherMenuComplet(menuId) {
            fetch(`/menu/${menuId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur de réponse du serveur.');
                    }
                    return response.json();
                })
                .then(menu => {
                    // Construire le HTML pour afficher les détails du menu complet
                    let menuDetailsHTML = `
                        <div class="border rounded-lg p-4">
                            <h1 class="text-2xl font-semibold mb-2">${menu.nom}</h1>
                            <img src="${menu.image_path}" alt="${menu.nom}" class="w-full h-64 object-cover mb-4 rounded">
                        </div>`;




                    // Ajouter un formulaire pour encapsuler les champs d'entrée
                    menuDetailsHTML += `
                        <form id="menuForm" action={{ route('save.menu') }} method="POST">
                            @csrf
                            </br>

                            </br>
                            <input type="hidden" name="menuId" value="${menuId}">

                            <div class="mt-4">
                                <button type="submit" id="enregistrement" class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
                            </div>

                        </form>`;

                    // Afficher les détails du menu dans la partie droite
                    document.getElementById('menuComplet').innerHTML = menuDetailsHTML;

                    // Écouter l'événement de soumission du formulaire pour enregistrer le menu
                    document.getElementById('menuForm').addEventListener('submit', (event) => {
                        event.preventDefault(); // Empêcher le comportement par défaut du formulaire
                        const formData = new FormData(document.getElementById('menuForm'));
                        // console.log(formJson); // Afficher les données du formulaire en format JSON
                        // Envoyer les données du formulaire à votre route
                         fetch('/save-menu', {
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
                             alert('Choix effectuée avec succès !');
                             window.location.reload();
                         })
                         .catch(error => {
                             console.error('Erreur lors de la soumission du formulaire :', error.message);
                         });
                    });
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des détails du menu :', error.message);
                });
        }



    </script>
@endsection
