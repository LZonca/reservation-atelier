<div class="max-w-7xl mx-auto px-4 py-8" x-data="atelierApp()" x-init="fetchAteliers()">
    <h2 class="text-3xl font-bold text-center mb-8">Ateliers Disponibles</h2>

    <!-- Loading State -->
    <div x-show="loading" class="text-center py-12">
        <p class="text-gray-500">Chargement des ateliers...</p>
    </div>

    <!-- Error State -->
    <div x-show="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <p x-text="error"></p>
    </div>

    <!-- Ateliers Grid -->
    <div x-show="!loading && !error" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="atelier in ateliers" :key="atelier.id">
            <article class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="h-44 bg-gray-100">
                    <img :src="`https://picsum.photos/seed/${atelier.id}/600/400`"
                         :alt="atelier.nom"
                         class="w-full h-full object-cover">
                </div>
                <div class="p-4">
                    <h3 class="text-xl font-semibold mb-2" x-text="atelier.nom"></h3>
                    <p class="text-gray-600 text-sm mb-3" x-text="truncate(atelier.description, 120)"></p>

                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>Date: <span x-text="atelier.date || 'À planifier'"></span></span>
                        <span class="font-semibold text-gray-800">
                            <span x-text="atelier.prix || 'Gratuit'"></span> €
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        @auth
                            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                                Réserver
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-md text-sm">
                                Connectez-vous pour réserver
                            </a>
                        @endauth

                        <a href="#" class="text-indigo-600 hover:underline text-sm">En savoir +</a>
                    </div>
                </div>
            </article>
        </template>

        <!-- Empty State -->
        <div x-show="ateliers.length === 0 && !loading && !error" class="col-span-full text-center text-gray-500">
            <p>Aucun atelier disponible pour le moment.</p>
        </div>
    </div>
</div>

<script>
    function atelierApp() {
        return {
            ateliers: [],
            loading: true,
            error: null,

            async fetchAteliers() {
                try {
                    const response = await fetch('/api/ateliers', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        }
                    });

                    if (!response.ok) {
                        throw new Error(`Erreur HTTP: ${response.status}`);
                    }

                    const data = await response.json();

                    // Si vous utilisez AtelierResource, les données sont dans data.data
                    this.ateliers = data.data || data;

                } catch (err) {
                    this.error = `Impossible de charger les ateliers: ${err.message}`;
                    console.error('Erreur fetch:', err);
                } finally {
                    this.loading = false;
                }
            },

            truncate(text, length) {
                if (!text) return '';
                return text.length > length ? text.substring(0, length) + '...' : text;
            }
        }
    }
</script>
