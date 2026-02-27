<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class MakeInterface extends GeneratorCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'make:interface {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria uma nova interface';

    /**
     * The console command type.
     *
     * @var string
     */
    protected $type = 'Interface';

    /**
     * O diretório onde o repositório será criado
     *
     * @var string
     */
    protected $destination = 'app';

    protected function getStub()
    {
        return resource_path('stubs/interface.stub');
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
        $path = str_replace('\\'. $this->getNameClass() . 'Interface', '' , $name);
        

        $stub = str_replace('{{ namespace }}', $path, $stub);
        $stub = str_replace('{{ interfaceBasePath }}', 'App\\' .  $this->getModule($name) . '\Shared\Contracts\Base' . $this->getNameModule($name) . 'Interface' ,  $stub);
        $stub = str_replace('{{ interfaceBase }}', 'Base' . $this->getNameModule($name) . 'Interface' ,  $stub);
        $stub = str_replace('{{ name }}', $this->getNameClass() . $this->getTypeClass(), $stub);

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

    private function getModule(string $module) : string {
        if(strpos($module, 'Repository') !== false){
            return 'Repositories';
        }
        else if(strpos($module, 'Services')) {
            return 'Services';
        }
    }

    private function getNameModule(string $module) : string {
        if(strpos($module, 'Repository') !== false){
            return 'Repository';
        }
        else if(strpos($module, 'Services')) {
            return 'Service';
        }
    }
}