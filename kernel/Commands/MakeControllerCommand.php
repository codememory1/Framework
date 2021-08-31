<?php

namespace Kernel\Commands;

use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Configuration\Exceptions\ConfigNotFoundException;
use Codememory\Components\Console\Command;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\FileSystem\File;
use Codememory\FileSystem\Interfaces\FileInterface;
use Codememory\Support\Str;
use Kernel\FrameworkConfiguration;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class MakeControllerCommand
 *
 * @package Kernel\Commands\Stubs
 *
 * @author  Codememory
 */
class MakeControllerCommand extends Command
{

    /**
     * @var string|null
     */
    protected ?string $command = 'make:controller';

    /**
     * @var string|null
     */
    protected ?string $description = 'Create a controller with some ready-made piece of code';

    /**
     * @return Command
     */
    protected function wrapArgsAndOptions(): Command
    {

        $this
            ->addArgument('name', InputArgument::REQUIRED, 'Controller name without suffix Controller')
            ->addOption('re-create', null, InputOption::VALUE_NONE, 'Re-create the controller if a controller with the same name already exists');

        return $this;

    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws ConfigNotFoundException
     * @throws EnvironmentVariableNotFoundException
     * @throws ParsingErrorException
     * @throws VariableParsingErrorException
     * @throws ConfigPathNotExistException
     */
    protected function handler(InputInterface $input, OutputInterface $output): int
    {

        $filesystem = new File();
        $frameworkConfig = (new FrameworkConfiguration($filesystem))->getConfig();

        $stubController = file_get_contents($filesystem->getRealPath('kernel/Commands/Stubs/ControllerStub.stub'));
        $controllerNameWithSuffix = $input->getArgument('name') . 'Controller';
        $fullPathWithController = sprintf(
            '%s/%s',
            trim($frameworkConfig->get('pathWithControllers'), '/'),
            trim($controllerNameWithSuffix, '/')
        );

        if (!$input->getOption('re-create') && $filesystem->exist($fullPathWithController . '.php')) {
            $this->io->error([
                sprintf('The %s controller already exists.', $input->getArgument('name') . 'Controller'),
                'To recreate it, use the --re-create option'
            ]);

            return Command::FAILURE;
        }

        return $this->createController($filesystem, $controllerNameWithSuffix, $fullPathWithController, $stubController);

    }

    /**
     * @param FileInterface $filesystem
     * @param string        $controllerNameWithSuffix
     * @param string        $fullPathWithController
     * @param string        $stubController
     *
     * @return int
     */
    private function createController(FileInterface $filesystem, string $controllerNameWithSuffix, string $fullPathWithController, string &$stubController): int
    {

        Str::replace($stubController, '{className}', $controllerNameWithSuffix);

        file_put_contents($filesystem->getRealPath($fullPathWithController . '.php'), $stubController);

        $this->io->success([
            sprintf('Controller %s created successfully', $controllerNameWithSuffix),
            sprintf('path: %s', $fullPathWithController . '.php')
        ]);

        return Command::SUCCESS;

    }

}
