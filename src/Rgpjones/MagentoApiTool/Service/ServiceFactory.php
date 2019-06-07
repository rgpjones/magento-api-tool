<?php

namespace Rgpjones\MagentoApiTool\Service;

class ServiceFactory
{
    public function getServiceConfiguration(array $data): ServiceConfiguration
    {
        return new ServiceConfiguration($data);
    }
}