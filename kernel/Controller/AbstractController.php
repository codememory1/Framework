<?php

namespace Kernel\Controller;

use Codememory\Components\Database\Connection\Connection;
use Codememory\Components\Database\Pack\DatabasePack;
use Codememory\Components\Model\AbstractModel;
use Codememory\Components\Model\Exceptions\ModelNotExistException;
use Codememory\Components\Model\Model;
use Codememory\Components\Profiling\Exceptions\BuilderNotCurrentSectionException;
use Codememory\Components\Validator\Manager as ValidatorManager;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use Codememory\Routing\Controller\AbstractController as AbstractCdmController;
use Kernel\ProviderRegistrar;
use ReflectionException;

/**
 * Class AbstractController
 *
 * @package Kernel\Controller
 *
 * @author  Codememory
 */
abstract class AbstractController extends AbstractCdmController
{

    /**
     * @var ServiceProviderInterface
     */
    protected ServiceProviderInterface $serviceProvider;

    /**
     * @var ValidatorManager
     */
    private ValidatorManager $validatorManager;

    /**
     * @var DatabasePack
     */
    private DatabasePack $databasePack;

    /**
     * @var Model
     */
    private Model $model;

    /**
     * @param ServiceProviderInterface $serviceProvider
     *
     * @throws BuilderNotCurrentSectionException
     */
    final public function __construct(ServiceProviderInterface $serviceProvider)
    {

        (new ProviderRegistrar($serviceProvider))->register();

        parent::__construct($serviceProvider);

        $this->serviceProvider = $serviceProvider;
        $this->validatorManager = new ValidatorManager();
        $this->databasePack = new DatabasePack(new Connection());
        $this->model = new Model();

        (new ControllerProfiling($this->serviceProvider))->profile();

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

        return $this->databasePack;

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
        $model = $modelReflector->newInstance($this->serviceProvider);

        return $model;

    }

}