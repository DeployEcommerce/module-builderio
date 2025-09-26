<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Plugin\Magento\Store\Model;

use DeployEcommerce\BuilderIO\System\Config;
use Magento\Store\Model\PathConfig as PathConfigMagento;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class PathConfig
 *
 * This class is a plugin for the Magento store model path configuration.
 * It is used to modify the default path configuration based on custom conditions.
 */
class PathConfig
{
    /**
     * PathConfig constructor.
     *
     * @param StoreManagerInterface $storeManager
     * @param Config $config
     */
    public function __construct(
        private StoreManagerInterface $storeManager,
        private Config $config
    ) {
    }

    /**
     * Modify the default path configuration.
     *
     * @param PathConfigMagento $subject
     * @param string $result
     * @return string
     */
    public function afterGetDefaultPath(PathConfigMagento $subject, string $result): string
    {
        $store = $this->storeManager->getStore();
        $value = $this->config->isCmsHomepageEnabled($store->getId());

        if($value) {
            $page_id = $this->config->getCmsHomepage($store->getId());
            if($page_id) {
                return 'builderio/view/index/id/' . $page_id;
            }
        }

        return $result;
    }
}
