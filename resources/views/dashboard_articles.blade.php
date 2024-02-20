<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            @if (!empty($has_site) && $themePreferences)
                <style>
                    main {

                        background-color: {{ $themePreferences->background_color }} !important;
                        color: {{ $themePreferences->police }} !important;

                    }
                </style>
            @endif
        </h2>
    </x-slot>

    @section('content')
        @if (!empty($has_site))
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-gray overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            @if (isset($error))
                                <p>{{ $error }}</p>
                            @else
                                <!-- Utilisez les données des préférences de menu et de thème pour construire votre site -->
                                @if ($menuPreferences->menuId == 10)
                                    <nav class="flex flex-col items-start">
                                        <ul class="space-y-2">
                                            <li><a href="/dashboard" class="text-gray-800 hover:text-blue-500">Accueil</a>
                                            </li>
                                            <li><a href="/dashboard/articles"
                                                    class="text-gray-800 hover:text-blue-500">Articles</a></li>
                                        </ul>
                                    </nav>
                                @elseif($menuPreferences->menuId == 11)
                                    <nav class="flex items-center justify-center">
                                        <ul class="flex space-x-4">
                                            <li><a href="/dashboard" class="text-gray-800 hover:text-blue-500">Accueil</a>
                                            </li>
                                            <li><a href="/dashboard/articles"
                                                    class="text-gray-800 hover:text-blue-500">Articles</a></li>
                                        </ul>
                                    </nav>
                                @elseif($menuPreferences->menuId == 13)
                                    <!-- Hamburger Menu -->
                                    <div class="hamburger-menu">
                                        <label for="checked" class="menu-icon">&#9776;</label>
                                        <input type="checkbox" id="checked">
                                        <ul class="menu">
                                            <li><a href="/dashboard" class="text-gray-800 hover:text-blue-500">Accueil</a>
                                            </li>
                                            <li><a href="/dashboard/articles"
                                                    class="text-gray-800 hover:text-blue-500">Articles</a></li>
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
                                    <img src="{{ asset('storage/' . $site->menu_image) }}" alt="Menu Image">
                                @else
                                    <p>Aucune image de menu n'est disponible</p>
                                @endif
                            @endif
                        </div>
                        <!-- Partie du body du site  -->
                        <div class="container mx-auto mt-8 flex">
                            <br>

                            <!-- Partie droite du body  -->
                            <div class="w-2/3 pl-4 border-l border-gray-300" id="article-details">
                                <!-- Affichage du premier article par défaut -->
                                @if ($articles->isNotEmpty())
                                    <div class="p-4 border rounded-lg shadow-md">
                                        <div class="flex justify-center" id="article-image">
                                            <img src="{{ asset('storage/' . $articles[0]->image_path) }}"
                                                alt="Image de l'article">
                                        </div>
                                        <h2 class="text-lg font-semibold mb-2 " id="article-title">{{ $articles[0]->title }}
                                        </h2>
                                        <p class="text-gray-700" id="article-content">{{ $articles[0]->content }}</p>
                                        <br>
                                        <!-- Liste des commentaires -->
                                        <div class="mt-4 ">
                                            <!-- Bouton "Voir les commentaires" -->
                                            <button id="toggle-comments-btn"
                                                class="bg-blue-500 text-white px-4 py-2 rounded">Voir les
                                                commentaires</button>

                                            <!-- Liste des commentaires (initialement cachée) -->
                                            <div class="mt-4" id="comment-list" style="display: none;">
                                                <h3 class="text-xl font-semibold mb-2 flex justify-center">Commentaires</h3>
                                                @if ($comments->isNotEmpty())
                                                    <ul class="space-y-2">
                                                        @foreach ($comments as $comment)
                                                            <li>
                                                                <p class="font-semibold">Nom: {{ $comment->nom }}</p>
                                                                <p>{{ $comment->contenu }}</p>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p>Aucun commentaire pour cet article.</p>
                                                @endif
                                                <!-- Formulaire pour un nouveau commentaire -->
                                                <form action="{{ route('comment.store') }}" method="POST" class="mt-4">
                                                    @csrf
                                                    <div class="mb-2">
                                                        <label for="nom" class="block  font-semibold">Nom :</label>
                                                        <input type="text" id="nom" name="nom"
                                                            class="border-gray-300 border w-full py-2 px-3 rounded mt-1">
                                                    </div>
                                                    <div class="mb-2">
                                                        <label for="contenu" class="block  font-semibold">Contenu :</label>
                                                        <textarea id="contenu" name="contenu" class="border-gray-300 border w-full py-2 px-3 rounded mt-1"></textarea>
                                                    </div>
                                                    <input type="hidden" name="article_id" value="{{ $articles[0]->id }}">
                                                    <button type="submit"
                                                        class="bg-blue-500 text-white px-4 py-2 rounded">Ajouter un
                                                        commentaire</button>
                                                </form>
                                            </div>

                                        </div>



                                    </div>
                                @else
                                    <p>Aucun article disponible</p>
                                @endif
                            </div>


                            <!-- Partie gauche du body  -->
                            <div class="w-1/3 pr-4 overflow-y-auto max-h-screen ml-5">
                                <h1 class="text-2xl font-semibold mb-4">Liste des articles</h1>
                                <div class="space-y-4">
                                    <!-- Boucle sur tous les articles -->
                                    @foreach ($articles as $article)
                                        <div class="p-4 border rounded-lg shadow-md article-item "
                                            data-article-id="{{ $article->id }}">
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

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const toggleCommentsBtn = document.getElementById('toggle-comments-btn');
                    const commentList = document.getElementById('comment-list');

                    toggleCommentsBtn.addEventListener('click', function() {
                        if (commentList.style.display === 'none') {
                            // Afficher les commentaires
                            commentList.style.display = 'block';
                            toggleCommentsBtn.textContent = 'Réduire les commentaires';
                        } else {
                            // Masquer les commentaires
                            commentList.style.display = 'none';
                            toggleCommentsBtn.textContent = 'Voir les commentaires';
                        }
                    });
                });

                // Sélectionnez tous les éléments de la liste des articles
                const articleItems = document.querySelectorAll('.article-item');

                // Ajoutez un gestionnaire d'événements à chaque élément de la liste
                articleItems.forEach(articleItem => {
                    articleItem.addEventListener('click', async () => {
                        // Récupérez l'ID de l'article sélectionné
                        const articleId = articleItem.dataset.articleId;
                        try {
                            // Récupérez les détails de l'article à l'aide de l'ID
                            const articleDetails = await getArticleDetails(articleId);
                            console.log(articleDetails);

                            // Mettez à jour l'affichage des détails de l'article
                            displayArticleDetails(articleDetails, articleId);
                        } catch (error) {
                            // Gérer les erreurs
                            console.error('Erreur lors de la récupération des détails de l\'article:', error);
                        }
                    });
                });

                // Fonction pour récupérer les détails de l'article en utilisant son ID (à adapter selon votre backend)
                async function getArticleDetails(articleId) {
                    try {
                        const response = await fetch(`/posts/${articleId}`);
                        if (!response.ok) {
                            throw new Error('Erreur de réponse du serveur.');
                        }
                        const data = await response.json();
                        return data;
                        console.log(data);
                    } catch (error) {
                        // Gérer les erreurs
                        console.error('Erreur lors de la récupération des détails de l\'article:', error);
                        throw error; // Rejette l'erreur pour que le code appelant puisse la gérer
                    }
                }


                // Fonction pour afficher les détails de l'article dans l'interface utilisateur
                function displayArticleDetails(articleDetails, articleId) {


                    const articleTitleElement = document.querySelector('#article-title');
                    const articleImageElement = document.querySelector('#article-image');
                    const articleContentElement = document.querySelector('#article-content');
                    const commentListElement = document.querySelector('#comment-list');
                    const id_article = document.querySelector('input[name="article_id"]');

                    id_article.value = articleId;


                    // Mettez à jour les éléments HTML avec les détails de l'article
                    articleTitleElement.textContent = articleDetails.title;
                    articleContentElement.textContent = articleDetails.content;
                    articleImageElement.innerHTML = `<img src="${articleDetails.image_path}" alt="Image de l'article">`;

                    // Effacez d'abord la liste des commentaires existante
                    commentListElement.innerHTML = '';

                    // Parcourez chaque commentaire et créez des éléments HTML pour les ajouter à la liste
                    articleDetails.comments.forEach(comment => {
                        const commentItem = document.createElement('li');
                        const nomElement = document.createElement('p');
                        nomElement.textContent = 'Nom: ' + comment.nom;
                        nomElement.classList.add('font-semibold');
                        const contenuElement = document.createElement('p');
                        contenuElement.textContent = comment.contenu;

                        // Ajoutez les éléments à l'élément de liste de commentaires
                        commentItem.appendChild(nomElement);
                        commentItem.appendChild(contenuElement);
                        commentListElement.appendChild(commentItem);
                    });

                }
            </script>
        @else
            <div class="flex justify-center items-center h-screen">
                <div class="bg-gray-100 p-8 rounded-md shadow-md">
                    <p class="text-center">
                        Vous n'avez pas de site web. Veuillez en créer un pour accéder à cette page.
                    </p>
                </div>
            </div>

        @endif
    @endsection

</x-app-layout>
