<!-- Partial: mini-postman.blade.php -->
<!-- Contient le bouton flottant et l'overlay modal pour le simulateur API -->

<!-- Bouton flottant pour ouvrir le simulateur -->
<button id="mini-postman-toggle" title="Ouvrir le simulateur API" class="fixed bottom-6 right-6 z-50 bg-indigo-600 text-white rounded-full p-3 shadow-lg hover:bg-indigo-700 focus:outline-none">🔧</button>

<!-- Overlay modal du simulateur (caché par défaut) -->
<div id="mini-postman-overlay" class="hidden fixed inset-0 z-40 items-center justify-center bg-black bg-opacity-40 p-4">
    <div class="w-full max-w-3xl bg-white rounded-lg shadow-lg overflow-auto" style="max-height:90vh; min-width:80%;">
        <div class="flex items-center justify-between p-4 border-b">
            <div class="text-lg font-medium text-gray-900">Simulateur API (mini-Postman)</div>
            <div>
                <button id="mini-postman-close" class="text-gray-600 hover:text-gray-800 px-2">Fermer</button>
            </div>
        </div>

        <div class="p-4">
            <!-- Layout: modèles à gauche, formulaire à droite -->
            <div class="grid grid-cols-12 gap-4">
                <!-- Modèles (gauche) -->
                <div class="col-span-12 lg:col-span-4">
                    <div class="text-sm text-gray-600 mb-2">Modèles rapides</div>
                    <div class="space-y-2">
                        <div class="request-template border rounded p-3 hover:bg-gray-50 cursor-pointer" data-method="GET" data-url="/api/ateliers" data-body="">
                            <div class="flex justify-between items-center flex-col">
                                <div>
                                    <div class="text-sm font-semibold">GET /api/ateliers</div>
                                    <div class="text-xs text-gray-500">Lister tous les ateliers</div>
                                </div>
                                <div class="text-xs text-indigo-600">Charger</div>
                            </div>
                        </div>

                        <div class="request-template border rounded p-3 hover:bg-gray-50 cursor-pointer" data-method="POST" data-url="/api/ateliers" data-body='{"titre":"Atelier test","description":"Description de test","prix":20}'>
                            <div class="flex justify-between items-center flex-col">
                                <div>
                                    <div class="text-sm font-semibold">POST /api/ateliers</div>
                                    <div class="text-xs text-gray-500">Créer un nouvel atelier (ex: titre, description, prix)</div>
                                </div>
                                <div class="text-xs text-indigo-600">Charger</div>
                            </div>
                        </div>

                        <div class="request-template border rounded p-3 hover:bg-gray-50 cursor-pointer" data-method="GET" data-url="/api/ateliers/{atelierId}" data-body="">
                            <div class="flex justify-between items-center flex-col">
                                <div>
                                    <div class="text-sm font-semibold">GET /api/ateliers/{atelierId}</div>
                                    <div class="text-xs text-gray-500">Récupérer un atelier par ID (remplacer {atelierId})</div>
                                </div>
                                <div class="text-xs text-indigo-600">Charger</div>
                            </div>
                        </div>

                        <div class="request-template border rounded p-3 hover:bg-gray-50 cursor-pointer" data-method="POST" data-url="/api/atelier/{atelierId}/reservations" data-body='{"client_id":"<clientId>","places":1}'>
                            <div class="flex justify-between items-center flex-col">
                                <div>
                                    <div class="text-sm font-semibold">POST /api/atelier/{atelierId}/reservations</div>
                                    <div class="text-xs text-gray-500">Créer une réservation (remplacer {atelierId} et &lt;clientId&gt;)</div>
                                </div>
                                <div class="text-xs text-indigo-600">Charger</div>
                            </div>
                        </div>

                        <div class="request-template border rounded p-3 hover:bg-gray-50 cursor-pointer" data-method="GET" data-url="/api/clients" data-body="">
                            <div class="flex justify-between items-center flex-col">
                                <div>
                                    <div class="text-sm font-semibold">GET /api/clients</div>
                                    <div class="text-xs text-gray-500">Lister tous les clients</div>
                                </div>
                                <div class="text-xs text-indigo-600">Charger</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulaire (droite) -->
                <div class="col-span-12 lg:col-span-8">
                    <form id="mini-postman-form" class="space-y-3 text-sm">
                        <div class="flex items-center space-x-2">
                            <label for="method" class="sr-only">Méthode</label>
                            <select id="method" class="border rounded-md px-2 py-1 text-sm">
                                <option>GET</option>
                                <option>POST</option>
                                <option>PUT</option>
                                <option>PATCH</option>
                                <option>DELETE</option>
                            </select>

                            <input id="url" type="text" placeholder="Entrez l'URL (ex: /api/ateliers)" class="flex-1 border rounded-md px-2 py-1 text-sm">
                            <button id="send-btn" type="submit" class="ml-2 bg-indigo-600 text-white px-3 py-1 rounded-md text-sm">Envoyer</button>
                        </div>

                        <div>
                            <label for="body" class="block text-xs text-gray-600">Corps (JSON)</label>
                            <textarea id="body" rows="6" class="w-full border rounded-md px-2 py-1 text-xs font-mono" placeholder='{"titre":"Mon atelier","description":"..."}'></textarea>
                        </div>

                        <div>
                            <div class="text-xs text-gray-600 mb-1">Réponse</div>
                            <div id="mini-postman-response" class="w-full h-48 overflow-auto bg-gray-50 border rounded-md p-3 text-xs font-mono whitespace-pre-wrap"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



