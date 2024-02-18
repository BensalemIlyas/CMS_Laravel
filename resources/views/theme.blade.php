@extends('layouts.app') <!-- Assurez-vous que le nom du layout est correct -->

@section('content')
    <div class="container mx-auto mt-8">
        <div class="flex justify-center border-4 border-black pb-4">

            <form id="themeForm" action="{{ route('save.theme') }}" method="POST">
                @csrf
                <div class="mt-4">
                    <label for="police" class="block font-semibold">Police :</label>
                    <input type="color" id="police" name="police" class="border-gray-300 border py-2 px-3 rounded mt-1">
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
                
            </form>

        </div>
    </div>


@endsection
