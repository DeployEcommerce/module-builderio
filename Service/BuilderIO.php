<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Service;

use DeployEcommerce\BuilderIO\System\Config;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Psr\Log\LoggerInterface;

/**
 * Class BuilderIO
 *
 * This class provides methods to interact with the Builder.io API.
 * It uses GuzzleHttp\Client to send HTTP requests and fetch content for specific pages.
 *
 */
class BuilderIO
{
    /**
     * BuilderIO constructor.
     *
     * @param Client $client
     * @param Config $config
     * @param LoggerInterface $logger
     */
    public function __construct(
        private Client          $client,
        private Config          $config,
        private LoggerInterface $logger
    ) {
    }

    /**
     * Get the content for a specific page.
     *
     * @param string $endpoint The API endpoint to request.
     * @param array $params Additional parameters for the request.
     * @param null|int|string $store_id
     * @return StreamInterface|null The response body content or null on failure.
     * @throws GuzzleException If an error occurs during the request.
     */
    public function getRequest(string $endpoint, array $params = [], $store_id = null): ?string
    {
        $preParams = [
            'apiKey' => $this->config->getPublicKey($store_id),
            'cacheSeconds' => 60
        ];

        $params = array_merge($preParams, $params);

        try {
            $response = $this->client->request('GET', $endpoint, ['query' => $params]);
            if ($response->getStatusCode() == 200) {
                return (string) $response->getBody()->getContents();
            }

        } catch (Exception $e) {
            $this->logger->error('BuilderIO GET Request failed', [
                'endpoint' => $endpoint,
                'params' => $params,
                'store_id' => $store_id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'exception_class' => get_class($e)
            ]);
        }

        return null;
    }

    /**
     * Post a request to the Builder.io API.
     *
     * @param string $endpoint The API endpoint to request.
     * @param string $body The request body.
     * @param null|int|string $store_id
     * @return StreamInterface|null The response body content or null on failure.
     * @throws GuzzleException If an error occurs during the request.
     */
    public function postRequest(string $endpoint, string $body, $store_id = null): ?Response
    {
        try {
            $response = $this->client->request(
                'POST',
                $endpoint,
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->config->getPrivateKey($store_id),
                        'Content-Type' => 'application/json'
                    ],
                    'body' => $body
                ]
            );

            if ($response->getStatusCode() == 200) {
                return $response;
            }

        } catch (Exception $e) {
            $this->logger->error('BuilderIO POST Request failed', [
                'endpoint' => $endpoint,
                'body' => $body,
                'store_id' => $store_id,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'exception_class' => get_class($e)
            ]);
            return null;
        }

        return null;
    }
}
