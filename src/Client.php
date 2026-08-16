<?php

namespace Inlomax\Inlomax;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Inlomax\Inlomax\Exceptions\InlomaxException;

class Client
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var GuzzleClient
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * Client constructor.
     *
     * @param string $apiKey The Inlomax API Key.
     * @param bool $isSandbox Set to true to use the sandbox environment.
     */
    public function __construct(string $apiKey, bool $isSandbox = false)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $isSandbox ? 'https://inlomax.com/sandbox/' : 'https://inlomax.com/api/';

        $this->httpClient = new GuzzleClient([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Authorization' => 'Token ' . $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            // We want to handle non-200 responses manually to parse JSON errors
            'http_errors' => false,
        ]);
    }

    /**
     * Get the wallet balance.
     *
     * @return array
     * @throws InlomaxException
     */
    public function getBalance(): array
    {
        return $this->request('GET', 'balance');
    }

    /**
     * Get the available services.
     *
     * @return array
     * @throws InlomaxException
     */
    public function getServices(): array
    {
        return $this->request('GET', 'services');
    }

    /**
     * Buy Airtime.
     *
     * @param array $payload Must contain serviceID, amount, mobileNumber, request-id
     * @return array
     * @throws InlomaxException
     */
    public function buyAirtime(array $payload): array
    {
        return $this->request('POST', 'airtime', $payload);
    }

    /**
     * Buy Data.
     *
     * @param array $payload Must contain serviceID, mobileNumber, request-id
     * @return array
     * @throws InlomaxException
     */
    public function buyData(array $payload): array
    {
        return $this->request('POST', 'data', $payload);
    }

    /**
     * Pay for Utility / Cable.
     *
     * @param array $payload
     * @return array
     * @throws InlomaxException
     */
    public function buyCable(array $payload): array
    {
        return $this->request('POST', 'cable', $payload);
    }

    /**
     * Pay for Electricity.
     *
     * @param array $payload
     * @return array
     * @throws InlomaxException
     */
    public function buyElectricity(array $payload): array
    {
        return $this->request('POST', 'electricity', $payload);
    }

    /**
     * Buy Education Pins.
     *
     * @param array $payload
     * @return array
     * @throws InlomaxException
     */
    public function buyEducationPins(array $payload): array
    {
        return $this->request('POST', 'edu', $payload);
    }

    /**
     * Verify Bank Account.
     *
     * @param array $payload
     * @return array
     * @throws InlomaxException
     */
    public function verifyBankAccount(array $payload): array
    {
        return $this->request('POST', 'verifybankacct', $payload);
    }

    /**
     * Verify BVN.
     *
     * @param array $payload
     * @return array
     * @throws InlomaxException
     */
    public function verifyBvn(array $payload): array
    {
        return $this->request('POST', 'kyc/bvn', $payload);
    }

    /**
     * Verify NIN.
     *
     * @param array $payload
     * @return array
     * @throws InlomaxException
     */
    public function verifyNin(array $payload): array
    {
        return $this->request('POST', 'kyc/nin', $payload);
    }

    /**
     * Verify IUC Number (Cable).
     *
     * @param array $payload
     * @return array
     * @throws InlomaxException
     */
    public function verifyIuc(array $payload): array
    {
        return $this->request('POST', 'verifyiuc', $payload);
    }

    /**
     * Verify Meter Number (Electricity).
     *
     * @param array $payload
     * @return array
     * @throws InlomaxException
     */
    public function verifyMeter(array $payload): array
    {
        return $this->request('POST', 'verifymeter', $payload);
    }

    /**
     * Get Transaction Details.
     *
     * @param string $reference
     * @return array
     * @throws InlomaxException
     */
    public function getTransaction(string $reference): array
    {
        // Assuming it's a GET request with reference or similar
        // Adjust endpoint based on specific implementation if it takes query params
        return $this->request('GET', 'transaction', ['reference' => $reference]);
    }

    /**
     * Send an HTTP request to the Inlomax API.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     * @throws InlomaxException
     */
    protected function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = [];
            if (!empty($data)) {
                if (strtoupper($method) === 'GET') {
                    $options['query'] = $data;
                } else {
                    $options['json'] = $data;
                }
            }

            $response = $this->httpClient->request($method, $endpoint, $options);
            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new InlomaxException("Failed to decode JSON response: " . $body);
            }

            if (isset($decoded['status']) && $decoded['status'] === 'failed') {
                $message = $decoded['message'] ?? 'Unknown API error';
                throw new InlomaxException($message, $response->getStatusCode());
            }

            return $decoded;

        } catch (GuzzleException $e) {
            throw new InlomaxException("Network error: " . $e->getMessage(), $e->getCode(), $e);
        }
    }
}
