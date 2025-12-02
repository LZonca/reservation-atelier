<!-- Partial: mini-postman.blade.php -->
<!-- Contient le bouton flottant et l'overlay modal pour le simulateur API -->

<!-- Bouton flottant pour ouvrir le simulateur -->
<button id="mini-postman-toggle" title="Ouvrir le simulateur API" class="fixed bottom-6 right-6 z-50 bg-indigo-600 text-white rounded-full p-4 shadow-lg hover:bg-indigo-700 focus:outline-none transition-all hover:scale-110">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
</button>

<!-- Overlay modal du simulateur (caché par défaut) -->
<div id="mini-postman-overlay" class="hidden fixed inset-0 z-40 items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="w-full max-w-6xl bg-white rounded-lg shadow-2xl overflow-hidden" style="max-height:90vh;">
        <div class="flex items-center justify-between p-4 border-b bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div class="text-lg font-semibold">Simulateur API (Mini-Postman)</div>
            </div>
            <button id="mini-postman-close" class="text-white hover:bg-white hover:bg-opacity-20 px-3 py-1 rounded transition">✕ Fermer</button>
        </div>

        <div class="p-4 overflow-auto" style="max-height: calc(90vh - 64px);">
            <!-- Layout: modèles à gauche, formulaire à droite -->
            <div class="grid grid-cols-12 gap-4">
                <!-- Modèles (gauche) -->
                <div class="col-span-12 lg:col-span-4 space-y-3">
                    <div class="text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                        <span>📋</span>
                        <span>Templates d'API</span>
                    </div>
                    <div class="space-y-2 max-h-[calc(90vh-180px)] overflow-y-auto pr-2">
                        <!-- ATELIERS -->
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-3 mb-2">🎨 Ateliers</div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="GET" data-url="/api/ateliers" data-body="">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-green-500 px-2 py-0.5 rounded">GET</span>
                                <span class="text-xs font-mono text-gray-700">/api/ateliers</span>
                            </div>
                            <div class="text-xs text-gray-500">Liste tous les ateliers</div>
                        </div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="POST" data-url="/api/ateliers" data-body='{"nom":"Atelier Peinture","description":"Initiation à la peinture abstraite","prix":45.5,"duree":2,"date":"2025-12-15 14:00:00","categorie":"Peinture","vip":false,"salle_id":"REMPLACER_PAR_ID_SALLE","employe_id":"REMPLACER_PAR_ID_EMPLOYE"}'>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-blue-500 px-2 py-0.5 rounded">POST</span>
                                <span class="text-xs font-mono text-gray-700">/api/ateliers</span>
                            </div>
                            <div class="text-xs text-gray-500">Créer un atelier (tous champs requis)</div>
                        </div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="GET" data-url="/api/ateliers/{id}" data-body="">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-green-500 px-2 py-0.5 rounded">GET</span>
                                <span class="text-xs font-mono text-gray-700">/api/ateliers/{id}</span>
                            </div>
                            <div class="text-xs text-gray-500">Détails d'un atelier</div>
                        </div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="PUT" data-url="/api/ateliers/{id}" data-body='{"nom":"Atelier Peinture Avancé","prix":55}'>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-yellow-500 px-2 py-0.5 rounded">PUT</span>
                                <span class="text-xs font-mono text-gray-700">/api/ateliers/{id}</span>
                            </div>
                            <div class="text-xs text-gray-500">Modifier un atelier</div>
                        </div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="DELETE" data-url="/api/ateliers/{id}" data-body="">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-red-500 px-2 py-0.5 rounded">DELETE</span>
                                <span class="text-xs font-mono text-gray-700">/api/ateliers/{id}</span>
                            </div>
                            <div class="text-xs text-gray-500">Supprimer un atelier</div>
                        </div>

                        <!-- CLIENTS -->
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-4 mb-2">👥 Clients</div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="GET" data-url="/api/clients" data-body="">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-green-500 px-2 py-0.5 rounded">GET</span>
                                <span class="text-xs font-mono text-gray-700">/api/clients</span>
                            </div>
                            <div class="text-xs text-gray-500">Liste tous les clients</div>
                        </div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="POST" data-url="/api/clients" data-body='{"nom":"Dupont","prenom":"Jean","email":"jean.dupont@example.com","telephone":"0612345678","credit_fidelite":0}'>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-blue-500 px-2 py-0.5 rounded">POST</span>
                                <span class="text-xs font-mono text-gray-700">/api/clients</span>
                            </div>
                            <div class="text-xs text-gray-500">Créer un client</div>
                        </div>

                        <!-- RÉSERVATIONS -->
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-4 mb-2">📅 Réservations</div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="POST" data-url="/api/atelier/{atelierId}/reservations" data-body='{"client_id":"REMPLACER_PAR_ID_CLIENT","nbPersonne":2,"prix":91}'>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-blue-500 px-2 py-0.5 rounded">POST</span>
                                <span class="text-xs font-mono text-gray-700">/api/atelier/{id}/reservations</span>
                            </div>
                            <div class="text-xs text-gray-500">Créer une réservation</div>
                        </div>

                        <!-- SALLES -->
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-4 mb-2">🏢 Salles</div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="GET" data-url="/api/salles" data-body="">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-green-500 px-2 py-0.5 rounded">GET</span>
                                <span class="text-xs font-mono text-gray-700">/api/salles</span>
                            </div>
                            <div class="text-xs text-gray-500">Liste toutes les salles</div>
                        </div>

                        <!-- UTILISATEURS -->
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-wider mt-4 mb-2">🔐 Utilisateurs</div>

                        <div class="request-template border-2 border-gray-200 rounded-lg p-2.5 hover:bg-indigo-50 hover:border-indigo-300 cursor-pointer transition-all" data-method="GET" data-url="/api/user" data-body="">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-white bg-green-500 px-2 py-0.5 rounded">GET</span>
                                <span class="text-xs font-mono text-gray-700">/api/user</span>
                            </div>
                            <div class="text-xs text-gray-500">Profil utilisateur connecté</div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire (droite) -->
                <div class="col-span-12 lg:col-span-8">
                    <form id="mini-postman-form" class="space-y-3">
                        <!-- Méthode + URL -->
                        <div class="flex items-center gap-2">
                            <select id="method" class="border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option>GET</option>
                                <option>POST</option>
                                <option>PUT</option>
                                <option>PATCH</option>
                                <option>DELETE</option>
                            </select>

                            <input id="url" type="text" placeholder="/api/ateliers" class="flex-1 border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <button id="send-btn" type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white px-6 py-2 rounded-lg text-sm font-semibold transition-all shadow-md hover:shadow-lg">
                                ▶ Envoyer
                            </button>
                        </div>

                        <!-- Aide contextuelle -->
                        <div id="help-text" class="hidden text-xs bg-yellow-50 border-l-4 border-yellow-400 rounded p-3 text-yellow-800">
                            <div class="flex items-start gap-2">
                                <span class="text-lg">💡</span>
                                <div>
                                    <strong>Astuce:</strong>
                                    <span id="help-message"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Corps de la requête -->
                        <div>
                            <label for="body" class="block text-sm font-semibold text-gray-700 mb-2">📝 Corps de la requête (JSON)</label>
                            <textarea id="body" rows="10" class="w-full border-2 border-gray-300 rounded-lg px-3 py-2 text-xs font-mono focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-gray-50" placeholder='{"nom":"...","description":"...","prix":0}'></textarea>
                            <div class="flex items-center justify-between mt-1">
                                <div class="text-xs text-gray-500">💡 Cliquez sur un template à gauche pour pré-remplir</div>
                                <button type="button" id="format-json" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">✨ Formater JSON</button>
                            </div>
                        </div>

                        <!-- Réponse -->
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-semibold text-gray-700">📥 Réponse</label>
                                <button id="copy-response" type="button" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                    <span>📋</span>
                                    <span>Copier</span>
                                </button>
                            </div>
                            <div id="mini-postman-response" class="w-full h-64 overflow-auto bg-gray-900 text-green-400 border-2 border-gray-700 rounded-lg p-3 text-xs font-mono whitespace-pre-wrap shadow-inner">⏳ En attente de requête...</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Script inline pour le mini-Postman --}}
