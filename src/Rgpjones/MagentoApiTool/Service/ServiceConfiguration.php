<?php

namespace Rgpjones\MagentoApiTool\Service;

class ServiceConfiguration
{
    private const FIELDS = [
        'addr' => '',
        'user' => '',
        'pass' => '',
        'wsi_compliance' => ''
    ];

    private $address;
    private $username;
    private $password;
    private $wsiCompliance;

    public function __construct(array $data)
    {
        if (count(array_diff_key($data, self::FIELDS))) {
            throw new \RunTimeException('Bad service configuration!');
        }

        $this->address = $data['addr'];
        $this->username = $data['user'];
        $this->password = $data['pass'];
        $this->wsiCompliance = (bool) $data['wsi_compliance'];
    }

    public function getSoapAddress(): string
    {
        return $this->address;
    }

    public function getUser(): string
    {
        return $this->username;
    }

    public function getRealPassword(): string
    {
        return $this->password;
    }

    public function getMaskedPassword(): string
    {
        return '********';
    }

    public function isWsiComplianceEnabled(): bool
    {
        return $this->wsiCompliance;
    }
}