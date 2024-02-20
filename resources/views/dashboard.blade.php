

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
            @if(!empty($has_site) && ($themePreferences))
                   
                <style>
                    main{
                            
                            background-color: {{$themePreferences->background_color}} !important;
                            color : {{$themePreferences->police}} !important;
                            
                    }
                    
                </style>

            @endif
        </h2>
    </x-slot>

    @section('content')
    @if(!empty($has_site))
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
                                        <li><a href="/dashboard" class="text-gray-800 hover:text-blue-500">Accueil</a></li>
                                        <li><a href="/dashboard/articles" class="text-gray-800 hover:text-blue-500">Articles</a></li>
                                    </ul>
                                </nav>
                            @elseif($menuPreferences->menuId == 11)
                            
                                <nav class="flex items-center justify-center">
                                    <ul class="flex space-x-4">
                                        <li><a href="/dashboard" class="text-gray-800 hover:text-blue-500">Accueil</a></li>
                                        <li><a href="/dashboard/articles" class="text-gray-800 hover:text-blue-500">Articles</a></li>
                                    </ul>
                                </nav>
                            @elseif($menuPreferences->menuId == 13)
                                <!-- Hamburger Menu -->
                                <div class="hamburger-menu">
                                    <label for="checked" class="menu-icon">&#9776;</label>
                                    <input type="checkbox" id="checked">
                                    <ul class="menu">
                                        <li><a href="/dashboard" class="text-gray-800 hover:text-blue-500">Accueil</a></li>
                                        <li><a href="/dashboard/articles" class="text-gray-800 hover:text-blue-500">Articles</a></li>
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
                  
                    </div>
                </div>
            </div>
        </div>

        <script>
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
                    displayArticleDetails(articleDetails,articleId);
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
        function displayArticleDetails(articleDetails,articleId) {
            

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