<script>
    (function(){
        // Toggle overlay
        const toggleBtn = document.getElementById('mini-postman-toggle');
        const overlay = document.getElementById('mini-postman-overlay');
        const closeBtn = document.getElementById('mini-postman-close');

        function showOverlay(){
            overlay.classList.remove('hidden');
            overlay.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }
        function hideOverlay(){
            overlay.classList.remove('flex');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        toggleBtn?.addEventListener('click', (ev) => { ev.preventDefault(); showOverlay(); });
        closeBtn?.addEventListener('click', (ev) => { ev.preventDefault(); hideOverlay(); });
        overlay?.addEventListener('click', (ev) => { if (ev.target === overlay) hideOverlay(); });
        document.addEventListener('keydown', (ev) => { if (ev.key === 'Escape') hideOverlay(); });

        const form = document.getElementById('mini-postman-form');
        const methodEl = document.getElementById('method');
        const urlEl = document.getElementById('url');
        const bodyEl = document.getElementById('body');
        const respEl = document.getElementById('mini-postman-response');
        const helpText = document.getElementById('help-text');
        const helpMessage = document.getElementById('help-message');
        const templates = document.querySelectorAll('.request-template');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Formater JSON
        document.getElementById('format-json')?.addEventListener('click', () => {
            try {
                const formatted = JSON.stringify(JSON.parse(bodyEl.value), null, 2);
                bodyEl.value = formatted;
            } catch(e) {
                alert('JSON invalide: ' + e.message);
            }
        });

        // Charger les templates
        templates.forEach(t => {
            t.addEventListener('click', () => {
                templates.forEach(x => {
                    x.classList.remove('ring-2', 'ring-indigo-500', 'bg-indigo-50');
                });
                t.classList.add('ring-2', 'ring-indigo-500', 'bg-indigo-50');

                const m = t.getAttribute('data-method') || 'GET';
                const u = t.getAttribute('data-url') || '';
                const b = t.getAttribute('data-body') || '';

                methodEl.value = m;
                urlEl.value = u;
                bodyEl.value = b ? JSON.stringify(JSON.parse(b), null, 2) : '';

                // Aide contextuelle
                if (u.includes('{id}') || u.includes('{atelierId}') || b.includes('REMPLACER')) {
                    helpMessage.textContent = 'N\'oubliez pas de remplacer {id}, {atelierId} ou "REMPLACER_PAR_..." par des valeurs réelles !';
                    helpText.classList.remove('hidden');
                } else {
                    helpText.classList.add('hidden');
                }

                if (window.innerWidth < 1024) {
                    form.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Copier la réponse
        document.getElementById('copy-response')?.addEventListener('click', () => {
            navigator.clipboard.writeText(respEl.textContent).then(() => {
                const btn = document.getElementById('copy-response');
                const original = btn.innerHTML;
                btn.innerHTML = '<span>✓</span><span>Copié !</span>';
                setTimeout(() => { btn.innerHTML = original; }, 2000);
            });
        });

        function prettyPrintHeaders(headers){
            let out = '';
            for (const pair of headers.entries()){
                out += pair[0] + ': ' + pair[1] + '\n';
            }
            return out;
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            respEl.textContent = '⏳ Envoi en cours...';
            respEl.classList.remove('text-green-400', 'text-red-400');
            respEl.classList.add('text-yellow-400');

            let method = methodEl.value || 'GET';
            let url = urlEl.value.trim();

            if (!url) {
                respEl.textContent = '❌ Veuillez fournir une URL.';
                respEl.classList.add('text-red-400');
                return;
            }

            // Normaliser l'URL
            if (!/^https?:\/\//i.test(url)){
                url = window.location.origin + (url.startsWith('/') ? url : '/' + url);
            }

            const opts = { method, headers: { 'Accept': 'application/json' } };

            if (method !== 'GET' && method !== 'DELETE'){
                if (csrf) opts.headers['X-CSRF-TOKEN'] = csrf;
                const bodyText = bodyEl.value.trim();
                if (bodyText) {
                    opts.headers['Content-Type'] = 'application/json';
                    try {
                        opts.body = JSON.stringify(JSON.parse(bodyText));
                    } catch (err) {
                        respEl.textContent = '❌ JSON invalide: ' + err.message;
                        respEl.classList.remove('text-yellow-400');
                        respEl.classList.add('text-red-400');
                        return;
                    }
                }
            }

            try {
                const res = await fetch(url, opts);
                const statusLine = `HTTP/1.1 ${res.status} ${res.statusText}`;
                const contentType = res.headers.get('content-type') || '';

                let text;
                if (contentType.includes('application/json')){
                    const json = await res.json();
                    text = JSON.stringify(json, null, 2);
                } else {
                    text = await res.text();
                }

                // Coloration
                respEl.classList.remove('text-yellow-400');
                if (res.ok) {
                    respEl.classList.add('text-green-400');
                    helpText.classList.add('hidden');
                } else {
                    respEl.classList.add('text-red-400');
                }

                respEl.textContent = statusLine + '\n\n' + prettyPrintHeaders(res.headers) + '\n' + text;

                // Aide pour erreur 422
                if (res.status === 422 && contentType.includes('application/json')) {
                    try {
                        const json = JSON.parse(text);
                        if (json.errors) {
                            const fields = Object.keys(json.errors);
                            helpMessage.innerHTML = `<strong>Champs manquants/invalides:</strong> ${fields.join(', ')}<br><small>Utilisez un template complet pour voir tous les champs requis</small>`;
                            helpText.classList.remove('hidden');
                        }
                    } catch(e){}
                }

            } catch (err) {
                respEl.classList.remove('text-yellow-400', 'text-green-400');
                respEl.classList.add('text-red-400');
                respEl.textContent = '❌ Erreur réseau: ' + err.message;
            }
        });
    })();
</script>

