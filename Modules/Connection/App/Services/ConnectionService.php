<?php

namespace Modules\Connection\App\Services;

class ConnectionService
{
    public function validationDomain($saveDataFromCall)
    {
        $validateData = $saveDataFromCall;

        $domains = [];

        foreach ($validateData as $key => $url) {
            if (preg_match('/(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/', $url)) {
                return false;
            }

            $domain = parse_url($url, PHP_URL_HOST);
            $domains[] = $domain;
        }

        if (count(array_unique($domains)) > 1) {
            return false;
        }

        $secretKeys = array_map(function ($url) {
            $path = ltrim(str_replace([
                '/register-monitoring',
                '/login-monitoring',
                '/logging',
                '/logging/KEY/type',
                '/logging/KEY/type/time',
                '/type',
                '/time',
                '/type/time',
            ], '', parse_url($url, PHP_URL_PATH)), '/');

            if (preg_match('/^[a-f0-9]{64}/', $path, $matches)) {
                return $matches[0];
            }

            return null;
        }, $validateData);

        $secretKeys = array_filter($secretKeys);

        if (count($secretKeys) != 8) {
            return false;
        }

        $url = $validateData['endpoint'];
        $domain = parse_url($url, PHP_URL_HOST);

        if (!$domain) {
            return false;
        }

        $routes = [
            'endpoint',
            'register',
            'login',
            'get_log',
            'get_log_by_type',
            'get_log_by_time',
            'delete_log',
            'delete_log_by_type',
            'delete_log_by_time',
        ];

        foreach ($routes as $route) {
            preg_match_all('/(?:https?:\/\/)?(?:www\.)?([^\/]+\.[^\/]+)/i', $validateData[$route], $matches);
            $numberOfDomain = count($matches[1]);

            if ($numberOfDomain > 1) {
                return false;
            }
        }

        return $url;
    }
}
