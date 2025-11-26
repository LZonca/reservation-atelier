<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <h2 class="text-2xl font-semibold mb-4">Créer une salle</h2>

        <form action="{{ url('/salles') }}" method="POST">
            @csrf
            @include('salles._form')

            <div class="mt-4">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded">Créer</button>
                <a href="{{ url('/salles') }}" class="ml-2 text-sm text-gray-600">Annuler</a>
            </div>
        </form>
    </div>
</x-app-layout>

