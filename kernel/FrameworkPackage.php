<?php

namespace Kernel;

use Codememory\Components\GlobalConfig\GlobalConfig;
use Codememory\Components\JsonParser\Exceptions\JsonErrorException;
use Codememory\Components\JsonParser\JsonParser;
use Codememory\Screw\HttpRequest;
use Codememory\Screw\Response\Response;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class FrameworkPackage
 *
 * @package Kernel
 *
 * @author  Codememory
 */
class FrameworkPackage
{

    private const VENDOR_PACKAGE = 'codememory/framework';

    /**
     * @return float
     */
    public function current(): float
    {

        return (float) GlobalConfig::get('version');

    }

    /**
     * @return float
     * @throws GuzzleException
     * @throws JsonErrorException
     */
    public function last(): float
    {

        $packageInfo = $this->packageInfo();

        if ([] !== $packageInfo) {
            return (float) $packageInfo['packages'][self::VENDOR_PACKAGE][0]['version_normalized'];
        }

        return $this->current();

    }

    /**
     * @return array
     * @throws JsonErrorException
     * @throws GuzzleException
     */
    public function packageInfo(): array
    {

        $jsonParser = new JsonParser();
        $httpRequest = new HttpRequest();

        $requestUrl = sprintf('https://repo.packagist.org/p2/%s.json', self::VENDOR_PACKAGE);
        $headers = @get_headers($requestUrl);
        $code = substr($headers[0], 9, 3);

        $httpRequest
            ->setUrl($requestUrl)
            ->setMethod('GET')
            ->send();

        $response = new Response($httpRequest);

        if ($code >= 200 && $code < 400) {
            return $jsonParser->setData($response->getBody())->decode();
        }

        return [];

    }

}