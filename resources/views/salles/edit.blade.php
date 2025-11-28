<x-app-layout>
    <div class="max-w-3xl mx-auto p-4 sm:p-6 lg:p-8">
        <div class="bg-white rounded shadow p-6">
            <h2 class="text-2xl font-semibold mb-4">Éditer la salle</h2>

            <form action="{{ url('/salles', [$salle->getKey()]) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                @include('salles._form')

                <div class="mt-4 flex items-center space-x-2">
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Mettre à jour</button>
                    <a href="{{ url('/salles') }}" class="text-sm text-gray-600">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
