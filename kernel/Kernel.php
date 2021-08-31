<?php

namespace Kernel;

use Codememory\Component\Toolbar\Toolbar;
use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Configuration\Exceptions\ConfigNotFoundException;
use Codememory\Components\Configuration\Exceptions\NotOpenConfigException;
use Codememory\Components\Environment\Environment;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\IncorrectPathToEnviException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\Components\Profiling\Profiler;
use Codememory\Components\Profiling\Utils as ProfilerUtils;
use Codememory\HttpFoundation\ControlHttpStatus\ControlResponseCode;
use Codememory\Routing\Exceptions\ConstructorNotInitializedException;
use Codememory\Routing\Exceptions\IncorrectControllerException;
use Codememory\Routing\Exceptions\InvalidControllerMethodException;
use Codememory\Routing\Exceptions\SingleConstructorInitializationException;
use Codememory\Routing\Router;

/**
 * Class Kernel
 *
 * @package Kernel
 *
 * @author  Codememory
 */
class Kernel
{

    /**
     * Kernel Construct
     */
    public function __construct()
    {

        new ObjectInitializer();

    }

    /**
     * @return $this
     * @throws ConfigNotFoundException
     * @throws ConfigPathNotExistException
     * @throws ConstructorNotInitializedException
     * @throws EnvironmentVariableNotFoundException
     * @throws IncorrectControllerException
     * @throws IncorrectPathToEnviException
     * @throws InvalidControllerMethodException
     * @throws ParsingErrorException
     * @throws SingleConstructorInitializationException
     * @throws VariableParsingErrorException
     */
    public function build(): Kernel
    {

        $this->routerInit();

        return $this;

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Run all initializer handlers
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @throws ConstructorNotInitializedException
     * @throws NotOpenConfigException
     */
    public function run(): void
    {

        Router::processAllRoutes();

        (new ControlResponseCode(ObjectInitializer::$response))->trackResponseStatus();

        $this->toolbarInit(ObjectInitializer::$frameworkConfiguration);

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Initializing all created routes
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return void
     * @throws ConfigNotFoundException
     * @throws ConfigPathNotExistException
     * @throws ConstructorNotInitializedException
     * @throws EnvironmentVariableNotFoundException
     * @throws IncorrectControllerException
     * @throws IncorrectPathToEnviException
     * @throws InvalidControllerMethodException
     * @throws ParsingErrorException
     * @throws SingleConstructorInitializationException
     * @throws VariableParsingErrorException
     */
    private function routerInit(): void
    {

        $this->environmentInit();

        Router::__constructStatic(ObjectInitializer::$request);
        Router::initializingRoutesFromConfig();

        $this->profilerInit();

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Initializing the profiler
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return void
     */
    private function profilerInit(): void
    {

        Profiler::init();
        Profiler::xhprofStart();

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the full url to the profiler
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return string
     */
    private function getProfilerUrl(): string
    {

        $url = ObjectInitializer::$request->url;
        $profilerUtils = new ProfilerUtils();

        return sprintf('%s%s.%s', $url->getScheme(), $profilerUtils->profilerSubdomain(), $url->getHost());

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Initializing environment changes
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return void
     * @throws EnvironmentVariableNotFoundException
     * @throws ParsingErrorException
     * @throws VariableParsingErrorException
     * @throws ConfigPathNotExistException
     */
    private function environmentInit(): void
    {

        Environment::__constructStatic(ObjectInitializer::$filesystem);

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Initializing and adding information to the Toolbar
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @param FrameworkConfiguration $frameworkConfiguration
     *
     * @return void
     */
    private function toolbarInit(FrameworkConfiguration $frameworkConfiguration): void
    {

        $frameworkConfiguration = $frameworkConfiguration->getConfig();

        if (isDev() && $frameworkConfiguration->get('toolbar.enabled') || $frameworkConfiguration->get('toolbar.enabledInProduction')) {
            $this->addInfoToToolbar(sprintf('Link to profiler page <a href="%s">Profiler</a>', $this->getProfilerUrl()));

            (new Toolbar())->connectToolbar();
        }

    }

    /**
     * @param string $message
     *
     * @return void
     */
    private function addInfoToToolbar(string $message): void
    {

        $_SERVER['CDM_INFO'][] = $message;

    }

}