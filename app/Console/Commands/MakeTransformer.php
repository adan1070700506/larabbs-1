<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class MakeTransformer extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bbs:make-transformer {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建 transformer';

    protected function getStub()
    {
        return __DIR__.'/stubs/transformer';
    }

    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'Transformer.php';
    }

    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        return $this->replaceClass($this->replaceInstance($stub, $name), $name);
    }

    protected function replaceInstance($stub, $name)
    {
        $class = str_replace($this->getNamespace($name).'\\', '', $name);

        return str_replace('dummyInstance', lcfirst($class), $stub);
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Transformers';
    }
}