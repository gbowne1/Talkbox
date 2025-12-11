<?php

namespace TalkBox\Server;

use Ratchet\Http\HttpServer as RatchetHttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Http\Router;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Basic HTTP server for TalkBox
 * Handles simple REST endpoints (login, user list, etc.)
 */
class HttpServer
{
    protected $routes;

    public function __construct()
    {
        $this->routes = new RouteCollection();

        // Define routes
        $this->routes->add('login', new Route('/login', [
            '_controller' => [$this, 'login']
        ], [], [], '', [], ['POST']));

        $this->routes->add('users', new Route('/users', [
            '_controller' => [$this, 'users']
        ], [], [], '', [], ['GET']));
    }

    public function run($port = 8081)
    {
        $server = IoServer::factory(
            new RatchetHttpServer(
                new Router(function (Request $request) {
                    return $this->handleRequest($request);
                })
            ),
            $port
        );

        echo "TalkBox HTTP Server running on port {$port}\n";
        $server->run();
    }

    protected function handleRequest(Request $request)
    {
        $context = new RequestContext();
        $context->fromRequest($request);

        $matcher = new UrlMatcher($this->routes, $context);

        try {
            $parameters = $matcher->match($request->getPathInfo());
            $controller = $parameters['_controller'];
            return call_user_func($controller, $request);
        } catch (\Exception $e) {
            return new Response("Not Found", 404);
        }
    }

    // Example controller: login
    public function login(Request $request)
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        // TODO: validate against database
        if ($username === 'demo' && $password === 'demo') {
            return new Response(json_encode([
                'success' => true,
                'message' => 'Login successful'
            ]), 200, ['Content-Type' => 'application/json']);
        }

        return new Response(json_encode([
            'success' => false,
            'message' => 'Invalid credentials'
        ]), 401, ['Content-Type' => 'application/json']);
    }

    // Example controller: user list
    public function users(Request $request)
    {
        // TODO: fetch from database or WebSocketServer
        $users = ['Alice', 'Bob', 'Charlie'];

        return new Response(json_encode([
            'success' => true,
            'users' => $users
        ]), 200, ['Content-Type' => 'application/json']);
    }
}
