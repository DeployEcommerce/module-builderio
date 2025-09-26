<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Api\Data;

/**
 * Interface WebhookInterface
 *
 * This interface defines the methods for accessing and manipulating webhook data.
 * It includes getters and setters for various properties such as entity ID, operation, model name, owner ID, and more.
 *
 */
interface ContentPageInterface
{
    public const CACHE_TAG = 'builderio_content_page_model';

    public const ID = 'id';
    public const BUILDERIO_PAGE_ID = 'builderio_page_id';
    public const MODEL_NAME = 'model_name';
    public const URL = 'url';
    public const TITLE = 'title';
    public const META_DESCRIPTION = 'meta_description';
    public const META_KEYWORDS = 'meta_keywords';
    public const HTML = 'html';
    public const STORE_IDS = 'store_ids';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /**
     * Get the ID.
     *
     * @return int|null
     */
    public function getId(): ?int;

    /**
     * Get the Builder.io page ID.
     *
     * @return string|null
     */
    public function getBuilderioPageId(): ?string;

    /**
     * Get the model name.
     *
     * @return string|null
     */
    public function getModelName(): ?string;

    /**
     * Get the URL.
     *
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * Get the title.
     *
     * @return string|null
     */
    public function getTitle(): ?string;

    /**
     * Get the meta description.
     *
     * @return string|null
     */
    public function getMetaDescription(): ?string;

    /**
     * Get the meta keywords.
     *
     * @return string|null
     */
    public function getMetaKeywords(): ?string;

    /**
     * Get the HTML.
     *
     * @return string|null
     */
    public function getHtml(): ?string;

    /**
     * Get the store IDs.
     *
     * @return string|null
     */
    public function getStoreIds(): ?string;

    /**
     * Get the status.
     *
     * @return string|null
     */
    public function getStatus(): ?string;

    /**
     * Get the created at timestamp.
     *
     * @return string|null
     */
    public function getCreatedAt(): ?string;

    /**
     * Get the updated at timestamp.
     *
     * @return string|null
     */
    public function getUpdatedAt(): ?string;

    /**
     * Set the ID.
     *
     * @param int $id
     * @return ContentPageInterface
     */
    public function setId(int $id): ContentPageInterface;

    /**
     * Set the Builder.io page ID.
     *
     * @param string $builderioPageId
     * @return ContentPageInterface
     */
    public function setBuilderioPageId(string $builderioPageId): ContentPageInterface;

    /**
     * Set the model name.
     *
     * @param string $modelName
     * @return ContentPageInterface
     */
    public function setModelName(string $modelName): ContentPageInterface;

    /**
     * Set the URL.
     *
     * @param string $url
     * @return ContentPageInterface
     */
    public function setUrl(string $url): ContentPageInterface;

    /**
     * Set the title.
     *
     * @param string $title
     * @return ContentPageInterface
     */
    public function setTitle(string $title): ContentPageInterface;

    /**
     * Set the meta description.
     *
     * @param string $metaDescription
     * @return ContentPageInterface
     */
    public function setMetaDescription(string $metaDescription): ContentPageInterface;

    /**
     * Set the meta keywords.
     *
     * @param string $metaKeywords
     * @return ContentPageInterface
     */
    public function setMetaKeywords(string $metaKeywords): ContentPageInterface;

    /**
     * Set the HTML.
     *
     * @param string $html
     * @return ContentPageInterface
     */
    public function setHtml(string $html): ContentPageInterface;

    /**
     * Set the store IDs.
     *
     * @param string $storeIds
     * @return ContentPageInterface
     */
    public function setStoreIds(string $storeIds): ContentPageInterface;

    /**
     * Set the status.
     *
     * @param string $status
     * @return ContentPageInterface
     */
    public function setStatus(string $status): ContentPageInterface;

    /**
     * Set the created at timestamp.
     *
     * @param string $createdAt
     * @return ContentPageInterface
     */
    public function setCreatedAt(string $createdAt): ContentPageInterface;

    /**
     * Set the updated at timestamp.
     *
     * @param string $updatedAt
     * @return ContentPageInterface
     */
    public function setUpdatedAt(string $updatedAt): ContentPageInterface;
}
