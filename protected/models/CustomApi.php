<?php
require_once 'vendor/autoload.php';
if (!defined('API_URL')) define('API_URL', 'https://localhost:7173');

use GuzzleHttp\Client;

class CustomApi
{
    public static function signIn($email, $password)
    {
        try {
            $client = new Client(['verify' => false]);
            $response = $client->post(API_URL . '/api/auth/sign-in', [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body' => json_encode([
                    'email' => $email,
                    'password' => $password
                ])
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Throwable $th) {
            return [
                Responses::getError(),
                'data' => $th
            ];
        }
    }
    public static function signUp($personId, $name, $surname, $email, $password, $phone)
    {
        try {
            $client = new Client(['verify' => false]);
            $response = $client->post(API_URL . '/api/auth/sign-up', [
                'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                'body' => json_encode([
                    'personId' => $personId,
                    'name' => $name,
                    'surname' => $surname,
                    'email' => $email,
                    'password' => $password,
                    'phone' => $phone
                ])
            ]);
            return json_decode($response->getBody(), true);
        } catch (\Throwable $th) {
            return [
                Responses::getError(),
                'data' => $th
            ];
        }
    }
}
