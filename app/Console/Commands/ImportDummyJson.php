<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use App\Services\DummyJsonImporter;
use Illuminate\Console\Command;

class ImportDummyJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-dummy-json {resource} {--search=}';

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
        $resource = $this->argument('resource');
        $searchQuery = $this->option('search');
        $importer = new DummyJsonImporter();

        $map = [
            'products' => [
                'model' => Product::class,
                'filter' => function ($item) {
                    return $item['brand'] === 'Apple';
                },
            ],
            'users' => [
                'model' => User::class,
                'filter' => null
            ],
        ];

        if (!isset($map[$resource])) {
            $this->error("Ресурс '$resource' не поддерживается.");
            return;
        }

        try {
            $importer->import(
                $resource,
                $map[$resource]['filter'],
                $map[$resource]['model'],
                null,
                $searchQuery
            );
            $this->info("Импорт '{$resource}' завершён.");
        } catch (\Exception $e) {
            $this->error("Ошибка: " . $e->getMessage());
        }
    }
}
