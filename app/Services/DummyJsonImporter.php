<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DummyJsonImporter
{
    public function import(string $resource, callable $filter = null, string $modelClass, callable $mapper = null, string $search = null)
    {
        $url = "https://dummyjson.com/{$resource}";
        if ($search) {
            $url .= "/search?q=" . urlencode($search);
        } else {
            $url .= "?limit=10";
        }

        $response = Http::get($url);

        if (!$response->successful()) {
            throw new \Exception("Ошибка загрузки ресурса: {$resource}");
        }

        $items = $response->json()[$resource] ?? $response->json()['products'] ?? []; // на случай если в search ответ "products"

        foreach ($items as $item) {
            if ($filter && !$filter($item)) {
                continue;
            }

            if ($mapper) {
                $item = $mapper($item);
            }

            $model = new $modelClass;

            if ($model::find($item['id'])) {
                echo "Пропущено: " . ($item['title'] ?? $item['firstName']) . "\n";
                continue;
            }

            $model::create($this->filterFields($item, $model->getFillable()));

            echo "Добавлен: " . ($item['title'] ?? $item['firstName']) . "\n";
        }
    }

    private function filterFields(array $data, array $allowed)
    {
        return array_filter($data, fn($key) => in_array($key, $allowed), ARRAY_FILTER_USE_KEY);
    }

    public function addProduct(array $data): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://dummyjson.com/products/add', $data);

        if (!$response->successful()) {
            throw new \Exception('Ошибка добавления продукта: ' . $response->body());
        }

        return $response->json();
    }
    public function addUser(array $data): array
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json'
        ])->post('https://dummyjson.com/users/add', $data);

        if (!$response->successful()) {
            throw new \Exception('Ошибка добавления пользователя: ' . $response->body());
        }

        return $response->json();
    }
}
