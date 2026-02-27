<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeService extends GeneratorCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:service {name} {plural}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um novo service';

    /**
     * The console command type.
     *
     * @var string
     */
    protected $type = 'Service';

    /**
     * O diretório onde o repositório será criado
     *
     * @var string
     */
    protected $destination = 'app/Services';

    protected function getStub()
    {
        return resource_path('stubs/service.stub');
    }

    protected function getNameInput()
    {
        return ucfirst($this->argument('name'));
    }

    /**
     * @param string   $property
     */
    protected function getPath($name)
    {
        return $this->destination . '/' . $this->getNameInput() . '.php';
    }

    /**
     * @param string   $property
     * @param string   $property
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $arguments = $this->arguments();

        $path = str_replace('\\'. $this->getNameClass() . $this->getTypeClass(), '' , $name);
        $path = str_replace('App\\', 'App\Services\\' , $path);

        $stub = str_replace('{{ namespace }}', $path, $stub);
        $stub = str_replace('{{ contract }}', 'App\Services\\' . $this->getNameModule() . '\\' . $arguments['plural'] . '\Contracts\\' . $this->getNameClass() . 'ServiceInterface', $stub);
        $stub = str_replace('{{ names }}', $arguments['plural'], $stub);
        $stub = str_replace('{{ name }}', $this->getNameClass(), $stub);
        $stub = str_replace('{{ module }}', $this->getNameModule(), $stub);

        return parent::replaceNamespace($stub, $name);
    }

    private function getNameClass(): string{
        $fileName = $this->getNameInput();

        // Extrai o nome do arquivo sem a extensão
        $fileBaseName = pathinfo($fileName, PATHINFO_FILENAME);
        
        // Divide o nome do arquivo em palavras com base na capitalização (CamelCase)
        $words = preg_split('/(?=[A-Z])/', $fileBaseName);
        
        // Remove a última palavra
        array_pop($words);
        
        // Junta as palavras restantes
        $modifiedName = implode('', $words);
        
        return $modifiedName;
    }

    private function getTypeClass(): string{

        $fileName = $this->getNameInput();

        // Extrai o nome do arquivo sem a extensão
        $fileBaseName = pathinfo($fileName, PATHINFO_FILENAME);

        // Divide o nome do arquivo em palavras com base na capitalização (CamelCase)
        $words = preg_split('/(?=[A-Z])/', $fileBaseName);

        // Pega a primeira palavra
        return $words[count($words)-1]; 
    }

    private function getNameModule() : string {
        $explodeNames =  explode('/',  $this->getNameInput());
        $module = $explodeNames[0];
        return $module; 
    }
}