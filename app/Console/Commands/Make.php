<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Make extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make {module} {name} {plural}';
    
    /**
     * The console command description.
     *
     * @var string
     */    
    protected $description = 'Run migrations central';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $commands = [];
        $arguments = $this->arguments();

        try {
            $this->info('Iniciando criação da estrutura...');
            array_push($commands, 'make:model ' . $this->stringToPath('model', $arguments['module']) .  '/' . $arguments['plural'] . '/' . $arguments['name']);
            array_push($commands, 'make:migration ' .  $arguments['plural'] . ' --path=' . $this->stringToPath('migration', $arguments['module']));
            array_push($commands, 'make:controller ' . $this->stringToPath('controller', $arguments['module']) . '/' . $arguments['plural'] . '/' . $arguments['name'] . 'Controller');
            array_push($commands, 'make:request ' . $this->stringToPath('request', $arguments['module']) . '/' .  $arguments['plural'] . '/' . 'Store' . $arguments['name'] . 'Request');
            array_push($commands, 'make:request ' . $this->stringToPath('request', $arguments['module']) . '/' .  $arguments['plural'] . '/' . 'Update' . $arguments['name'] . 'Request');
            array_push($commands, 'make:resource ' . $this->stringToPath('resource', $arguments['module']) . '/' .  $arguments['plural'] . '/' . $arguments['name'] . 'Resource');
            array_push($commands, 'make:resource ' . $this->stringToPath('resource', $arguments['module']) . '/' .  $arguments['plural'] . '/' . $arguments['name'] . 'Collection');

            array_push($commands, 'make:repository ' . $this->stringToPath('repository', $arguments['module']) . '/' . $arguments['plural'] . '/' . $arguments['name'] . 'Repository ' . $arguments['plural']);
            array_push($commands, 'make:interface Repositories/' . $this->stringToPath('interface', $arguments['module']) . '/' .  $arguments['plural'] . '/Contracts/' . $arguments['name'] . 'RepositoryInterface');

            array_push($commands, 'make:service ' . $this->stringToPath('service', $arguments['module']) . '/' .  $arguments['plural'] . '/' . $arguments['name'] . 'Service ' .  $arguments['plural']);
            array_push($commands, 'make:interface Services/' . $this->stringToPath('interface', $arguments['module']) . '/' .  $arguments['plural'] . '/Contracts/' . $arguments['name'] . 'ServiceInterface');

            foreach ($commands as $command) {
                Artisan::call($command);
            }

            $this->info('Comando executado com sucesso!');
        } catch (\Throwable $th) {
            $this->error('Erro ao migrar: '. $th->getMessage());
        }
    }

    private function stringToPath(string $type, string $aString) {
        switch ($type) {
            case 'migration':
                switch ($aString) {
                    case 'service':
                        return '/database/migrations/service';
                    case 'central':
                        return '/database/migrations/central';
                }
            case 'resource':
            case 'interface':
            case 'repository':
            case 'model':
            case 'request':
            case 'controller':
            case 'service':
                switch ($aString) {
                    case 'service':
                        return 'Service';
                    case 'central':
                        return 'Central';
                }
            break;
        }
    }
}
