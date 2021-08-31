<?php

namespace Kernel;

use Codememory\Components\Database\Connection\Connection;
use Codememory\Components\Database\Pack\DatabasePack;
use Codememory\Components\DateTime\Exceptions\InvalidTimezoneException;
use Codememory\Components\Model\AbstractModel;
use Codememory\Components\Model\Exceptions\ModelNotExistException;
use Codememory\Components\Model\Interfaces\ModelInterface;
use Codememory\Components\Model\Model;
use Codememory\Components\Profiling\Exceptions\BuilderNotCurrentSectionException;
use Codememory\Components\Profiling\Interfaces\ResourceInterface;
use Codememory\Components\Profiling\ReportCreators\HomeReportCreator;
use Codememory\Components\Profiling\ReportCreators\PerformanceReportCreator;
use Codememory\Components\Profiling\Resource;
use Codememory\Components\Profiling\Sections\Builders\HomeBuilder;
use Codememory\Components\Profiling\Sections\Builders\PerformanceReportBuilder;
use Codememory\Components\Profiling\Sections\HomeSection;
use Codememory\Components\Profiling\Sections\PerformanceSection;
use Codememory\Components\Validator\Manager as ValidatorManager;
use Codememory\Components\View\View;
use Codememory\Container\ServiceProvider\Interfaces\InjectionProviderInterface;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use Codememory\FileSystem\File;
use Codememory\Routing\Controller\AbstractController as CdmAbstractController;
use Codememory\Routing\Route;
use Codememory\Routing\Router;
use ReflectionException;

/**
 * Class AbstractController
 *
 * @package Kernel
 *
 * @author  Codememory
 */
abstract class AbstractController extends CdmAbstractController
{

    /**
     * @var ServiceProviderInterface
     */
    private ServiceProviderInterface $serviceProvider;

    /**
     * @var ValidatorManager
     */
    private ValidatorManager $validatorManager;

    /**
     * @var DatabasePack
     */
    private DatabasePack $databasePack;

    /**
     * @var ModelInterface
     */
    private ModelInterface $model;

    /**
     * AbstractController constructor.
     *
     * @param ServiceProviderInterface $serviceProvider
     *
     * @throws InvalidTimezoneException
     */
    public function __construct(ServiceProviderInterface $serviceProvider)
    {

        $this->serviceProvider = $serviceProvider;
        $this->validatorManager = new ValidatorManager();
        $this->databasePack = new DatabasePack(new Connection());
        $this->model = new Model();

        $this->serviceProvider
            ->register('view', View::class, function (InjectionProviderInterface $injectionProvider) {
                $injectionProvider->construct([new File()], true);
            });

        FrameworkBuilder::registerProviders($this->serviceProvider);

        parent::__construct($this->serviceProvider);

        $this->initProfiler();

    }

    /**
     * @param string $view
     * @param array  $parameters
     *
     * @return bool
     */
    protected function render(string $view, array $parameters = []): bool
    {

        $this->get('view')->render($view, $parameters)->makeOutput();

        return true;

    }

    /**
     * @return ValidatorManager
     */
    protected function validatorManager(): ValidatorManager
    {

        return $this->validatorManager;

    }

    /**
     * @return DatabasePack
     */
    protected function getDatabase(): DatabasePack
    {

        return clone $this->databasePack;

    }

    /**
     * @param string $name
     *
     * @return AbstractModel
     * @throws ModelNotExistException
     * @throws ReflectionException
     */
    protected function getModel(string $name): AbstractModel
    {

        $modelReflector = $this->model->getModelReflector($name);

        /** @var AbstractModel $model */
        $model = $modelReflector->newInstanceArgs([
            $this->serviceProvider
        ]);

        return $model;

    }

    /**
     * @return void
     * @throws BuilderNotCurrentSectionException
     */
    private function initProfiler(): void
    {

        $resource = new Resource();
        $currentRoute = Router::getCurrentRoute();

        $this->createPageReport($currentRoute, $resource);
        $this->profilingPage($currentRoute, $resource);

    }

    /**
     * @param Route             $route
     * @param ResourceInterface $resource
     *
     * @return void
     * @throws BuilderNotCurrentSectionException
     */
    private function createPageReport(Route $route, ResourceInterface $resource): void
    {

        $homeBuilder = new HomeBuilder();
        $homeReportCreator = new HomeReportCreator($route, new HomeSection($resource));

        [$controller, $method] = explode('#', $route->getResources()->getAction());

        $homeBuilder
            ->setRoutePath($route->getResources()->getPathGenerator()->getPath())
            ->setHttpMethod($this->get('request')->getMethod())
            ->setController($controller)
            ->setControllerMethod($method)
            ->setCreateDate($this->get('date')->format('Y-m-d H:i:s'));

        $homeReportCreator->create($homeBuilder);

    }

    /**
     * @param Route             $route
     * @param ResourceInterface $resource
     *
     * @throws BuilderNotCurrentSectionException
     */
    private function profilingPage(Route $route, ResourceInterface $resource): void
    {

        $performanceBuilder = new PerformanceReportBuilder();
        $performanceReportCreator = new PerformanceReportCreator($route, new PerformanceSection($resource));

        $performanceBuilder
            ->setReport(xhprof_disable());

        $performanceReportCreator->create($performanceBuilder);

    }

}