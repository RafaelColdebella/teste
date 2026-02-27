<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeRepository extends GeneratorCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:repository {name} {plural}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria um novo repositório';

    /**
     * The console command type.
     *
     * @var string
     */
    protected $type = 'Repository';

    /**
     * O diretório onde o repositório será criado
     *
     * @var string
     */
    protected $destination = 'app/Repositories';

    protected function getStub()
    {
        return resource_path('stubs/repository.stub');
    }

    protected function getNameInput()
    {
        return ucfirst($this->argument('name'));
    }

    /**
     * @param string
     */
    protected function getPath($name)
    {
        return $this->destination . '/' . $this->getNameInput() . '.php';
    }

    /**
     * @param string
     * @param string
     */
    protected function replaceNamespace(&$stub, $name)
    {
        $arguments = $this->arguments();

        $path = str_replace('\\'. $this->getNameClass() . $this->getTypeClass(), '' , $name);
        $path = str_replace('App\\', 'App\Repositories\\' , $path);

        $stub = str_replace('{{ namespace }}', $path, $stub);
        $stub = str_replace('{{ model }}', 'App\Models\\' . $this->getNameModule() . '\\' . $arguments['plural']  . '\\' . $this->getNameClass(), $stub);
        $stub = str_replace('{{ contract }}', 'App\Repositories\\' . $this->getNameModule() . '\\' . $arguments['plural'] . '\Contracts\\' . $this->getNameClass() . 'RepositoryInterface', $stub);
        $stub = str_replace('{{ name }}', $this->getNameClass(), $stub);

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