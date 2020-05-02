<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\TestResponse;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * request function
     *
     * @param $method
     * @param $uri
     * @param array $params
     * @param array $header
     *
     * @return TestResponse
     */
    public function request($method, $uri, $params = [], $header = []): TestResponse
    {
        return $this->json(
            $method,
            $uri,
            $params,
            $header
        );
    }
}