{{-- Script inline pour le mini-Postman : capture du formulaire, exécution de fetch et affichage de la réponse --}}
<script>
    (function(){
        // toggle overlay
        const toggleBtn = document.getElementById('mini-postman-toggle');
        const overlay = document.getElementById('mini-postman-overlay');
        const closeBtn = document.getElementById('mini-postman-close');
        function showOverlay(){ overlay.classList.remove('hidden'); overlay.classList.add('flex'); document.body.classList.add('overflow-hidden'); }
        function hideOverlay(){ overlay.classList.remove('flex'); overlay.classList.add('hidden'); document.body.classList.remove('overflow-hidden'); }
        toggleBtn?.addEventListener('click', (ev) => { ev.preventDefault(); showOverlay(); });
        closeBtn?.addEventListener('click', (ev) => { ev.preventDefault(); hideOverlay(); });
        // fermer avec Esc ou clic en dehors du panel
        overlay?.addEventListener('click', (ev) => { if (ev.target === overlay) hideOverlay(); });
        document.addEventListener('keydown', (ev) => { if (ev.key === 'Escape') hideOverlay(); });

        const form = document.getElementById('mini-postman-form');
        const methodEl = document.getElementById('method');
        const urlEl = document.getElementById('url');
        const bodyEl = document.getElementById('body');
        const respEl = document.getElementById('mini-postman-response');
        const apiLinks = document.querySelectorAll('.api-route-link');
        const templates = document.querySelectorAll('.request-template');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // remplir l'URL depuis les raccourcis API
        apiLinks.forEach(a => {
            a.addEventListener('click', (e) => {
                e.preventDefault();
                urlEl.value = a.getAttribute('data-uri') || a.href || '';
            });
        });

        // remplir depuis les modèles pré-remplis
        templates.forEach(t => {
            t.addEventListener('click', () => {
                // visual feedback
                templates.forEach(x => x.classList.remove('ring-2', 'ring-indigo-200'));
                t.classList.add('ring-2', 'ring-indigo-200');

                const m = t.getAttribute('data-method') || 'GET';
                const u = t.getAttribute('data-url') || '';
                const b = t.getAttribute('data-body') || '';

                methodEl.value = m;
                urlEl.value = u;
                bodyEl.value = b;
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
            respEl.textContent = 'Envoi en cours...';

            let method = methodEl.value || 'GET';
            let url = urlEl.value.trim();
            if (!url) { respEl.textContent = 'Veuillez fournir une URL.'; return; }

            // normaliser l'URL relative
            if (!/^https?:\/\//i.test(url)){
                if (url.startsWith('/')){
                    url = window.location.origin + url;
                } else {
                    url = window.location.origin + (url.startsWith('api/') ? '/' + url : '/' + url);
                }
            }

            const opts = { method, headers: { 'Accept': 'application/json' } };
            if (method !== 'GET'){
                if (csrf) opts.headers['X-CSRF-TOKEN'] = csrf;
                const bodyText = bodyEl.value.trim();
                if (bodyText) {
                    opts.headers['Content-Type'] = 'application/json';
                    try {
                        opts.body = JSON.stringify(JSON.parse(bodyText));
                    } catch (err) {
                        respEl.textContent = 'JSON invalide: ' + err.message; return;
                    }
                }
            }

            try {
                const res = await fetch(url, opts);
                const statusLine = `HTTP/1.1 ${res.status} ${res.statusText}`;
                let text;
                const contentType = res.headers.get('content-type') || '';
                if (contentType.includes('application/json')){
                    const json = await res.json();
                    text = JSON.stringify(json, null, 2);
                } else {
                    text = await res.text();
                }

                respEl.textContent = statusLine + '\n\n' + prettyPrintHeaders(res.headers) + '\n' + text;

            } catch (err) {
                respEl.textContent = 'Erreur réseau: ' + err.message;
            }
        });
    })();
</script>
