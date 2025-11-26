<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-2xl font-semibold mb-4">Créer un atelier</h2>

        <form action="{{ url('ateliers') }}" method="POST">
            @csrf
            @include('ateliers._form')

            <div class="mt-4">
                <button class="bg-indigo-600 text-white px-4 py-2 rounded">Créer</button>
                <a href="{{ url('ateliers') }}" class="ml-2 text-sm text-gray-600">Annuler</a>
            </div>
        </form>
    </div>
</x-app-layout>
