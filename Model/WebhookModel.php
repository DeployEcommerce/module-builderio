<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Api\Data\WebhookInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use DeployEcommerce\BuilderIO\Model\ResourceModel\WebhookResource;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Framework\Serialize\Serializer\Json;

/**
 * Class WebhookModel
 *
 * This class represents the model for webhooks in the Builder.io integration.
 * It implements the WebhookInterface and IdentityInterface, providing methods
 * to get and set various properties of the webhook.
 *
 */
class WebhookModel extends AbstractModel implements WebhookInterface, IdentityInterface
{

    public const CACHE_TAG = 'webhook_model';

    /**
     * @var string
     */
    protected $_eventPrefix = 'webhook_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(WebhookResource::class);
    }

    /**
     * WebhookModel constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param Json $json
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context          $context,
        Registry         $registry,
        private Json     $json,
        ?AbstractResource $resource = null,
        ?AbstractDb       $resourceCollection = null,
        array            $data = []
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @inheritDoc
     */
    public function getWebhookId(): ?int
    {
        return (int) $this->getData(self::WEBHOOK_ID);
    }

    /**
     * @inheritDoc
     */
    public function getOperation(): ?string
    {
        return $this->getData(self::OPERATION);
    }

    /**
     * @inheritDoc
     */
    public function setOperation(string $value): WebhookInterface
    {
        return $this->setData(self::OPERATION, $value);
    }

    /**
     * @inheritDoc
     */
    public function getModelName(): ?string
    {
        return $this->getData(self::MODEL_NAME);
    }

    /**
     * @inheritDoc
     */
    public function setModelName(string $value): WebhookInterface
    {
        return $this->setData(self::MODEL_NAME, $value);
    }

    /**
     * @inheritDoc
     */
    public function getOwnerId(): ?string
    {
        return $this->getData(self::OWNER_ID);
    }

    /**
     * @inheritDoc
     */
    public function setOwnerId(string $value): WebhookInterface
    {
        return $this->setData(self::OWNER_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedDate(): ?string
    {
        return $this->getData(self::CREATED_DATE);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedDate(string $value): WebhookInterface
    {
        return $this->setData(self::CREATED_DATE, $value);
    }

    /**
     * @inheritDoc
     */
    public function getBuilderioId(): ?string
    {
        return $this->getData(self::BUILDERIO_ID);
    }

    /**
     * @inheritDoc
     */
    public function setBuilderioId(string $value): WebhookInterface
    {
        return $this->setData(self::BUILDERIO_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): ?string
    {
        return $this->getData(self::VERSION);
    }

    /**
     * @inheritDoc
     */
    public function setVersion(string $value): WebhookInterface
    {
        return $this->setData(self::VERSION, $value);
    }

    /**
     * @inheritDoc
     */
    public function getModelId(): ?string
    {
        return $this->getData(self::MODEL_ID);
    }

    /**
     * @inheritDoc
     */
    public function setModelId(string $value): WebhookInterface
    {
        return $this->setData(self::MODEL_ID, $value);
    }

    /**
     * @inheritDoc
     */
    public function getPublished(): ?string
    {
        return $this->getData(self::PUBLISHED);
    }

    /**
     * @inheritDoc
     */
    public function setPublished(string $value): WebhookInterface
    {
        return $this->setData(self::PUBLISHED, $value);
    }

    /**
     * @inheritDoc
     */
    public function getMeta(): ?string
    {
        return $this->getData(self::META);
    }

    /**
     * @inheritDoc
     */
    public function setMeta(string $value): WebhookInterface
    {
        return $this->setData(self::META, $value);
    }

    /**
     * @inheritDoc
     */
    public function getPriority(): ?string
    {
        return $this->getData(self::PRIORTIY);
    }

    /**
     * @inheritDoc
     */
    public function setPriority(string $value): WebhookInterface
    {
        return $this->setData(self::PRIORTIY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getQuery(): ?string
    {
        return $this->getData(self::QUERY);
    }

    /**
     * @inheritDoc
     */
    public function setQuery(string $value): WebhookInterface
    {
        return $this->setData(self::QUERY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getMetrics(): ?string
    {
        return $this->getData(self::METRICS);
    }

    /**
     * @inheritDoc
     */
    public function setMetrics(string $value): WebhookInterface
    {
        return $this->setData(self::METRICS, $value);
    }

    /**
     * @inheritDoc
     */
    public function getVariations(): ?string
    {
        return $this->getData(self::VARIATIONS);
    }

    /**
     * @inheritDoc
     */
    public function setVariations(string $value): WebhookInterface
    {
        return $this->setData(self::VARIATIONS, $value);
    }

    /**
     * @inheritDoc
     */
    public function getLastUpdated(): ?string
    {
        return $this->getData(self::LAST_UPDATED);
    }

    /**
     * @inheritDoc
     */
    public function setLastUpdated(string $value): WebhookInterface
    {
        return $this->setData(self::LAST_UPDATED, $value);
    }

    /**
     * @inheritDoc
     */
    public function getFirstPublished(): ?string
    {
        return $this->getData(self::FIRST_PUBLISHED);
    }

    /**
     * @inheritDoc
     */
    public function setFirstPublished(string $value): WebhookInterface
    {
        return $this->setData(self::FIRST_PUBLISHED, $value);
    }

    /**
     * @inheritDoc
     */
    public function getPreviewUrl(): ?string
    {
        return $this->getData(self::PREVIEW_URL);
    }

    /**
     * @inheritDoc
     */
    public function setPreviewUrl(string $value): WebhookInterface
    {
        return $this->setData(self::PREVIEW_URL, $value);
    }

    /**
     * @inheritDoc
     */
    public function getTestRatio(): ?string
    {
        return $this->getData(self::TEST_RATIO);
    }

    /**
     * @inheritDoc
     */
    public function setTestRatio(string $value): WebhookInterface
    {
        return $this->setData(self::TEST_RATIO, $value);
    }

    /**
     * @inheritDoc
     */
    public function getScreenshot(): ?string
    {
        return $this->getData(self::SCREENSHOT);
    }

    /**
     * @inheritDoc
     */
    public function setScreenshot(string $value): WebhookInterface
    {
        return $this->setData(self::SCREENSHOT, $value);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedBy(): ?string
    {
        return $this->getData(self::CREATED_BY);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedBy(string $value): WebhookInterface
    {
        return $this->setData(self::CREATED_BY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getLastUpdatedBy(): ?string
    {
        return $this->getData(self::LAST_UPDATED_BY);
    }

    /**
     * @inheritDoc
     */
    public function setLastUpdatedBy(string $value): WebhookInterface
    {
        return $this->setData(self::LAST_UPDATED_BY, $value);
    }

    /**
     * @inheritDoc
     */
    public function getFolders(): ?string
    {
        return $this->getData(self::FOLDERS);
    }

    /**
     * @inheritDoc
     */
    public function setFolders(string $value): WebhookInterface
    {
        return $this->setData(self::FOLDERS, $value);
    }

    /**
     * @inheritDoc
     */
    public function getWebhookData(): ?string
    {
        return $this->getData(self::WEBHOOK_DATA);
    }

    /**
     * @inheritDoc
     */
    public function setWebhookData(string $value): WebhookInterface
    {
        return $this->setData(self::WEBHOOK_DATA, $value);
    }

    /**
     * @inheritDoc
     */
    public function getName(): ?string
    {
        return $this->getData(self::NAME);
    }

    /**
     * @inheritDoc
     */
    public function setName(string $value): WebhookInterface
    {
        return $this->setData(self::NAME, $value);
    }

    /**
     * Finds the URLPath in the query items.
     *
     * @return string|null
     */
    public function getUrlPath(): ?string
    {
        $query_items = $this->json->unserialize($this->getData(self::QUERY), true);

        if (is_array($query_items)) {
            foreach ($query_items as $query_item) {
                if ($query_item['property'] == 'urlPath') {
                    return $query_item['value'];
                }
            }
        }

        return "";
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getWebhookId()];
    }
}
