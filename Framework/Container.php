<?php

namespace Framework;

use ReflectionNamedType;

class Container
{
    private array $definitions = [];

    private array $resolved = [];

    public function addDefinitions(string $containerDefinitionPath)
    {
        $containerDefinitions = require basePath($containerDefinitionPath);
        $this->definitions = [...$this->definitions, ...$containerDefinitions];
    }

    public function resolve(string $className)
    {
        $reflectionClass = new \ReflectionClass($className);

        //can be class be instaintiated, i.e not an abstract or interface class
        if (!$reflectionClass->isInstantiable()) {
            throw new \Exception("$className cannot be instantiated!");
        }

        //check if class as a constructor or an arguments in constructor
        $constructor = $reflectionClass->getConstructor();

        $params = $constructor?->getParameters();

        if (!$constructor || count($params) === 0) {
            return new $className;
        }

        foreach ($params as $key => $param) {
            $name = $param->getName();
            $type = $param->getType();

            //if it doesnt have a type or the type is a buitin like string
            if (!$type || !($type instanceof ReflectionNamedType) || $type->isBuiltin()) {
                throw new \Exception("{$name} failed to resolve class name");
            }

            $dependencies[] = $this->get($type->getName());
        }

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    public function get($name)
    {
        if (!array_key_exists($name, $this->definitions)) {
            throw new \Exception('class not registered');
        }

        if (array_key_exists($name, $this->resolved)) {
            return $this->resolved[$name];
        }

        $factory = $this->definitions[$name];

        //Calls the factory function with $this, instantiate the dependency and 
        //resolve other dependencies dynamically by using the container itself--recursive
        $dependency = $factory($this);

        //store for reuse
        $this->resolved[$name] = $dependency;

        return $dependency;

    }
}