<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nom</label>
        <input type="text" name="nom" value="{{ old('nom', $salle->nom ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Capacité</label>
        <input type="number" name="capacite" min="0" value="{{ old('capacite', $salle->capacite ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('capacite') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Adresse</label>
        <textarea name="adresse" rows="3" class="mt-1 block w-full border rounded-md px-3 py-2">{{ old('adresse', $salle->adresse ?? '') }}</textarea>
        @error('adresse') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Boutique (optionnel)</label>
        <select name="boutique" class="mt-1 block w-full border rounded-md px-3 py-2 text-sm">
            <option value="">-- Choisir une boutique --</option>
            @isset($boutiques)
                @foreach($boutiques as $boutique)
                    <option value="{{ $boutique->getKey() }}" {{ (string) old('boutique', $salle->boutique ?? '') === (string) $boutique->getKey() ? 'selected' : '' }}>{{ $boutique->nom ?? $boutique->getKey() }}</option>
                @endforeach
            @endisset
        </select>
        @error('boutique') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>
</div>
