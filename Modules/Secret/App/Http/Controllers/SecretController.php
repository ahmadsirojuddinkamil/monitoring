<?php

namespace Modules\Secret\App\Http\Controllers;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SecretController extends Controller
{
    public function viewSecretGenerator()
    {
        return view('secret::layouts.generator', [
            'secret' => null,
        ]);
    }

    public function generateKey()
    {
        $randomString = shell_exec('openssl rand -hex 32');
        $hashedUuid = hash('sha256', $randomString);

        $secret = [];

        for ($i = 0; $i < 15; $i++) {
            $randomString = shell_exec('openssl rand -hex 32');
            $hashedUuid = hash('sha256', $randomString);
            $secret[] = $hashedUuid;
        }

        return view('secret::layouts.generator', [
            'secret' => $secret,
        ]);
    }

    public function getDatResponse()
    {
        $responses = [];

        for ($i = 0; $i < 20; $i++) {
            $requestStartTime = microtime(true); // Perbarui waktu mulai permintaan di setiap iterasi

            $ch = curl_init();
            // $url = 'google.com';
            $url = 'jagoanhosting.com';
            // $url = 'fileconvert.my.id';

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_exec($ch);
            $endTime = microtime(true);
            $responseTimeMs = round(($endTime - $requestStartTime) * 1000, 3);
            $responseTimeSec = round($responseTimeMs / 1000, 3);

            $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            $responses[] = [
                'Response Time' => $responseTimeSec,
                'Status Code' => $statusCode,
            ];

            curl_close($ch);
        }

        return $responses;
    }

    public function pageLogging()
    {
        return view('secret::layouts.index');
    }

    public function pageRegister()
    {
        return view('secret::layouts.register');
    }

    public function pageLogin()
    {
        return view('secret::layouts.login');
    }

    public function register(Request $request)
    {
        $client = new Client();

        $response = $client->request('POST', 'http://127.0.0.1:8000/api/register-monitoring/b38d68c2-3ad0-49c2-9fcc-b5664f6913bc', [
            'form_params' => $request->all(),
            'headers' => [
                'X-UUID' => 'cdf763c5-f49b-4439-84e6-87f5e51b0571',
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function login(Request $request)
    {
        $client = new Client();

        $response = $client->post('http://127.0.0.1:8000/api/login-monitoring/e7214a4a-b0c4-433d-a2fc-6731531627f2', [
            'form_params' => $request->all(),
            'headers' => [
                'X-UUID' => 'cdf763c5-f49b-4439-84e6-87f5e51b0571',
                'Accept' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function getAllDataLogging()
    {
        $client = new Client();

        $response = $client->request('POST', 'http://127.0.0.1:8000/api/logging/3b18f2e9-3ca1-4114-b926-e4265a6a4440');

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function getDataLoggingByType(Request $request)
    {
        $client = new Client();

        $response = $client->request('POST', 'http://127.0.0.1:8000/api/logging/f25ca72f-7027-4368-aff3-c7301c5de08a/type', [
            'form_params' => $request->all(),
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function getDataLoggingByTime(Request $request)
    {
        $client = new Client();

        $response = $client->request('POST', 'http://127.0.0.1:8000/api/logging/35739446-737f-4097-b830-f3b5aaf2fa2b/type/time', [
            'form_params' => $request->all(),
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function deleteAllDataLogging()
    {
        $client = new Client();

        $response = $client->request('DELETE', 'http://127.0.0.1:8000/api/logging/04edbe1d-c6c9-42da-88c7-2e01c6a1f3ba');

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function deleteDataLoggingByType(Request $request)
    {
        $client = new Client();

        $response = $client->request('DELETE', 'http://127.0.0.1:8000/api/logging/d0d2938f-613d-4c85-a151-cdf390086189/type', [
            'form_params' => $request->all(),
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }

    public function deleteDataLoggingByTime(Request $request)
    {
        $client = new Client();

        $response = $client->request('DELETE', 'http://127.0.0.1:8000/api/logging/86e06e9b-4f0f-487a-a533-87015a40b83e/type/time', [
            'form_params' => $request->all(),
        ]);

        $data = json_decode($response->getBody(), true);

        return $data;
    }
}
