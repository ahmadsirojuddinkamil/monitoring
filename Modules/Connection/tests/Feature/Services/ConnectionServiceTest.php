<?php

namespace Modules\Connection\tests\Feature\Services;

use Modules\Connection\App\Services\ConnectionService;
use Tests\TestCase;

class ConnectionServiceTest extends TestCase
{
    public function test_validation_domain_success(): void
    {
        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $data = [
            'endpoint' => 'https://endpoint.com/',
            'register' => "https://endpoint.com/register-monitoring/$key",
            'login' => "https://endpoint.com/login-monitoring/$key",
            'get_log' => "https://endpoint.com/logging/$key",
            'get_log_by_type' => "https://endpoint.com/logging/$key/type",
            'get_log_by_time' => "https://endpoint.com/logging/$key/type/time",
            'delete_log' => "https://endpoint.com/logging/$key",
            'delete_log_by_type' => "https://endpoint.com/logging/$key/type",
            'delete_log_by_time' => "https://endpoint.com/logging/$key/type/time",
        ];

        $connectionService = new ConnectionService();
        $result = $connectionService->validationDomain($data);
        $this->assertNotNull($result);
    }

    public function test_validation_domain_failed_because_the_domain_address_is_not_the_same(): void
    {
        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $data = [
            'endpoint' => 'https://endpoint.com/',
            'register' => "https://endpoint.com/register-monitoring/$key",
            'login' => "https://endpoint.com/login-monitoring/$key",
            'get_log' => "https://endpoint.com/logging/$key",
            'get_log_by_type' => "https://client.com/logging/$key/type",
            'get_log_by_time' => "https://client.com/logging/$key/type/time",
            'delete_log' => "https://client.com/logging/$key",
            'delete_log_by_type' => "https://client.com/logging/$key/type",
            'delete_log_by_time' => "https://client.com/logging/$key/type/time",
        ];

        $connectionService = new ConnectionService();
        $result = $connectionService->validationDomain($data);
        $this->assertFalse($result);
    }

    public function test_validation_domain_failed_because_the_endpoint_address_does_not_match(): void
    {
        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $data = [
            'endpoint' => 'https://endpoint.com/',
            'register' => "https://endpoint.com/",
            'login' => "https://endpoint.com/",
            'get_log' => "https://endpoint.com/",
            'get_log_by_type' => "https://endpoint.com/",
            'get_log_by_time' => "https://endpoint.com/",
            'delete_log' => "https://endpoint.com/",
            'delete_log_by_type' => "https://endpoint.com/",
            'delete_log_by_time' => "https://endpoint.com/",
        ];

        $connectionService = new ConnectionService();
        $result = $connectionService->validationDomain($data);
        $this->assertFalse($result);
    }

    public function test_validation_domain_failed_because_endpoint_is_empty(): void
    {
        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $data = [
            'endpoint' => '',
            'register' => "https://endpoint.com/register-monitoring/$key",
            'login' => "https://endpoint.com/login-monitoring/$key",
            'get_log' => "https://endpoint.com/logging/$key",
            'get_log_by_type' => "https://endpoint.com/logging/$key/type",
            'get_log_by_time' => "https://endpoint.com/logging/$key/type/time",
            'delete_log' => "https://endpoint.com/logging/$key",
            'delete_log_by_type' => "https://endpoint.com/logging/$key/type",
            'delete_log_by_time' => "https://endpoint.com/logging/$key/type/time",
        ];

        $connectionService = new ConnectionService();
        $result = $connectionService->validationDomain($data);
        $this->assertFalse($result);
    }

    public function test_validation_domain_failed_because_the_domain_is_more_than_1(): void
    {
        $key = '0b18b3ba1c9a99fd4cd3df8704aa57e63c28585b35d048305640d91a6f5db5a3';

        $data = [
            'endpoint' => 'https://endpoint.com/https://endpoint.com/',
            'register' => "https://endpoint.com/register-monitoring/$key",
            'login' => "https://endpoint.com/login-monitoring/$key",
            'get_log' => "https://endpoint.com/logging/$key",
            'get_log_by_type' => "https://endpoint.com/logging/$key/type",
            'get_log_by_time' => "https://endpoint.com/logging/$key/type/time",
            'delete_log' => "https://endpoint.com/logging/$key",
            'delete_log_by_type' => "https://endpoint.com/logging/$key/type",
            'delete_log_by_time' => "https://endpoint.com/logging/$key/type/time",
        ];

        $connectionService = new ConnectionService();
        $result = $connectionService->validationDomain($data);
        $this->assertFalse($result);
    }
}
