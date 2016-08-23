<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 8/22/16
 * Time: 11:15 PM
 */

namespace Activelogiclabs\Administration\Commands;

use Illuminate\Console\GeneratorCommand;

class AdminControllerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:admin-controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/admin-controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }


    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $namespace = $this->getNamespace($name);

        return str_replace("use $namespace\Controller;\n", '', parent::buildClass($name));
    }
}