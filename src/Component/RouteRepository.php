<?php

namespace Snowdog\DevTest\Component;

use FastRoute\RouteCollector;
use Snowdog\DevTest\Controller\ForbiddenAction;

class RouteRepository
{
    private static $instance = null;
    private $routes = [];
    const HTTP_METHOD = 'http_method';
    const ROUTE = 'route';
    const CLASS_NAME = 'class_name';
    const METHOD_NAME = 'method_name';
    const ACCESS_TYPE_GUEST = 'guest';
    const ACCESS_TYPE_LOGGED = 'logged';


    /**
     * @return RouteRepository
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function registerRoute($httpMethod, $route, $className, $methodName, $accessType)
    {
        $instance = self::getInstance();
        switch ($accessType) {
            case self::ACCESS_TYPE_GUEST:
                if (isset($_SESSION['login'])) {
                    $className = ForbiddenAction::class;
                    $methodName = 'execute';
                }
                break;
            case self::ACCESS_TYPE_LOGGED:
                if (!isset($_SESSION['login'])) {
                    $className = ForbiddenAction::class;
                    $methodName = 'execute';
                }
                break;
        }

        $instance->addRoute($httpMethod, $route, $className, $methodName);
    }

    public function __invoke(RouteCollector $r)
    {
        foreach ($this->routes as $route) {
            $r->addRoute(
                $route[self::HTTP_METHOD],
                $route[self::ROUTE],
                [
                    $route[self::CLASS_NAME],
                    $route[self::METHOD_NAME]
                ]
            );
        }
    }

    private function addRoute($httpMethod, $route, $className, $methodName)
    {
        $this->routes[] = [
            self::HTTP_METHOD => $httpMethod,
            self::ROUTE => $route,
            self::CLASS_NAME => $className,
            self::METHOD_NAME => $methodName,
        ];
    }
}