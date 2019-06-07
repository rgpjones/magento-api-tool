<?php

namespace Rgpjones\MagentoApiTool\Service;

class ServiceContainer
{
    private const SERVICE_PATH = 'var' . DIRECTORY_SEPARATOR . 'services';

    private $serviceFactory;

    public function __construct(ServiceFactory $serviceFactory)
    {
        $this->serviceFactory = $serviceFactory;
    }

    public function getServicePath()
    {
        return realpath(__DIR__
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . '..'
            . DIRECTORY_SEPARATOR . self::SERVICE_PATH) . DIRECTORY_SEPARATOR;
    }

    public function listServices(): array
    {
        $services = [];

        $dh = dir($this->getServicePath());

        while (false !== ($file = $dh->read())) {
            if (!in_array($file, ['.', '..'])) {
                $services[] = rtrim($file, '.ini');
            }
        }

        sort($services);

        return $services;
    }

    public function getServiceConfiguration($serviceName): ServiceConfiguration
    {
        if (!in_array($serviceName, $this->listServices())) {
            throw new \InvalidArgumentException(sprintf('Service with name "%s" is not defined', $serviceName));
        }

        return $this->serviceFactory->getServiceConfiguration(
            parse_ini_file(sprintf('%s%s.ini', $this->getServicePath(), $serviceName))
        );
    }
}