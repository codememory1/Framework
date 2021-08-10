<?php

namespace Kernel;

use Codememory\Components\Database\Connection\Connection;
use Codememory\Components\Database\Pack\DatabasePack;
use Codememory\Components\Validator\Manager as ValidatorManager;
use Codememory\Components\View\View;
use Codememory\Container\ServiceProvider\Interfaces\InjectionProviderInterface;
use Codememory\Container\ServiceProvider\Interfaces\ServiceProviderInterface;
use Codememory\FileSystem\File;
use Codememory\Routing\Controller\AbstractController as CdmAbstractController;

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
     * @var ValidatorManager
     */
    private ValidatorManager $validatorManager;

    /**
     * @var DatabasePack
     */
    private DatabasePack $databasePack;

    /**
     * AbstractController constructor.
     *
     * @param ServiceProviderInterface $serviceProvider
     */
    public function __construct(ServiceProviderInterface $serviceProvider)
    {

        $this->validatorManager = new ValidatorManager();
        $this->databasePack = new DatabasePack(new Connection());

        $serviceProvider
            ->register('view', View::class, function (InjectionProviderInterface $injectionProvider) {
                $injectionProvider->construct([new File()], true);
            });

        FrameworkBuilder::registerProviders($serviceProvider);

        parent::__construct($serviceProvider);

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

}