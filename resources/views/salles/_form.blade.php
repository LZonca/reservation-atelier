<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nom</label>
        <input type="text" name="nom" placeholder="Ex : Salle A" value="{{ old('nom', $salle->nom ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Capacité</label>
            <input type="number" name="capacite" min="0" placeholder="0" value="{{ old('capacite', $salle->capacite ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
            @error('capacite') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">Catégorie</label>
            <input type="text" name="categorie" placeholder="Ex : Poterie" value="{{ old('categorie', $salle->categorie ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
            @error('categorie') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Boutique</label>
        <select name="boutique_id" required class="mt-1 block w-full border rounded-md px-3 py-2 text-sm">
            <option value="">-- Aucune boutique --</option>
            @isset($boutiques)
                @foreach($boutiques as $boutique)
                    <option value="{{ $boutique->getKey() }}" {{ (string) old('boutique_id', (string) ($salle->boutique_id ?? '')) === (string) $boutique->getKey() ? 'selected' : '' }}>{{ $boutique->nom ?? $boutique->getKey() }}</option>
                @endforeach
            @endisset
        </select>
        <p class="text-xs text-gray-500 mt-1">Sélectionne la boutique si cette salle lui appartient. Laisser vide sinon.</p>
        @error('boutique_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>
</div>
