<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tableau de bord
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <p>Bienvenue, {{ auth()->user()->name ?? 'Administrateur' }}.</p>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="p-4 bg-gray-50 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Ateliers</div>
                            <div class="text-2xl font-bold">{{ number_format($ateliers ?? 0) }}</div>
                            <a href="/ateliers" class="text-sm text-blue-600">Gérer</a>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Réservations</div>
                            <div class="text-2xl font-bold">{{ number_format($reservations ?? 0) }}</div>
                            <a href="/reservations" class="text-sm text-blue-600">Voir</a>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Clients</div>
                            <div class="text-2xl font-bold">{{ number_format($clients ?? 0) }}</div>
                            <a href="/clients" class="text-sm text-blue-600">Voir</a>
                        </div>

                        <div class="p-4 bg-gray-50 rounded-lg text-center">
                            <div class="text-sm text-gray-500">Intervenants</div>
                            <div class="text-2xl font-bold">{{ number_format($intervenants ?? 0) }}</div>
                            <a href="/intervenants" class="text-sm text-blue-600">Voir</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
