<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Service\BuilderIO;

use DeployEcommerce\BuilderIO\Service\BuilderIO;
use DeployEcommerce\BuilderIO\Service\StreamInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Response;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class AdminApi
 *
 * This class provides methods to fetch and process CMS page content from the Builder.io API.
 * It uses the BuilderIO service to send HTTP requests and the PageContentFactory to create PageContent objects.
 *
 */
class AdminApi
{

    protected const API_ADMIN_ENDPOINT = 'https://builder.io/api/v2/admin';

    /**
     * AdminApi constructor.
     *
     * @param Client $client
     * @param BuilderIO $builderIO
     * @param Json $serializer
     */
    public function __construct(
        private Client       $client,
        private BuilderIO    $builderIO,
        private Json         $serializer
    ) {
    }

    /**
     * Get the content for a specific page.
     *
     * @param string $body
     * @param null|int|string $store_id
     * @return StreamInterface|Response
     * @throws GuzzleException
     */
    public function postContent(string $body, $store_id = null)
    {
        return $this->builderIO->postRequest(self::API_ADMIN_ENDPOINT, $body, $store_id);
    }
}
