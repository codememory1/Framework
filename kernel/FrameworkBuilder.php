<?php

namespace Kernel;

use Codememory\Components\Caching\Cache;
use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Collectors\CssCollector;
use Codememory\Components\Configuration\Exceptions\ConfigNotFoundException;
use Codememory\Components\Configuration\Exceptions\NotOpenConfigException;
use Codememory\Components\DateTime\DateTime;
use Codememory\Components\DateTime\Time;
use Codememory\Components\Environment\Environment;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\IncorrectPathToEnviException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\Components\Finder\Find;
use Codememory\Components\JsonParser\Exceptions\JsonErrorException;
use Codememory\Components\JsonParser\JsonParser;
use Codememory\Components\Logging\Logging;
use Codememory\Components\Markup\Types\YamlType;
use Codememory\Container\ServiceProvider\Interfaces\InjectionProviderInterface;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use Codememory\Database\Redis\Connections\Connection;
use Codememory\Database\Redis\RedisManager;
use Codememory\FileSystem\File;
use Codememory\HttpFoundation\Client\Header\Header;
use Codememory\HttpFoundation\ControlHttpStatus\ControlResponseCode;
use Codememory\HttpFoundation\Request\Request;
use Codememory\HttpFoundation\Response\Response;
use Codememory\Routing\Exceptions\ConstructorNotInitializedException;
use Codememory\Routing\Exceptions\IncorrectControllerException;
use Codememory\Routing\Exceptions\InvalidControllerMethodException;
use Codememory\Routing\Exceptions\SingleConstructorInitializationException;
use Codememory\Routing\Router;
use GuzzleHttp\Exception\GuzzleException;
use Redis;

/**
 * Class Kernel
 *
 * @package Kernel
 *
 * @author  Codememory
 */
class FrameworkBuilder
{

    /**
     * Kernel constructor.
     *
     * @throws ConfigNotFoundException
     * @throws ConfigPathNotExistException
     * @throws ConstructorNotInitializedException
     * @throws EnvironmentVariableNotFoundException
     * @throws GuzzleException
     * @throws IncorrectControllerException
     * @throws IncorrectPathToEnviException
     * @throws InvalidControllerMethodException
     * @throws JsonErrorException
     * @throws NotOpenConfigException
     * @throws ParsingErrorException
     * @throws SingleConstructorInitializationException
     * @throws VariableParsingErrorException
     */
    public function __construct()
    {

        $this->initialization();
        $this->checkFrameworkVersion();

    }

    /**
     * @param ServiceProviderInterface $serviceProvider
     *
     * @return void
     */
    public static function registerProviders(ServiceProviderInterface $serviceProvider): void
    {

        $serviceProvider
            ->register('filesystem', File::class)
            ->register('date', DateTime::class)
            ->register('time', Time::class)
            ->register('cache', Cache::class, function (InjectionProviderInterface $injectionProvider) {
                $injectionProvider->construct([
                    new YamlType(),
                    new File()
                ]);
            })
            ->register('json-parser', JsonParser::class)
            ->register('finder', Find::class)
            ->register('redis', RedisManager::class, function (InjectionProviderInterface $injectionProvider) {
                $injectionProvider->construct([new Connection(new Redis())]);
            })
            ->register('logging', Logging::class);

    }

    /**
     * @throws ConfigNotFoundException
     * @throws ConstructorNotInitializedException
     * @throws EnvironmentVariableNotFoundException
     * @throws IncorrectControllerException
     * @throws IncorrectPathToEnviException
     * @throws InvalidControllerMethodException
     * @throws NotOpenConfigException
     * @throws ParsingErrorException
     * @throws SingleConstructorInitializationException
     * @throws VariableParsingErrorException
     * @throws ConfigPathNotExistException
     */
    private function initialization(): void
    {

        Environment::__constructStatic(new File());

        $this->initializationRouting();
        $this->initializationControlResponseCode();

    }

    /**
     * @throws ConfigNotFoundException
     * @throws ConstructorNotInitializedException
     * @throws EnvironmentVariableNotFoundException
     * @throws IncorrectControllerException
     * @throws IncorrectPathToEnviException
     * @throws InvalidControllerMethodException
     * @throws ParsingErrorException
     * @throws SingleConstructorInitializationException
     * @throws VariableParsingErrorException
     */
    private function initializationRouting(): void
    {

        Router::__constructStatic(new Request());
        Router::initializingRoutesFromConfig();

        Router::processAllRoutes();

    }

    /**
     * @throws NotOpenConfigException
     */
    private function initializationControlResponseCode(): void
    {

        $controlResponseCode = new ControlResponseCode(new Response(new Header()));

        $controlResponseCode->trackResponseStatus();

    }

    /**
     * @throws JsonErrorException
     * @throws GuzzleException
     */
    private function checkFrameworkVersion(): void
    {

        if (isDev()) {
            $frameworkPackage = new FrameworkPackage();
            $cssCollector = new CssCollector();

            $styles = [
                'background'    => '#e22424',
                'color'         => '#fff',
                'padding'       => '10px 20px',
                'border-radius' => '3px',
                'font-weight'   => 600,
                'font-family'   => 'monospace',
                'position'      => 'absolute',
                'right'         => '10px'
            ];

            if ($frameworkPackage->last() > $frameworkPackage->current()) {
                echo sprintf('<span style="%s">There is a new version of the framework <b>%s</b>!<span>', $cssCollector->toString($styles), $frameworkPackage->last());
            }

        }
    }

}