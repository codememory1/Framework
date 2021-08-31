<?php

namespace Kernel;

use Codememory\Component\Toolbar\Toolbar;
use Codememory\Components\Caching\Cache;
use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Configuration\Config;
use Codememory\Components\Configuration\Exceptions\ConfigNotFoundException;
use Codememory\Components\Configuration\Exceptions\NotOpenConfigException;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
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
use Codememory\Components\Profiling\Profiler;
use Codememory\Components\Profiling\Utils as ProfilingUtils;
use Codememory\Container\ServiceProvider\Interfaces\InjectionProviderInterface;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use Codememory\Database\Redis\Connections\Connection;
use Codememory\Database\Redis\RedisManager;
use Codememory\FileSystem\File;
use Codememory\HttpFoundation\Client\Header\Header;
use Codememory\HttpFoundation\Client\Url;
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
     * @var ConfigInterface
     */
    private ConfigInterface $frameworkConfig;

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

        $this->frameworkConfig = (new Config(new File()))->open('framework');

        $this->initToolbar();

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
     * @throws ConfigPathNotExistException
     * @throws ConstructorNotInitializedException
     * @throws EnvironmentVariableNotFoundException
     * @throws IncorrectControllerException
     * @throws IncorrectPathToEnviException
     * @throws InvalidControllerMethodException
     * @throws NotOpenConfigException
     * @throws ParsingErrorException
     * @throws SingleConstructorInitializationException
     * @throws VariableParsingErrorException
     */
    private function initialization(): void
    {

        Environment::__constructStatic(new File());

        $statusInitRouter = $this->initializationRouting();

        if($statusInitRouter) {
            $this->initializationControlResponseCode();
        }

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
    private function initializationRouting(): bool
    {

        Router::__constructStatic(new Request());
        Router::initializingRoutesFromConfig();

        Profiler::init();

        $this->initializationProfiler();

        Router::processAllRoutes();

        return true;

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

            if ($frameworkPackage->last() > $frameworkPackage->current()) {
                $_SERVER['CDM_INFO'][] = sprintf('There is a new version of the framework <b>%s</b>!', $frameworkPackage->last());
            }

        }
    }

    /**
     * @return void
     */
    private function initializationProfiler(): void
    {

        $url = new Url();
        $profilingUtils = new ProfilingUtils();
        $link = sprintf('%s%s.%s', $url->getScheme(), $profilingUtils->profilerSubdomain(), $url->getHost());

        $_SERVER['CDM_INFO'][] = sprintf('Link to profiler page <a href="%s">Profiler</a>', $link);

    }

    /**
     * @return void
     */
    private function initToolbar(): void
    {

        if (isDev() && $this->frameworkConfig->get('toolbar.enabled')
            || $this->frameworkConfig->get('toolbar.enabledInProduction')) {
            (new Toolbar())->connectToolbar();
        }

    }

}