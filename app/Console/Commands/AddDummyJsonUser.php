<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\DummyJsonImporter;
use Illuminate\Console\Command;

class AddDummyJsonUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-dummy-json-user
        {--firstName= : Имя}
        {--lastName= : Фамилия}
        {--maidenName= : Девичья фамилия}
        {--email= : E-mail}
        {--password= : Пароль}
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
            'firstName' => $this->option('firstName'),
            'lastName' => $this->option('lastName'),
            'maidenName' => $this->option('maidenName'),
            'email' => $this->option('email'),
            'password' => $this->option('password'),
        ]);

        if (empty($data['firstName'])) {
            $this->error('Поле --firstName обязательно.');
            return 1;
        }

        try {
            $importer = new DummyJsonImporter();
            $userData = $importer->addUser($data);

            $this->info('Пользователь успешно добавлен');

            $model = User::create([
                'id' => $userData['id'],
                'firstName' => $userData['firstName'],
                'lastName' => $userData['lastName'],
                'maidenName' => $userData['maidenName'],
                'email' => $userData['email'],
                'password' => bcrypt($userData['password']),
            ]);

            $this->info("Пользователь сохранён в базу под ID = {$model->id}");
            $this->line(json_encode($model, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        } catch (\Exception $e) {
            $this->error('Ошибка: ' . $e->getMessage());
        }
    }
}
