<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Service\BuilderIO;

use DeployEcommerce\BuilderIO\Helper\Settings;
use DeployEcommerce\BuilderIO\Service\BuilderIO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class Page
 *
 * This class provides methods to fetch and process CMS page content from the Builder.io API.
 * It uses the BuilderIO service to send HTTP requests and the PageContentFactory to create PageContent objects.
 *
 */
class Page
{
    public const CMS_PAGES_FIELDS = [
        'id',
        'name',
        'published',
        'data.url',
        'data.title',
        'data.Description',
        'data.Keywords'
    ];

    protected const API_PAGE_ENDPOINT = 'https://cdn.builder.io/api/v1/qwik/';

    /**
     * Page constructor.
     *
     * @param Client $client
     * @param Settings $settings
     * @param BuilderIO $builderIO
     * @param Json $serializer
     */
    public function __construct(
        private Client       $client,
        private Settings     $settings,
        private BuilderIO    $builderIO,
        private Json         $serializer
    ) {
    }

    /**
     * Fetch content from the Builder.io API for a given URL.
     *
     * @param string $url The URL to fetch content for.
     * @return mixed The content fetched from the API or an empty string on failure.
     * @throws GuzzleException If an error occurs during the request.
     */
    public function fetchContentApi($url, $model = 'page'): mixed
    {
        $content = '';
        $apiResult = $this->builderIO->getRequest(
            self::API_PAGE_ENDPOINT . $model,
            [
            'url' => $url,
            'limit' => 1,
            'bypass' => time(), // Added to bypass any cache on the API
            ]
        );

        if ($apiResult && str_contains($apiResult, 'html')) {
            return $apiResult;
        }
        return $content;
    }
}
