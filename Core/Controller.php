<?php

namespace Core;

abstract class Controller
{
    protected $routeParams = [];

    public function __construct(array $routeParams)
    {
        $this->routeParams = $routeParams;
    }

    public function __call(string $name, array $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    protected function before()
    {
    }

    protected function after()
    {
    }
}
