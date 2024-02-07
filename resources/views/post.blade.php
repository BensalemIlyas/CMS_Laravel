@extends('layouts.app') <!-- Assurez-vous que le nom du layout est correct -->

@section('content')
    <div class="container mx-auto mt-8 flex">

        <!-- Section Liste des Posts ou Formulaire d'Ajout (à gauche) -->
        <div class="w-1/2 pr-4 overflow-y-auto max-h-screen">
            <div class="flex items-center justify-between mb-4">
                <h1 class="text-3xl font-semibold">Liste des Posts</h1>
                <button id="ajouterPostBtn" class="bg-green-500 text-white px-4 py-2 rounded">Ajouter Post</button>
            </div>

            <!-- Si le formulaire n'est pas affiché -->
            <div id="listeDesPosts">
                <!-- Boucle sur les posts (peut être dynamique selon le backend) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($posts as $post)
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="{{ $post->title }}"
                                class="w-full h-32 object-cover mb-2 rounded">
                            <h2 class="text-xl font-semibold mb-2">{{ $post->title }}</h2>
                            <p class="text-gray-600">{{ $post->content }}</p>
                            <p>Publié le {{ $post->published_at }}</p>
                            <button class="text-blue-500 mt-4 inline-block"
                                onclick="afficherPostComplet({{ $post->id }})">Voir plus</button>

                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Si le formulaire est affiché -->
            <div id="formulaireAjoutPost" class="hidden mt-4">
                <!-- Formulaire d'ajout de post ici -->
                <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
                    class="bg-white p-6 rounded-lg shadow-md">
                    @csrf

                    <div class="mb-4">
                        <label for="title" class="block text-gray-600 font-semibold">Titre :</label>
                        <input type="text" id="title" name="title"
                            class="w-full border-b-2 border-gray-300 p-2 focus:outline-none focus:border-green-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="content" class="block text-gray-600 font-semibold">Contenu :</label>
                        <textarea id="content" name="content"
                            class="w-full border-2 border-gray-300 p-2 focus:outline-none focus:border-green-500" required></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="published_at" class="block text-gray-600 font-semibold">Date de Publication :</label>
                        <input type="date" id="published_at" name="published_at" value="{{ now()->toDateString() }}"
                            class="w-full border-b-2 border-gray-300 p-2 focus:outline-none focus:border-green-500"
                            required>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-gray-600 font-semibold">Image :</label>
                        <input type="file" id="image" name="image" accept="image/*"
                            class="w-full border-b-2 border-gray-300 p-2 focus:outline-none focus:border-green-500"
                            required>
                    </div>

                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Ajouter Post</button>
                    <button id="annulerajout"
                        class="bg-red-500 text-white px-4 py-2 rounded mt-4 hover:bg-red-600 transition duration-300 ease-in-out">Annuler</button>
                </form>
            </div>
        </div>

        <!-- Section Affichage Complet (à droite) -->
        <div class="w-1/2 pl-4 border-l border-gray-300" id="postComplet">
            <!-- Le contenu complet du post sera affiché ici -->
            
        </div>

    </div>

    <script>
        document.getElementById('ajouterPostBtn').addEventListener('click', function() {
            // Basculer entre l'affichage de la liste des posts et du formulaire d'ajout
            document.getElementById('listeDesPosts').classList.toggle('hidden');
            document.getElementById('formulaireAjoutPost').classList.toggle('hidden');
        });

        document.getElementById('annulerajout').addEventListener('click', function() {
            // Basculer entre l'affichage de la liste des posts et du formulaire d'ajout
            document.getElementById('formulaireAjoutPost').classList.toggle('hidden');
            document.getElementById('listeDesPosts').classList.toggle('hidden');

        });

        function afficherPostComplet(postId) {
            fetch(`/posts/${postId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur de réponse du serveur.');
                    }
                    return response.json();
                })
                .then(post => {
                    // Construire le HTML pour afficher les détails du post complet
                    const postDetailsHTML = `
                        <div class="border rounded-lg p-4">
                            <img src="${post.image_path}" alt="${post.title}" class="w-full h-64 object-cover mb-4 rounded">
                            <h1 class="text-2xl font-semibold mb-2">${post.title}</h1>
                            <p class="text-gray-600 mb-2">${post.content}</p>
                            <p>Publié le ${post.published_at}</p>
                        </div>`;

                    // Afficher les détails du post dans la partie droite
                    document.getElementById('postComplet').innerHTML = postDetailsHTML;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des détails du post :', error.message);
                });
        }

    </script>
@endsection
