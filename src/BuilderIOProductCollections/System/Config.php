<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\System;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoresConfig;

/**
 * Class Config
 *
 * This class provides configuration settings for the Builder IO module.
 * It retrieves the public and private API keys for the Builder.io integration.
 *
 */
class Config
{
    public const MODULE_ENABLED = "builder_io/general_settings/enable";
    public const BUILDERIO_HEADER_ENABLED = "builder_io/general_settings/builderio_header";
    public const FALLBACK_EDITOR = "builder_io/general_settings/fallback_editor";
    public const INTEGRATION_API_PUBLIC_KEY = "builder_io/api_settings/public_key";
    public const INTEGRATION_API_PRIVATE_KEY = "builder_io/api_settings/private_key";
    public const INTEGRATION_API_WORSKPACE_ID = "builder_io/api_settings/workspace_id";
    public const WEBHOOK_LIFETIME = "builder_io/webhook/lifetime";
    public const WEBHOOK_ASYNC_PROCESSING = "builder_io/webhook/async_enable";
    public const WEBHOOK_SECRET_KEY = "builder_io/webhook/secret_key";
    public const SITEMAP_ENABLE = "builder_io/sitemap/enable";
    public const SITEMAP_CHANGEFREQUENCY = "builder_io/sitemap/changefrequency";
    public const SITEMAP_PRIORITY = "builder_io/sitemap/priority";
    public const ENABLE_CMS_HOMEPAGE = "builder_io/general_settings/enable_cms_home_page";
    public const CMS_HOMEPAGE = "builder_io/general_settings/cms_home_page";

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param StoresConfig $storesConfig
     * @param EncryptorInterface $encryptor
     */
    public function __construct(
        private ScopeConfigInterface $scopeConfig,
        private StoresConfig $storesConfig,
        private EncryptorInterface $encryptor
    ) {
    }

    /**
     * Check if the Builder.io integration is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag(self::MODULE_ENABLED);
    }

    /**
     * Check if the Builder.io header is enabled.
     *
     * @return bool
     */
    public function getBuilderioHeader()
    {
        return $this->scopeConfig->isSetFlag(self::BUILDERIO_HEADER_ENABLED);
    }

    /**
     * Get the fallback editor for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return bool
     */
    public function getFallbackEditor($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(self::FALLBACK_EDITOR, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the public key for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getPublicKey($storeId = null)
    {
        return $this->scopeConfig->getValue(self::INTEGRATION_API_PUBLIC_KEY, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the private key for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getPrivateKey($storeId = null)
    {
        $private_key = $this->scopeConfig->getValue(
            self::INTEGRATION_API_PRIVATE_KEY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $this->encryptor->decrypt($private_key);
    }

    /**
     * Get the workspace ID for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getWorkspaceId($storeId = null)
    {
        return $this->scopeConfig->getValue(self::INTEGRATION_API_WORSKPACE_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the webhook lifetime for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getWebhookLifetime($storeId = null)
    {
        return $this->scopeConfig->getValue(self::WEBHOOK_LIFETIME, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the webhook async processing for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function isAsyncProcessingEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(self::WEBHOOK_ASYNC_PROCESSING, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the webhook secret key for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getWebhookSecretKey($storeId = null)
    {
        $secret_key =  $this->scopeConfig->getValue(self::WEBHOOK_SECRET_KEY, ScopeInterface::SCOPE_STORE, $storeId);

        return $this->encryptor->decrypt($secret_key);
    }

    /**
     * Get the store URL for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getStoreUrl($storeId = null)
    {
        return $this->scopeConfig->getValue(Store::XML_PATH_SECURE_BASE_URL, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the sitemap enable for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function isSitemapEnabled($storeId = null)
    {
        return $this->scopeConfig->getValue(self::SITEMAP_ENABLE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the sitemap change frequency for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getSitemapChangeFrequency($storeId = null)
    {
        return $this->scopeConfig->getValue(self::SITEMAP_CHANGEFREQUENCY, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the sitemap priority for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getSitemapPriority($storeId = null)
    {
        return $this->scopeConfig->getValue(self::SITEMAP_PRIORITY, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the workspace ID for the Builder.io integration.
     *
     * @param null|int|string $workspaceId
     * @return array
     */
    public function getMappedStoreFromWorkspace($workspaceId): array
    {
        $values = $this->storesConfig->getStoresConfigByPath(self::INTEGRATION_API_WORSKPACE_ID);
        $store_ids = [];

        if ($values) {
            foreach ($values as $storeId => $workspace) {
                if ($workspace == $workspaceId) {
                    $store_ids[] = $storeId;
                }
            }
        }

        return $store_ids;
    }

    /**
     * Get the workspace ID for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getMappedWorkspaceFromStore($storeId = null)
    {
        return $this->scopeConfig->getValue(self::INTEGRATION_API_WORSKPACE_ID, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Check if the Builder.io integration is enabled.
     *
     * @return bool
     */
    public function isCmsHomepageEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(self::ENABLE_CMS_HOMEPAGE, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * Get the CMS homepage for the Builder.io integration.
     *
     * @param null|int|string $storeId
     * @return string
     */
    public function getCmsHomepage($storeId = null)
    {
        return $this->scopeConfig->getValue(self::CMS_HOMEPAGE, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
