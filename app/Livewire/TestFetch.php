<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;
use App\Models\Atelier;

class TestFetch extends Component
{
    public $ateliers = [];
    public $debug = null;

    public function mount()
    {
        $json = $this->fetchAteliers();

        // Fallback sur le fixture si pas de données
        if (empty($json)) {
            $json = $this->loadFixture();
        }

        $this->ateliers = $this->hydrateAteliers($json);
    }

    private function fetchAteliers(): ?array
    {
        $urls = $this->buildCandidateUrls();

        foreach ($urls as $url) {
            try {
                $response = Http::timeout(10)->get($url);

                if ($response->ok()) {
                    $data = $response->json();
                    $this->debug['source'] = $url;
                    return is_array($data) ? $data : null;
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        // Tentative interne si HTTP échoue
        return $this->fetchInternal();
    }

    private function buildCandidateUrls(): array
    {
        $base = config('app.url', 'http://127.0.0.1:8000');

        return [
            rtrim($base, '/') . '/api/ateliers'
        ];
    }

    private function fetchInternal(): ?array
    {
        try {
            $request = \Illuminate\Http\Request::create('/api/ateliers', 'GET');
            $response = app()->handle($request);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                $decoded = json_decode($response->getContent(), true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $this->debug['source'] = 'internal';
                    return $decoded;
                }
            }
        } catch (\Throwable $e) {
            // Silent fail
        }

        return null;
    }

    private function loadFixture(): ?array
    {
        $path = resource_path('fixtures/ateliers_sample.json');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            $data = json_decode($content, true);
            $this->debug['source'] = 'fixture';
            return $data;
        }

        return null;
    }

    private function hydrateAteliers(?array $json): array
    {
        if (empty($json)) {
            return [];
        }

        $items = $json['data'] ?? $json;
        $normalized = $this->normalizeItems($items);

        if (empty($normalized)) {
            return [];
        }

        return Atelier::hydrate($normalized)->all();
    }

    private function normalizeItems(array $items): array
    {
        $normalized = [];

        foreach ($items as $item) {
            if (is_object($item)) {
                $item = (array) $item;
            }

            if (!is_array($item)) {
                continue;
            }

            // Normaliser _id MongoDB
            if (isset($item['_id'])) {
                $raw = $item['_id'];

                if (is_array($raw) && isset($raw['$oid'])) {
                    $item['_id'] = $raw['$oid'];
                } elseif (is_object($raw)) {
                    $item['_id'] = (string) $raw;
                } else {
                    $item['_id'] = (string) $raw;
                }

                if (!isset($item['id'])) {
                    $item['id'] = $item['_id'];
                }
            }

            $normalized[] = $item;
        }

        return $normalized;
    }

    public function render()
    {
        return view('livewire.test-fetch')->layout('layouts.base');
    }
}
