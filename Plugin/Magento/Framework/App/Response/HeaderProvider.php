<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Plugin\Magento\Framework\App\Response;

use DeployEcommerce\BuilderIO\System\Config;
use Magento\Framework\App\Response\HeaderProvider\AbstractHeaderProvider;

/**
 * Class HeaderProvider
 *
 * This class is a plugin for the Magento framework header provider.
 * It is used to add custom headers to the response.
 *
 */
class HeaderProvider
{

    /**
     * HeaderProvider constructor.
     *
     * @param Config $config
     */
    public function __construct(
        private Config $config
    ) {
    }

    /**
     * This is largely depreciated and is no longer recommended, favoured is the Content-Security-Policy header
     *
     * @param AbstractHeaderProvider $subject
     * @param string $result
     * @return string
     */
    public function afterGetValue(AbstractHeaderProvider $subject, string $result): string
    {
        if ($this->config->getBuilderioHeader()) {
            if ($subject->getName() === 'X-Frame-Options') {
                return 'ALLOW-FROM https://builder.io';
            }
        }
        return $result;
    }
}
