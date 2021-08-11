<?php

namespace Kernel;

use Codememory\Components\Database\Connection\Connection;
use Codememory\Components\Database\Pack\DatabasePack;
use Codememory\Components\Model\AbstractModel;
use Codememory\Components\Model\Exceptions\ModelNotExistException;
use Codememory\Components\Model\Interfaces\ModelInterface;
use Codememory\Components\Model\Model;
use Codememory\Components\Validator\Manager as ValidatorManager;
use Codememory\Components\View\View;
use Codememory\Container\ServiceProvider\Interfaces\InjectionProviderInterface;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use Codememory\FileSystem\File;
use Codememory\Routing\Controller\AbstractController as CdmAbstractController;
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

}