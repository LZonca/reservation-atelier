<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nom</label>
        <input type="text" name="nom" placeholder="Ex : Boutique Centre" value="{{ old('nom', $boutique->nom ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Rue</label>
            <input type="text" name="adresse[rue]" placeholder="Ex : Rue de l'Atelier" value="{{ old('adresse.rue', $boutique->adresse['rue'] ?? $boutique->adresse->rue ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
            @error('adresse.rue') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Numéro</label>
            <input type="text" name="adresse[numero]" placeholder="Ex : 12" value="{{ old('adresse.numero', $boutique->adresse['numero'] ?? $boutique->adresse->numero ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
            @error('adresse.numero') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
        <div>
            <label class="block text-sm font-medium text-gray-700">Ville</label>
            <input type="text" name="adresse[ville]" placeholder="Ex : Paris" value="{{ old('adresse.ville', $boutique->adresse['ville'] ?? $boutique->adresse->ville ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
            @error('adresse.ville') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Code postal</label>
            <input type="text" name="adresse[code_postal]" placeholder="Ex : 75001" value="{{ old('adresse.code_postal', $boutique->adresse['code_postal'] ?? $boutique->adresse->code_postal ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
            @error('adresse.code_postal') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>
    </div>
</div>
