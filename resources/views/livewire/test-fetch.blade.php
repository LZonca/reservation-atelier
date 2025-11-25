@php
    use Illuminate\Support\Str;
@endphp

<div>
    {{-- Affichage rapide des ateliers sans debug --}}
    @if(empty($ateliers))
        <div class="p-4 text-gray-500">Aucun atelier disponible.</div>
    @else
        <ul>
            @foreach($ateliers as $atelier)
                <li class="py-2 border-b">
                    <strong>{{ $atelier->nom ?? ($atelier['nom'] ?? '—') }}</strong>
                    <div class="text-sm text-gray-600">{{ Str::limit($atelier->description ?? ($atelier['description'] ?? ''), 140) }}</div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
