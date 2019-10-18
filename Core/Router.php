<?php

namespace Core;

class Router
{
    protected $routes = [];
    protected $params = [];

    public function add(string $route, array $params = [])
    {
        $route = preg_replace('/\//', '\\/', $route);

        $route = preg_replace('/\{(controller)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{(action)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{(id)\}/', '(?P<\1>[0-9]+)', $route);

        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    public function match(string $url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {

                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function dispatch(string $url)
    {
        $url = $this->removeQueryStringVariables($url);

        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = $this->getNamespace() . $controller;

            if (class_exists($controller)) {
                $controller_object = new $controller($this->params);

                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);

                if (preg_match('/action$/i', $action) == 0) {
                    $controller_object->$action();

                } else {
                    throw new \Exception("Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method");
                }
            } else {
                throw new \Exception("Controller class $controller not found");
            }
        } else {
            throw new \Exception('No route matched', 404);
        }
    }

    protected function convertToStudlyCaps(string $string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase(string $string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    protected function removeQueryStringVariables(string $url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);

            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }

        return $url;
    }

    protected function getNamespace()
    {
        $namespace = 'App\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $namespace .= $this->params['namespace'] . '\\';
        }

        return $namespace;
    }
}
