<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Services\DummyJsonImporter;
use Illuminate\Console\Command;

class AddDummyJsonProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-dummy-json-product 
        {--title= : Название продукта}
        {--description= : Описание продукта}
        {--price= : Цена}
        {--brand= : Бренд}
        {--category= : Категория}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = array_filter([
            'title' => $this->option('title'),
            'description' => $this->option('description'),
            'price' => $this->option('price'),
            'brand' => $this->option('brand'),
            'category' => $this->option('category'),
        ]);

        if (empty($data['title'])) {
            $this->error('Поле --title обязательно.');
            return 1;
        }

        try {
            $importer = new DummyJsonImporter();
            $productData = $importer->addProduct($data);

            // Показываем, что пришло от dummyjson
            $this->info('Продукт успешно добавлен на DummyJSON:');
            $this->line(json_encode($productData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            $model = Product::create([
                'id' => $productData['id'],
                'title' => $productData['title'],
                'description' => $productData['description'] ?? null,
                'price' => $productData['price'] ?? 0,
                'brand' => $productData['brand'] ?? null,
                'category' => $productData['category'] ?? null,
            ]);

            $this->info("Продукт сохранён в базу под ID = {$model->id}");

        } catch (\Exception $e) {
            $this->error('Ошибка: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
