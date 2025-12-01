<x-app-layout>
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold">{{ $atelier->nom ?? $atelier->titre ?? 'Atelier' }}</h2>
            <div class="mt-2 text-sm text-gray-600">{{ optional($atelier->date)->format('Y-m-d H:i') ?? '' }}</div>
            <p class="mt-4 text-gray-700">{{ $atelier->description ?? '' }}</p>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                <div><strong>Durée :</strong> {{ $atelier->duree ?? '—' }}h</div>
                <div><strong>Prix :</strong> {{ isset($atelier->prix) ? number_format($atelier->prix,2) . ' €' : '—' }}</div>
                <div>
                    @if(!empty($atelier->vip))
                        <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded">VIP</span>
                    @endif
                </div>
            </div>

            <div class="mt-4 text-sm text-gray-700">
                @if(!empty($atelier->intervenant))
                    @php
                        $inter = $atelier->intervenant;
                        $inter_nom = $inter['nom'] ?? ($inter->nom ?? '');
                        $inter_prenom = $inter['prenom'] ?? ($inter->prenom ?? '');

                        // Les infos de contact sont dans infoContact embeded
                        $infoContact = $inter['infoContact'] ?? ($inter->infoContact ?? null);
                        $inter_email = $infoContact['email'] ?? ($infoContact->email ?? null);
                        $inter_tel = $infoContact['telephone'] ?? ($infoContact->telephone ?? null);

                        // réseaux et site (peuvent être absents)
                        $inter_website = $infoContact['website'] ?? ($infoContact->website ?? null);
                        $inter_youtube = $infoContact['youtube'] ?? ($infoContact->youtube ?? null);
                        $inter_instagram = $infoContact['instagram'] ?? ($infoContact->instagram ?? null);
                        $inter_facebook = $infoContact['facebook'] ?? ($infoContact->facebook ?? null);
                        $inter_twitter = $infoContact['twitter'] ?? ($infoContact->twitter ?? null);
                        $inter_pinterest = $infoContact['pinterest'] ?? ($infoContact->pinterest ?? null);
                        $inter_bluesky = $infoContact['bluesky'] ?? ($infoContact->bluesky ?? null);
                    @endphp

                    <div class="flex items-start justify-between">
                        <div>
                            <div><strong>Intervenant :</strong> {{ $inter_prenom }} {{ $inter_nom }}</div>
                            <div class="text-xs text-gray-500">
                                @if($inter_email){{ $inter_email }}@endif @if($inter_email && $inter_tel) — @endif @if($inter_tel){{ $inter_tel }}@endif
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            @if($inter_email)
                                <a href="mailto:{{ $inter_email }}" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-heroicon-o-envelope class="w-4 h-4" />
                                    Email
                                </a>
                            @endif
                            @if($inter_tel)
                                <a href="tel:{{ preg_replace('/\s+/', '', $inter_tel) }}" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-heroicon-o-phone class="w-4 h-4" />
                                    Appeler
                                </a>
                            @endif
                            @if($inter_website)
                                <a href="{{ $inter_website }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-heroicon-o-globe-alt class="w-4 h-4" />
                                    Site
                                </a>
                            @endif
                            @if($inter_youtube)
                                <a href="{{ $inter_youtube }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-red-100 hover:bg-red-200 text-red-800 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-si-youtube class="w-4 h-4" />
                                    YouTube
                                </a>
                            @endif
                            @if($inter_instagram)
                                <a href="{{ $inter_instagram }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-pink-100 hover:bg-pink-200 text-pink-800 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-si-instagram class="w-4 h-4" />
                                    Instagram
                                </a>
                            @endif
                            @if($inter_facebook)
                                <a href="{{ $inter_facebook }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-si-facebook class="w-4 h-4" />
                                    Facebook
                                </a>
                            @endif
                            @if($inter_twitter)
                                <a href="{{ $inter_twitter }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-blue-50 hover:bg-blue-100 text-blue-700 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-si-x class="w-4 h-4" />
                                    Twitter/X
                                </a>
                            @endif
                            @if($inter_pinterest)
                                <a href="{{ $inter_pinterest }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-si-pinterest class="w-4 h-4" />
                                    Pinterest
                                </a>
                            @endif
                            @if($inter_bluesky)
                                <a href="{{ $inter_bluesky }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1 bg-sky-50 hover:bg-sky-100 text-sky-700 px-3 py-1.5 rounded text-xs font-medium transition-colors">
                                    <x-si-bluesky class="w-4 h-4" />
                                    Bluesky
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                @if(!empty($atelier->salle))
                    <div class="mt-2"><strong>Salle :</strong> {{ $atelier->salle->nom ?? ($atelier->salle_id ?? '—') }}</div>
                @endif
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <a href="{{ url('/ateliers/' . $atelier->getKey() . '/edit') }}" class="text-indigo-600">Éditer</a>
                <a href="{{ url('/ateliers') }}" class="text-gray-600">Retour</a>
            </div>
        </div>

    {{-- Réservations pour cet atelier --}}
    <div class="max-w-3xl mx-auto mt-6">
        <h3 class="text-lg font-semibold mb-3">Réservations</h3>

        {{-- Formulaire rapide pour ajouter une réservation directement depuis la page de l'atelier --}}
        <div class="bg-white rounded shadow p-4 mb-4">
            <form action="{{ url('/ateliers/' . $atelier->getKey() . '/reservations') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-2 items-end">
                @csrf
                <div>
                    <label class="text-xs text-gray-600">Client (nom)</label>
                    <input type="text" name="client_name" class="mt-1 block w-full border rounded px-2 py-1 text-sm" placeholder="Nom du client">
                </div>
                <div>
                    <label class="text-xs text-gray-600">Nb personnes *</label>
                    <input type="number" name="nbPersonne" required min="1" class="mt-1 block w-full border rounded px-2 py-1 text-sm" value="1">
                </div>
                <div>
                    <label class="text-xs text-gray-600">Prix</label>
                    <input type="number" name="prix" step="0.01" class="mt-1 block w-full border rounded px-2 py-1 text-sm" placeholder="ex: 25.00">
                </div>
                <div class="sm:col-span-1">
                    <button class="bg-indigo-600 text-white px-4 py-2 rounded">Ajouter</button>
                </div>
            </form>
        </div>

        @if(isset($reservations) && $reservations->count())
            <div class="space-y-3">
                @foreach($reservations as $res)
                    <div class="bg-white rounded shadow p-3 flex items-center justify-between">
                        <div>
                            <div class="font-semibold">Réservation #{{ $res->_id }}</div>
                            <div class="text-sm text-gray-500">Client: {{ $res->client ? $res->client->prenom . ' ' . $res->client->nom : ($res->client_name ?? '—') }}</div>
                            <div class="text-sm text-gray-500">Nb personnes: {{ $res->nbPersonne ?? ($res->nb_personne ?? '—') }}</div>
                        </div>
                        <div class="space-x-2 text-sm">
                            {{-- <a href="{{ url('/reservations/' . $res->_id) }}" class="text-indigo-600">Voir</a> --}}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Aucune réservation pour cet atelier.</p>
        @endif
    </div>
    </div>
</x-app-layout>
