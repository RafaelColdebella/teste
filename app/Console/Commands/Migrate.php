<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Migrate extends Command
{
    protected $signature = 'central:migrate {--refresh}';

    protected $description = 'Run migrations central';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $command = ($this->option('refresh') ? 'migrate:refresh' : 'migrate') . ' --path=/database/migrations/central';

        try {
            $this->info('Iniciando as migrações...');
            Artisan::call($command);
            $this->info('Migrações executadas com sucesso!');
        } catch (\Throwable $th) {
            $this->error('Erro ao migrar: '. $th->getMessage());
        }
    }
}
