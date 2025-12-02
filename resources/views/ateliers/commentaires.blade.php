@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- En-tête avec retour -->
        <div class="mb-6">
            <a href="{{ route('ateliers.index') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux ateliers
            </a>
        </div>

        <!-- Carte informations de l'atelier -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
            <div class="p-6 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                <h1 class="text-3xl font-bold mb-2">{{ $atelier->nom }}</h1>
                @if($atelier->date)
                    <p class="text-indigo-100">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $atelier->date->format('d/m/Y à H:i') }}
                    </p>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Statistiques -->
            <div class="lg:col-span-1">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-xl font-bold mb-4 text-gray-800">📊 Statistiques</h2>

                    <!-- Note moyenne -->
                    <div class="mb-6 text-center">
                        @if($noteMoyenne)
                            <div class="text-5xl font-bold text-indigo-600 mb-2">
                                {{ number_format($noteMoyenne, 1) }}
                            </div>
                            <div class="flex justify-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($noteMoyenne))
                                        <span class="text-yellow-400 text-2xl">★</span>
                                    @elseif($i - 0.5 <= $noteMoyenne)
                                        <span class="text-yellow-400 text-2xl">⯨</span>
                                    @else
                                        <span class="text-gray-300 text-2xl">☆</span>
                                    @endif
                                @endfor
                            </div>
                            <p class="text-gray-600">{{ $nombreCommentaires }} {{ $nombreCommentaires > 1 ? 'avis' : 'avis' }}</p>
                        @else
                            <div class="text-gray-400 py-4">
                                <svg class="w-16 h-16 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <p>Aucun avis pour le moment</p>
                            </div>
                        @endif
                    </div>

                    <!-- Répartition des notes -->
                    @if($nombreCommentaires > 0)
                        <div class="space-y-2">
                            @foreach([5,4,3,2,1] as $note)
                                <div class="flex items-center gap-2">
                                    <span class="w-12 text-sm text-gray-600">{{ $note }} ★</span>
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        @php
                                            $percentage = $nombreCommentaires > 0 ? ($repartitionNotes[$note] / $nombreCommentaires) * 100 : 0;
                                        @endphp
                                        <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                    <span class="w-8 text-sm text-gray-600 text-right">{{ $repartitionNotes[$note] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Liste des commentaires -->
            <div class="lg:col-span-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">💬 Avis des participants</h2>

                    @if($commentaires->isEmpty())
                        <div class="text-center py-12 text-gray-400">
                            <svg class="w-20 h-20 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            <p class="text-lg">Aucun commentaire pour cet atelier</p>
                            <p class="text-sm mt-2">Soyez le premier à donner votre avis !</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($commentaires as $commentaire)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <!-- Avatar -->
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold shadow">
                                                @if($commentaire->client)
                                                    {{ strtoupper(substr($commentaire->client->prenom ?? 'A', 0, 1)) }}{{ strtoupper(substr($commentaire->client->nom ?? 'A', 0, 1)) }}
                                                @else
                                                    ?
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800">
                                                    @if($commentaire->client)
                                                        {{ $commentaire->client->prenom }} {{ $commentaire->client->nom }}
                                                    @else
                                                        Client inconnu
                                                    @endif
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $commentaire->created_at->format('d/m/Y à H:i') }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Note -->
                                        @if($commentaire->note)
                                            <div class="flex items-center gap-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $commentaire->note)
                                                        <span class="text-yellow-400 text-lg">★</span>
                                                    @else
                                                        <span class="text-gray-300 text-lg">☆</span>
                                                    @endif
                                                @endfor
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Commentaire -->
                                    <p class="text-gray-700 leading-relaxed">
                                        {{ $commentaire->commentaire }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

