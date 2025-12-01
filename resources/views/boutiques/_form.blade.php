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

    {{-- Informations de contact --}}
    <div class="mt-6 pt-4 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de contact</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="infoContact[email]" placeholder="contact@boutique.fr" value="{{ old('infoContact.email', $boutique->infoContact['email'] ?? $boutique->infoContact->email ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                @error('infoContact.email') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                <input type="tel" name="infoContact[telephone]" placeholder="+33 1 23 45 67 89" value="{{ old('infoContact.telephone', $boutique->infoContact['telephone'] ?? $boutique->infoContact->telephone ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                @error('infoContact.telephone') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Site web</label>
            <input type="url" name="infoContact[website]" placeholder="https://www.boutique.fr" value="{{ old('infoContact.website', $boutique->infoContact['website'] ?? $boutique->infoContact->website ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
            @error('infoContact.website') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
        </div>

        {{-- Réseaux sociaux --}}
        <div class="mt-6">
            <h4 class="text-md font-medium text-gray-900 mb-3">Réseaux sociaux (optionnels)</h4>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-youtube class="w-4 h-4 text-red-600" />
                        YouTube
                    </label>
                    <input type="url" name="infoContact[youtube]" placeholder="https://youtube.com/channel/..." value="{{ old('infoContact.youtube', $boutique->infoContact['youtube'] ?? $boutique->infoContact->youtube ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('infoContact.youtube') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-instagram class="w-4 h-4 text-pink-600" />
                        Instagram
                    </label>
                    <input type="url" name="infoContact[instagram]" placeholder="https://instagram.com/..." value="{{ old('infoContact.instagram', $boutique->infoContact['instagram'] ?? $boutique->infoContact->instagram ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('infoContact.instagram') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-facebook class="w-4 h-4 text-blue-600" />
                        Facebook
                    </label>
                    <input type="url" name="infoContact[facebook]" placeholder="https://facebook.com/..." value="{{ old('infoContact.facebook', $boutique->infoContact['facebook'] ?? $boutique->infoContact->facebook ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('infoContact.facebook') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-x class="w-4 h-4 text-blue-500" />
                        Twitter/X
                    </label>
                    <input type="url" name="infoContact[twitter]" placeholder="https://twitter.com/..." value="{{ old('infoContact.twitter', $boutique->infoContact['twitter'] ?? $boutique->infoContact->twitter ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('infoContact.twitter') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-pinterest class="w-4 h-4 text-red-500" />
                        Pinterest
                    </label>
                    <input type="url" name="infoContact[pinterest]" placeholder="https://pinterest.com/..." value="{{ old('infoContact.pinterest', $boutique->infoContact['pinterest'] ?? $boutique->infoContact->pinterest ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('infoContact.pinterest') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 flex items-center gap-2">
                        <x-si-bluesky class="w-4 h-4 text-sky-600" />
                        Bluesky
                    </label>
                    <input type="url" name="infoContact[bluesky]" placeholder="https://bsky.app/profile/..." value="{{ old('infoContact.bluesky', $boutique->infoContact['bluesky'] ?? $boutique->infoContact->bluesky ?? '') }}" class="mt-1 block w-full border rounded-md px-3 py-2">
                    @error('infoContact.bluesky') <div class="text-sm text-red-600">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>
</div>
