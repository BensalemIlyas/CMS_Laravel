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
                        <div class="bg-white p-6 rounded-lg shadow-md" id="post-{{ $post->id  }}">
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

        let token = "{{csrf_token()}}";

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
                    let postDetailsHTML = `
                        <div class="border rounded-lg p-4">
                            <img src="${post.image_path}" alt="${post.title}" class="w-full h-64 object-cover mb-4 rounded">
                            <h1 class="text-2xl font-semibold mb-2">${post.title}</h1>
                            <p class="text-gray-600 mb-2">${post.content}</p>
                            <p>Publié le ${post.published_at}</p>
                            <button class="deletePost bg-red-500 text-white px-4 py-2 rounded mt-4 hover:bg-red-600 transition duration-300 ease-in-out">
                                Supprimer
                            </button>
                        </div>`;

                    // Si des commentaires sont associés à cet article, les afficher
                    if (post.comments.length > 0) {
                        postDetailsHTML += `<div class="mt-4 border-t pt-4">
                        <p class="font-semibold text-center" >Les Commentaires :</p>
                        </br>`;
                        post.comments.forEach(comment => {
                            postDetailsHTML += `
                                <div class="mb-2">
                                    <p class="font-semibold">Nom :${comment.nom}</p>
                                    <p>${comment.contenu}</p>
                                    <p id="comment-${postId}-${comment.id}" > Statut: ${comment.statut ? 'Visible' : 'Non visible'}</p>
                                    <button id ="buttonChangerStatut" onclick="changerStatutCommentaire(${postId}, ${comment.id})" class="ml-auto bg-blue-500 text-white px-3 py-1 rounded font-semibold text-center">Changer Statut</button>
                                </div>`;
                        });
                        postDetailsHTML += `</div>`;
                    }

                    // Afficher les détails du post dans la partie droite
                    const postComplet =  document.getElementById('postComplet');
                    postComplet.innerHTML = postDetailsHTML;
                    postComplet.querySelector('.deletePost').addEventListener('click', event => {
                        const form = new FormData();

                        form.append("postId",postId);
                        form.append("_token",token);

                        const options = {
                            method: "post",
                            body: form
                        };

                        fetch("{{route("post.delete")}}",options).then(response => response.json()).then(dataJson => {
                            if(dataJson.success){
                                token = dataJson.token;
                                postComplet.innerHTML = "";
                                const postContainer = document.querySelector(`#post-${postId}`);

                                postContainer.animate({opacity: 0},400).addEventListener("finish",() => {
                                    postContainer.remove();
                                });

                            }else{
                                alert(dataJson.error);
                            }

                        }).catch(error => {
                            console.log(error)
                            alert("Une erreur s'est produitkjhjgfde")
                        });

                    })

                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des détails du post :', error.message);
                });
        }

        function changerStatutCommentaire(postId, commentId) {


            let comment = document.getElementById(`comment-${postId}-${commentId}`);
            let buttonChangerStatut = document.getElementById('buttonChangerStatut');

            fetch(`/comments/${commentId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', // Ajoutez le jeton CSRF
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur de réponse du serveur.');
                }
                return response.json();
            })
            .then(data => {

                let commentStatut = data.statut ? 'Visible' : 'Non visible';
                comment.textContent =  'Statut: ' + commentStatut ;
                // Afficher un message de succès ou mettre à jour l'affichage des commentaires si nécessaire
                console.log('Statut du commentaire changé avec succès:', data);
                // buttonChangerStatut.disabled = true;
                // setTimeout(()=>buttonChangerStatut.disabled = false, 800);

            })
            .catch(error => {
                console.error('Erreur lors du changement de statut du commentaire :', error.message);
            });
    }

    </script>
@endsection
