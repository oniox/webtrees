<?php
/**
 * webtrees: online genealogy
 * Copyright (C) 2019 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
declare(strict_types=1);

namespace Fisharebest\Webtrees\Http\Middleware;

use Fisharebest\Webtrees\DebugBar;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use function app;
use function explode;
use function preg_replace;

/**
 * Take an HTTP request, and forward it to a webtrees RequestHandler.
 */
class RequestRouter implements MiddlewareInterface
{
    private const CONTROLLER_NAMESPACE = '\\Fisharebest\\Webtrees\\Http\\Controllers\\';

    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // If there was a base_url specified in config.ini.php, then use it.
        $base_url = $request->getAttribute('base_url', $this->extractBaseUrl($request));
        $request  = $request->withAttribute('base_url', $base_url);

        // Bind the request into the container
        app()->instance(ServerRequestInterface::class, $request);

        // Load the route and routing table.
        $route  = $request->getQueryParams()['route'] ?? '';
        $routes = require 'routes/web.php';

        // Find the routing for the selected route.
        $routing = $routes[$request->getMethod() . ':' . $route] ?? '';

        // This timer gets stopped automatically when we generate the response.
        DebugBar::startMeasure('controller_action');

        // No route matched?
        if ($routing === '') {
            return $handler->handle($request);
        }

        // Routes defined using controller@action
        if (Str::contains($routing, '@')) {
            [$class, $method] = explode('@', $routing);

            $controller = app(self::CONTROLLER_NAMESPACE . $class);

            return app()->dispatch($controller, $method);
        }

        // Routes defined using a request handler
        return app($routing)->handle($request);
    }

    private function extractBaseUrl(ServerRequestInterface $request): string
    {
        $base_url = (string) $request->getUri();
        $base_url = preg_replace('/index\.php.*/', '', $base_url);

        return $base_url;
    }
}
