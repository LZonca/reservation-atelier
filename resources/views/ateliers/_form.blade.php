<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Titre</label>
        <input type="text" name="nom" value="{{ old('nom', $atelier->nom ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Date</label>
        <input type="datetime-local" name="date" value="{{ old('date', isset($atelier) && $atelier->date ? $atelier->date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('date') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Durée</label>
        <input type="text" name="duree" value="{{ old('duree', $atelier->duree ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('duree') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Prix</label>
        <input type="number" step="0.01" name="prix" value="{{ old('prix', $atelier->prix ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('prix') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" rows="4" class="mt-1 block w-full border rounded-md px-3 py-2">{{ old('description', $atelier->description ?? '') }}</textarea>
        @error('description') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Salle</label>
        <select name="salle_id" class="mt-1 block w-full border rounded-md px-3 py-2 text-sm">
            <option value="">-- Choisir une salle (optionnel) --</option>
            @isset($sallesParBoutique)
                @foreach($sallesParBoutique as $boutiqueNom => $salles)
                    <optgroup label="{{ $boutiqueNom }}">
                        @foreach($salles as $salle)
                            <option value="{{ $salle->getKey() }}" {{ (string) old('salle_id', $atelier->salle_id ?? '') === (string) $salle->getKey() ? 'selected' : '' }}>
                                {{ $salle->nom ?? $salle->titre ?? $salle->getKey() }} - {{ $salle->capacite ?? '—' }} places ({{ $salle->categorie ?? 'Non catégorisée' }})
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            @endisset
        </select>
        @error('salle_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Intervenant</label>
        <select name="employe_id" class="mt-1 block w-full border rounded-md px-3 py-2 text-sm">
            <option value="">-- Choisir un intervenant (optionnel) --</option>
            @isset($intervenants)
                @foreach($intervenants as $intervenant)
                    <option value="{{ $intervenant->getKey() }}" {{ (string) old('employe_id', $atelier->employe_id ?? '') === (string) $intervenant->getKey() ? 'selected' : '' }}>
                        {{ $intervenant->name }} ({{ $intervenant->email }})
                    </option>
                @endforeach
            @endisset
        </select>
        @error('employe_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div class="flex items-center space-x-4">
        <label class="inline-flex items-center">
            <input type="checkbox" name="vip" value="1" {{ old('vip', $atelier->vip ?? false) ? 'checked' : '' }} class="form-checkbox">
            <span class="ml-2 text-sm text-gray-700">VIP</span>
        </label>
    </div>
</div>
