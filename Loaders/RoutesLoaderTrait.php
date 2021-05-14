<?php

namespace Apiato\Core\Loaders;

use Apiato\Core\Foundation\Facades\Apiato;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\SplFileInfo;

trait RoutesLoaderTrait
{
    /**
     * Register all the containers routes files in the framework.
     */
    public function runRoutesAutoLoader(): void
    {
        $containersPaths = Apiato::getAllContainerPaths();

        foreach ($containersPaths as $containerPath) {
            $this->loadApiContainerRoutes($containerPath);
            $this->loadWebContainerRoutes($containerPath);
        }
    }

    /**
     * Register the Containers API routes files.
     */
    private function loadApiContainerRoutes(string $containerPath): void
    {
        // Build the container api routes path
        $apiRoutesPath = $containerPath . '/UI/API/Routes';
        // Build the namespace from the path
        $controllerNamespace = $containerPath . '\\UI\API\Controllers';

        if (File::isDirectory($apiRoutesPath)) {
            $files = File::allFiles($apiRoutesPath);
            $files = Arr::sort($files, fn (SplFileInfo $file): string => $file->getFilename());
            foreach ($files as $file) {
                $this->loadApiRoute($file, $controllerNamespace);
            }
        }
    }

    private function loadApiRoute(SplFileInfo $file, string $controllerNamespace): void
    {
        $routeGroupArray = $this->getApiRouteGroup($file, $controllerNamespace);

        $this->createRouteGroup($file, $routeGroupArray);
    }

    private function createRouteGroup(SplFileInfo $file, array $routeGroupArray): void
    {
        Route::group($routeGroupArray, function (Router $router) use ($file) {
            /** @psalm-suppress UnresolvableInclude dynamic include, psalm cant resolve it */
            require $file->getPathname();
        });
    }

    /**
     * @param string|SplFileInfo $endpointFileOrPrefixString
     * @param null|string        $controllerNamespace
     */
    public function getApiRouteGroup($endpointFileOrPrefixString, $controllerNamespace = null): array
    {
        return [
            'namespace'  => $controllerNamespace,
            'middleware' => $this->getMiddlewares(),
            'domain'     => $this->getApiUrl(),
            // If $endpointFileOrPrefixString is a file then get the version name from the file name, else if string use that string as prefix
            'prefix'     => is_string($endpointFileOrPrefixString) ? $endpointFileOrPrefixString : $this->getApiVersionPrefix($endpointFileOrPrefixString),
        ];
    }

    /**
     * @return string[]
     */
    private function getMiddlewares(): array
    {
        return array_filter([
            'api',
            $this->getRateLimitMiddleware(), // Returns NULL if feature disabled. Null will be removed form the array.
        ]);
    }

    private function getRateLimitMiddleware(): ?string
    {
        $rateLimitMiddleware = null;

        if (Config::get('apiato.api.throttle.enabled')) {
            $rateLimitMiddleware = 'throttle:' . Config::get('apiato.api.throttle.attempts') . ',' . Config::get('apiato.api.throttle.expires');
        }

        return $rateLimitMiddleware;
    }

    private function getApiUrl(): string
    {
        return Config::get('apiato.api.url');
    }

    private function getApiVersionPrefix(SplFileInfo $file): string
    {
        return Config::get('apiato.api.prefix') . (Config::get('apiato.api.enable_version_prefix') ? $this->getRouteFileVersionFromFileName($file) : '');
    }

    private function getRouteFileVersionFromFileName(SplFileInfo $file): string
    {
        $fileNameWithoutExtension = $this->getRouteFileNameWithoutExtension($file);

        $fileNameWithoutExtensionExploded = explode('.', $fileNameWithoutExtension);

        end($fileNameWithoutExtensionExploded);

        $apiVersion = prev($fileNameWithoutExtensionExploded); // get the array before the last one

        // Skip versioning the API's root route
        if ($apiVersion === 'ApisRoot') {
            $apiVersion = '';
        }

        return $apiVersion;
    }

    private function getRouteFileNameWithoutExtension(SplFileInfo $file): string
    {
        $fileInfo = pathinfo($file->getFileName());

        return $fileInfo['filename'];
    }

    /**
     * Register the Containers WEB routes files.
     */
    private function loadWebContainerRoutes(string $containerPath): void
    {
        // Build the container web routes path
        $webRoutesPath = $containerPath . '/UI/WEB/Routes';
        // Build the namespace from the path
        $controllerNamespace = $containerPath . '\\UI\WEB\Controllers';

        if (File::isDirectory($webRoutesPath)) {
            $files = File::allFiles($webRoutesPath);
            $files = Arr::sort($files, fn (SplFileInfo $file): string => $file->getFilename());
            foreach ($files as $file) {
                $this->loadWebRoute($file, $controllerNamespace);
            }
        }
    }

    private function loadWebRoute(SplFileInfo $file, string $controllerNamespace): void
    {
        $routeGroupArray = $this->getAdminRouteGroup($file, $controllerNamespace);

        $this->createRouteGroup($file, $routeGroupArray);
    }

    public function getAdminRouteGroup(SplFileInfo $file, ?string $controllerNamespace = null): array
    {
        return [
            'namespace'  => $controllerNamespace,
            'middleware' => ['web'],
        ];
    }
}
