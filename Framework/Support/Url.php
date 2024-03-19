<?php

namespace Framework\Support;

use App\Http\Controllers\DashboardController;
use Framework\Foundation\Application;
use Framework\Foundation\Config;
use Framework\Http\Request;
use Framework\Routing\Router;

/**
 * The URL class represents a utility for generating and manipulating URLs.
 *
 * This class provides methods for generating absolute URLs, retrieving the base URL for the application,
 * and getting the current URL. It supports handling of query parameters and provides options to exclude
 * the host from the generated URLs if needed.
 *
 * @package Framework\Support
 */
class Url
{
    /**
     * Get the application's router.
     *
     * @return Router
     */
    protected static function router(): Router
    {
        return Application::get_instance()->get(Router::class);
    }

    /**
     * Generate a URL for the given route name.
     *
     * @param string $route_name The name of the route.
     * @param array $parameters [optional] Parameters to substitute into the route URI.
     * @param bool $absolute [optional] Whether to generate an absolute URL (including scheme and host).
     * @return string The generated URL.
     */
    public static function route(string $route_name, array $parameters = [], bool $absolute = true): string
    {
        $route_uri = self::router()::route($route_name, $parameters) ?: $route_name;

        if ($absolute) {
            $route_uri = url()::app_url() . ltrim($route_uri, '/');
        }

        return $route_uri;
    }

    /**
     * Get the base URL for the application.
     *
     * @return string|null The base URL for the application. Returns relative path of document root to project directory if 'app_url' is not set.
     */
    public static function app_url(): ?string
    {
        $path = str_replace(server()->get('DOCUMENT_ROOT'), null, backslashes_to_slashes(base_path()));

        return $path ? request()->base_url() . ltrim($path, '/') : config('app.app_url');
    }

    /**
     * Generate an absolute URL for the given path and parameters, optionally excluding the host.
     *
     * @param string $path The path to the resource.
     * @param array $parameters [optional] Parameters to append to the URL as query parameters.
     * @param bool $exclude_host [optional] Whether to exclude the host from the generated URL.
     * @return string The generated absolute URL.
     */
    public static function to(string $path, array $parameters = [], bool $exclude_host = false): string
    {
        $url = self::app_url() . ltrim($path, '/');

        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters);
        }

        if ($exclude_host) {
            $parts = parse_url($url);
            $url = $parts['path'];

            if (isset($parts['query'])) {
                $url .= '?' . $parts['query'];
            }
        }

        return $url;
    }

    /**
     * Get the current URL.
     *
     * @return string The current URL.
     */
    public static function current(): string
    {
        return request()->base_url() . ltrim(request()->path(), '/');
    }
}
