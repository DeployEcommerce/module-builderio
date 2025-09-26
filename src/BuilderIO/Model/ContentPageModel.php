<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Model;

use DeployEcommerce\BuilderIO\Model\ResourceModel\ContentPageResource;
use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class ContentPageModel extends AbstractModel implements ContentPageInterface, IdentityInterface
{

    /**
     * @var string
     */
    protected $_eventPrefix = 'builderio_content_page_model';

    /**
     * Initialize magento model.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ContentPageResource::class);
    }

    /**
     * @inheritDoc
     */
    public function getId(): ?int
    {
        return (int) $this->getData(self::ID);
    }

    /**
     * @inheritDoc
     */
    public function getBuilderioPageId(): ?string
    {
        return $this->getData(self::BUILDERIO_PAGE_ID);
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
    public function getUrl(): ?string
    {
        return $this->getData(self::URL);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): ?string
    {
        return $this->getData(self::TITLE);
    }

    /**
     * @inheritDoc
     */
    public function getMetaDescription(): ?string
    {
        return $this->getData(self::META_DESCRIPTION);
    }

    /**
     * @inheritDoc
     */
    public function getMetaKeywords(): ?string
    {
        return $this->getData(self::META_KEYWORDS);
    }

    /**
     * @inheritDoc
     */
    public function getHtml(): ?string
    {
        return $this->getData(self::HTML);
    }

    /**
     * @inheritDoc
     */
    public function getStoreIds(): ?string
    {
        return $this->getData(self::STORE_IDS);
    }

    /**
     * @inheritDoc
     */
    public function getStatus(): ?string
    {
        return $this->getData(self::STATUS);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt(): ?string
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getUpdatedAt(): ?string
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function setId($id): ContentPageInterface
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function setBuilderioPageId(string $builderioPageId): ContentPageInterface
    {
        return $this->setData(self::BUILDERIO_PAGE_ID, $builderioPageId);
    }

    /**
     * @inheritDoc
     */
    public function setModelName(string $modelName): ContentPageInterface
    {
        return $this->setData(self::MODEL_NAME, $modelName);
    }

    /**
     * @inheritDoc
     */
    public function setUrl(string $url): ContentPageInterface
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * @inheritDoc
     */
    public function setTitle(string $title): ContentPageInterface
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @inheritDoc
     */
    public function setMetaDescription(string $metaDescription): ContentPageInterface
    {
        return $this->setData(self::META_DESCRIPTION, $metaDescription);
    }

    /**
     * @inheritDoc
     */
    public function setMetaKeywords(string $metaKeywords): ContentPageInterface
    {
        return $this->setData(self::META_KEYWORDS, $metaKeywords);
    }

    /**
     * @inheritDoc
     */
    public function setHtml(string $html): ContentPageInterface
    {
        return $this->setData(self::HTML, $html);
    }

    /**
     * @inheritDoc
     */
    public function setStoreIds(string $storeIds): ContentPageInterface
    {
        return $this->setData(self::STORE_IDS, $storeIds);
    }

    /**
     * @inheritDoc
     */
    public function setStatus(string $status): ContentPageInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @inheritDoc
     */
    public function setCreatedAt(string $createdAt): ContentPageInterface
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @inheritDoc
     */
    public function setUpdatedAt(string $updatedAt): ContentPageInterface
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @inheritDoc
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}
