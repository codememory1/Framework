<?php

namespace Kernel;

use Codememory\Components\Caching\Exceptions\ConfigPathNotExistException;
use Codememory\Components\Configuration\Config;
use Codememory\Components\Configuration\Exceptions\ConfigNotFoundException;
use Codememory\Components\Configuration\Interfaces\ConfigInterface;
use Codememory\Components\Environment\Exceptions\EnvironmentVariableNotFoundException;
use Codememory\Components\Environment\Exceptions\ParsingErrorException;
use Codememory\Components\Environment\Exceptions\VariableParsingErrorException;
use Codememory\FileSystem\Interfaces\FileInterface;

/**
 * Class FrameworkConfiguration
 *
 * @package Kernel
 *
 * @author  Codememory
 */
class FrameworkConfiguration
{

    private const CONFIG_NAME = 'framework';

    /**
     * @var ConfigInterface
     */
    private ConfigInterface $config;

    /**
     * @param FileInterface $filesystem
     * @param array         $defaultConfig
     *
     * @throws ConfigPathNotExistException
     * @throws ConfigNotFoundException
     * @throws EnvironmentVariableNotFoundException
     * @throws ParsingErrorException
     * @throws VariableParsingErrorException
     */
    public function __construct(FileInterface $filesystem, array $defaultConfig = [])
    {

        $this->config = (new Config($filesystem))->open(self::CONFIG_NAME, $defaultConfig);

    }

    /**
     * =>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>=>
     * Returns the open configuration of the framework
     * <=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=<=
     *
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface
    {

        return $this->config;

    }

}