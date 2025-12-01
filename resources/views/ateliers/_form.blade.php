<div class="grid grid-cols-1 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Titre</label>
        <input type="text" name="nom" value="{{ old('nom', $atelier->nom ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Date</label>
        <input type="datetime-local" name="date" id="atelier-date" value="{{ old('date', isset($atelier) && $atelier->date ? $atelier->date->format('Y-m-d\TH:i') : '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
        @error('date') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Durée (heures)</label>
        <input type="number" name="duree" id="atelier-duree" min="1" step="1" value="{{ old('duree', $atelier->duree ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
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
        <select name="salle_id" id="atelier-salle" class="mt-1 block w-full border rounded-md px-3 py-2 text-sm">
            <option value="">-- Choisir une salle (optionnel) --</option>
            @isset($sallesParBoutique)
                @foreach($sallesParBoutique as $boutiqueNom => $salles)
                    <optgroup label="{{ $boutiqueNom }}">
                        @foreach($salles as $salle)
                            <option value="{{ $salle->getKey() }}"
                                    data-salle-id="{{ $salle->getKey() }}"
                                    {{ (string) old('salle_id', $atelier->salle_id ?? '') === (string) $salle->getKey() ? 'selected' : '' }}>
                                {{ $salle->nom ?? $salle->titre ?? $salle->getKey() }} - {{ $salle->capacite ?? '—' }} places ({{ $salle->categorie ?? 'Non catégorisée' }})
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            @endisset
        </select>
        <p class="mt-1 text-xs text-gray-500">
            💡 Les salles indisponibles pour le créneau sélectionné seront marquées
        </p>
        @error('salle_id') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Responsable (Employé)</label>
        <select name="employe_id" class="mt-1 block w-full border rounded-md px-3 py-2 text-sm">
            <option value="">-- Choisir un employé responsable --</option>
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

    {{-- Informations de l'intervenant (embeded) --}}
    <div class="mt-6 pt-4 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Intervenant de l'atelier</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Prénom de l'intervenant</label>
                <input type="text" name="intervenant[prenom]" placeholder="Ex : Jean" value="{{ old('intervenant.prenom', $atelier->intervenant['prenom'] ?? $atelier->intervenant->prenom ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                @error('intervenant.prenom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Nom de l'intervenant</label>
                <input type="text" name="intervenant[nom]" placeholder="Ex : Dupont" value="{{ old('intervenant.nom', $atelier->intervenant['nom'] ?? $atelier->intervenant->nom ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                @error('intervenant.nom') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email de l'intervenant</label>
                <input type="email" name="intervenant[infoContact][email]" placeholder="intervenant@example.com" value="{{ old('intervenant.infoContact.email', $atelier->intervenant['infoContact']['email'] ?? $atelier->intervenant->infoContact->email ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                @error('intervenant.infoContact.email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone de l'intervenant</label>
                <input type="tel" name="intervenant[infoContact][telephone]" placeholder="+33 6 12 34 56 78" value="{{ old('intervenant.infoContact.telephone', $atelier->intervenant['infoContact']['telephone'] ?? $atelier->intervenant->infoContact->telephone ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                @error('intervenant.infoContact.telephone') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Site web de l'intervenant</label>
            <input type="url" name="intervenant[infoContact][website]" placeholder="https://www.exemple.fr" value="{{ old('intervenant.infoContact.website', $atelier->intervenant['infoContact']['website'] ?? $atelier->intervenant->infoContact->website ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
            @error('intervenant.infoContact.website') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        {{-- Réseaux sociaux de l'intervenant --}}
        <div class="mt-6">
            <h4 class="text-md font-medium text-gray-900 mb-3">Réseaux sociaux de l'intervenant (optionnels)</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-youtube class="w-4 h-4 text-red-600" />
                        YouTube
                    </label>
                    <input type="url" name="intervenant[infoContact][youtube]" placeholder="https://youtube.com/..." value="{{ old('intervenant.infoContact.youtube', $atelier->intervenant['infoContact']['youtube'] ?? $atelier->intervenant->infoContact->youtube ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('intervenant.infoContact.youtube') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-instagram class="w-4 h-4 text-pink-600" />
                        Instagram
                    </label>
                    <input type="url" name="intervenant[infoContact][instagram]" placeholder="https://instagram.com/..." value="{{ old('intervenant.infoContact.instagram', $atelier->intervenant['infoContact']['instagram'] ?? $atelier->intervenant->infoContact->instagram ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('intervenant.infoContact.instagram') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-facebook class="w-4 h-4 text-blue-600" />
                        Facebook
                    </label>
                    <input type="url" name="intervenant[infoContact][facebook]" placeholder="https://facebook.com/..." value="{{ old('intervenant.infoContact.facebook', $atelier->intervenant['infoContact']['facebook'] ?? $atelier->intervenant->infoContact->facebook ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('intervenant.infoContact.facebook') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-x class="w-4 h-4 text-blue-500" />
                        Twitter/X
                    </label>
                    <input type="url" name="intervenant[infoContact][twitter]" placeholder="https://twitter.com/..." value="{{ old('intervenant.infoContact.twitter', $atelier->intervenant['infoContact']['twitter'] ?? $atelier->intervenant->infoContact->twitter ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('intervenant.infoContact.twitter') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-pinterest class="w-4 h-4 text-red-500" />
                        Pinterest
                    </label>
                    <input type="url" name="intervenant[infoContact][pinterest]" placeholder="https://pinterest.com/..." value="{{ old('intervenant.infoContact.pinterest', $atelier->intervenant['infoContact']['pinterest'] ?? $atelier->intervenant->infoContact->pinterest ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('intervenant.infoContact.pinterest') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-bluesky class="w-4 h-4 text-sky-600" />
                        Bluesky
                    </label>
                    <input type="url" name="intervenant[infoContact][bluesky]" placeholder="https://bsky.app/profile/..." value="{{ old('intervenant.infoContact.bluesky', $atelier->intervenant['infoContact']['bluesky'] ?? $atelier->intervenant->infoContact->bluesky ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('intervenant.infoContact.bluesky') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center space-x-4 mt-6">
        <label class="inline-flex items-center">
            <input type="checkbox" name="vip" value="1" {{ old('vip', $atelier->vip ?? false) ? 'checked' : '' }} class="form-checkbox">
            <span class="ml-2 text-sm text-gray-700">VIP</span>
        </label>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('atelier-date');
    const dureeInput = document.getElementById('atelier-duree');
    const salleSelect = document.getElementById('atelier-salle');
    const atelierIdInput = document.querySelector('input[name="_method"][value="PUT"]');
    const atelierId = atelierIdInput ? '{{ isset($atelier) ? $atelier->getKey() : '' }}' : null;

    // Fonction pour vérifier la disponibilité des salles
    async function verifierDisponibilite() {
        const date = dateInput.value;
        const duree = dureeInput.value;

        if (!date || !duree) {
            // Réactiver toutes les options et enlever les warnings
            Array.from(salleSelect.options).forEach(option => {
                if (option.value) {
                    option.disabled = false;
                    option.text = option.text.replace(' ⚠️ OCCUPÉE', '').replace(' ✓ Disponible', '');
                }
            });
            return;
        }

        try {
            const response = await fetch('/api/salles/disponibilite', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    date: date,
                    duree: parseInt(duree),
                    atelier_id: atelierId
                })
            });

            const data = await response.json();

            // Mettre à jour les options du select
            Array.from(salleSelect.options).forEach(option => {
                if (option.value && option.dataset.salleId) {
                    const salleId = option.dataset.salleId;
                    const isDisponible = data.disponibles && data.disponibles.includes(salleId);

                    // Nettoyer le texte existant
                    let optionText = option.text.replace(' ⚠️ OCCUPÉE', '').replace(' ✓ Disponible', '');

                    if (isDisponible) {
                        option.text = optionText + ' ✓ Disponible';
                        option.disabled = false;
                        option.style.color = 'green';
                    } else {
                        option.text = optionText + ' ⚠️ OCCUPÉE';
                        option.disabled = true;
                        option.style.color = 'red';
                    }
                }
            });
        } catch (error) {
            console.error('Erreur lors de la vérification de disponibilité:', error);
        }
    }

    // Écouter les changements de date et durée
    dateInput?.addEventListener('change', verifierDisponibilite);
    dureeInput?.addEventListener('input', verifierDisponibilite);

    // Vérifier au chargement si les champs sont remplis
    if (dateInput?.value && dureeInput?.value) {
        verifierDisponibilite();
    }
});
</script>
