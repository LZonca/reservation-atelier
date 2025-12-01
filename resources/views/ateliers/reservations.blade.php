<x-app-layout>
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
        {{-- En-tête avec lien de retour --}}
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold">{{ $atelier->nom ?? $atelier->titre ?? 'Atelier' }}</h2>
            <a href="{{ url('/ateliers') }}" class="text-sm text-gray-600 hover:text-gray-900">← Retour</a>
        </div>

        {{-- Messages flash --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Informations détaillées de l'atelier --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="mt-2 text-sm text-gray-600">
                <strong>Date :</strong> {{ optional($atelier->date)->format('d/m/Y à H:i') ?? '—' }}
            </div>
            <p class="mt-4 text-gray-700">{{ $atelier->description ?? '' }}</p>

            <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                <div><strong>Durée :</strong> {{ $atelier->duree ?? '—' }}h</div>
                <div><strong>Prix :</strong> {{ isset($atelier->prix) ? number_format($atelier->prix, 2) . ' €' : '—' }}</div>
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
        </div>

        {{-- Composant modal Livewire --}}
        <livewire:reservation-editor />

        {{-- Liste des réservations --}}

        @if(isset($reservations) && $reservations->count())
            {{-- Compteur de réservations --}}
            @php
                $activeCount = $reservations->filter(fn($r) => empty($r->deleted_at))->count();
                $deletedCount = $reservations->filter(fn($r) => !empty($r->deleted_at))->count();
            @endphp
            <div class="mb-4 flex items-center gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <span class="text-gray-700 font-medium">{{ $activeCount }} active{{ $activeCount > 1 ? 's' : '' }}</span>
                </div>
                @if($deletedCount > 0)
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                        <span class="text-gray-700 font-medium">{{ $deletedCount }} annulée{{ $deletedCount > 1 ? 's' : '' }}</span>
                    </div>
                @endif
                <div class="text-gray-500">
                    Total : {{ $reservations->count() }}
                </div>
            </div>

            <div class="space-y-3">

                <livewire:atelier-statistics :atelier="$atelier" />

                @foreach($reservations as $reservation)
                    @php
                        $isDeleted = !empty($reservation->deleted_at);
                    @endphp
                    <div class="bg-white rounded shadow p-4 {{ $isDeleted ? 'border-l-4 border-red-500 bg-red-50' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="font-semibold text-lg {{ $isDeleted ? 'text-gray-600' : '' }}">
                                        Réservation #{{ $reservation->_id }}
                                    </div>
                                    @if($isDeleted)
                                        <span class="px-3 py-1 bg-red-600 text-white text-xs font-bold rounded-full uppercase">
                                            Annulée
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            le {{ $reservation->deleted_at->format('d/m/Y à H:i') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm {{ $isDeleted ? 'text-gray-500' : '' }}">
                                    {{-- Informations client --}}
                                    <div>
                                        <span class="text-gray-600">Client:</span>
                                        <span class="font-medium ml-1">
                                            {{ optional($reservation->client)->prenom ? optional($reservation->client)->prenom . ' ' . optional($reservation->client)->nom : ($reservation->client_name ?? '—') }}
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-gray-600">Nb personnes:</span>
                                        <span class="font-medium ml-1">{{ $reservation->nbPersonne ?? ($reservation->nb_personne ?? '—') }}</span>
                                    </div>

                                    {{-- Informations paiement --}}
                                    @php
                                        $payment = $reservation->paiement;
                                        $paymentId = $payment ? (data_get($payment, '_id') ?? data_get($payment, 'id')) : null;
                                    @endphp
                                    <div>
                                        <span class="text-gray-600">Prix payé:</span>
                                        <span class="font-medium ml-1">
                                            {{ $payment ? number_format((float)($payment->montant ?? 0), 2) . ' €' : '—' }}
                                        </span>
                                    </div>

                                    <div>
                                        <span class="text-gray-600">Moyen de paiement:</span>
                                        <span class="font-medium ml-1">{{ $payment->methode_paiement ?? '—' }}</span>
                                    </div>

                                    <div class="md:col-span-2">
                                        <span class="text-gray-600">Statut paiement:</span>
                                        @if($payment)
                                            <span class="ml-2 px-3 py-1 rounded text-xs font-semibold
                                                @if(in_array($payment->statut ?? '', ['completed', 'validé'])) bg-green-100 text-green-800
                                                @elseif(in_array($payment->statut ?? '', ['refunded', 'remboursé'])) bg-red-100 text-red-800
                                                @elseif(in_array($payment->statut ?? '', ['failed', 'échoué'])) bg-orange-100 text-orange-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ ucfirst($payment->statut ?? 'pending') }}
                                            </span>
                                        @else
                                            <span class="ml-2 text-gray-400">—</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Bouton d'édition (masqué si annulée) --}}
                            @if(!$isDeleted)
                                <div class="ml-4">
                                    <button
                                        onclick="
                console.log('Button clicked');
                Livewire.dispatch('openReservationEditor', {
                    atelierId: '{{ (string)$atelier->_id }}',
                    reservationId: '{{ (string)$reservation->_id }}'
                });
                console.log('Event dispatched');
            "
                                        class="px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded hover:bg-blue-600 transition-colors">
                                        Éditer
                                    </button>
                                </div>
                            @endif                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-4">
                @if(method_exists($reservations, 'links'))
                    {{ $reservations->links() }}
                @endif
            </div>
        @else
            <p class="text-gray-500 text-center py-8">Aucune réservation pour cet atelier.</p>
        @endif
    </div>
</x-app-layout>
