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
interface WebhookInterface
{
    /**
     * String constants for property names
     */
    public const WEBHOOK_ID = "webhook_id";
    public const OPERATION = "operation";
    public const MODEL_NAME = "model_name";
    public const OWNER_ID = "owner_id";
    public const CREATED_DATE = "created_date";
    public const BUILDERIO_ID = "builderio_id";
    public const VERSION = "version";
    public const NAME = "name";
    public const MODEL_ID = "model_id";
    public const PUBLISHED = "published";
    public const META = "meta";
    public const PRIORTIY = "priority";
    public const QUERY = "query";
    public const WEBHOOK_DATA = "webhook_data";
    public const METRICS = "metrics";
    public const VARIATIONS = "variations";
    public const LAST_UPDATED = "last_updated";
    public const FIRST_PUBLISHED = "first_published";
    public const PREVIEW_URL = "preview_url";
    public const TEST_RATIO = "test_ratio";
    public const SCREENSHOT = "screenshot";
    public const CREATED_BY = "created_by";
    public const LAST_UPDATED_BY = "last_updated_by";
    public const FOLDERS = "folders";

    /**
     * Get the Webhook ID
     *
     * @return int|null
     */
    public function getWebhookId(): ?int;

    /**
     * Get the operation.
     *
     * @return string|null
     */
    public function getOperation(): ?string;

    /**
     * Set the operation.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setOperation(string $value): WebhookInterface;

    /**
     * Get the model name.
     *
     * @return string|null
     */
    public function getModelName(): ?string;

    /**
     * Set the model name.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setModelName(string $value): WebhookInterface;

    /**
     * Get the owner ID.
     *
     * @return string|null
     */
    public function getOwnerId(): ?string;

    /**
     * Set the owner ID.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setOwnerId(string $value): WebhookInterface;

    /**
     * Get the created date.
     *
     * @return string|null
     */
    public function getCreatedDate(): ?string;

    /**
     * Set the created date.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setCreatedDate(string $value): WebhookInterface;

    /**
     * Get the Builder.io ID.
     *
     * @return string|null
     */
    public function getBuilderioId(): ?string;

    /**
     * Set the Builder.io ID.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setBuilderioId(string $value): WebhookInterface;

    /**
     * Get the version.
     *
     * @return string|null
     */
    public function getVersion(): ?string;

    /**
     * Set the version.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setVersion(string $value): WebhookInterface;

    /**
     * Get the model ID.
     *
     * @return string|null
     */
    public function getModelId(): ?string;

    /**
     * Set the model ID.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setModelId(string $value): WebhookInterface;

    /**
     * Get the published status.
     *
     * @return string|null
     */
    public function getPublished(): ?string;

    /**
     * Set the published status.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setPublished(string $value): WebhookInterface;

    /**
     * Get the name.
     *
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * Set the name.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setName(string $value): WebhookInterface;

    /**
     * Get the meta.
     *
     * @return string|null
     */
    public function getMeta(): ?string;

    /**
     * Set the meta.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setMeta(string $value): WebhookInterface;

    /**
     * Get the priority.
     *
     * @return string|null
     */
    public function getPriority(): ?string;

    /**
     * Set the priority.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setPriority(string $value): WebhookInterface;

    /**
     * Get the query.
     *
     * @return string|null
     */
    public function getQuery(): ?string;

    /**
     * Set the query.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setQuery(string $value): WebhookInterface;

    /**
     * Get the data.
     *
     * @return string|null
     */
    public function getWebhookData(): ?string;

    /**
     * Set the data.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setWebhookData(string $value): WebhookInterface;

    /**
     * Get the metrics.
     *
     * @return string|null
     */
    public function getMetrics(): ?string;

    /**
     * Set the metrics.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setMetrics(string $value): WebhookInterface;

    /**
     * Get the variations.
     *
     * @return string|null
     */
    public function getVariations(): ?string;

    /**
     * Set the variations.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setVariations(string $value): WebhookInterface;

    /**
     * Get the last updated date.
     *
     * @return string|null
     */
    public function getLastUpdated(): ?string;

    /**
     * Set the last updated date.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setLastUpdated(string $value): WebhookInterface;

    /**
     * Get the first published date.
     *
     * @return string|null
     */
    public function getFirstPublished(): ?string;

    /**
     * Set the first published date.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setFirstPublished(string $value): WebhookInterface;

    /**
     * Get the preview URL.
     *
     * @return string|null
     */
    public function getPreviewUrl(): ?string;

    /**
     * Set the preview URL.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setPreviewUrl(string $value): WebhookInterface;

    /**
     * Get the test ratio.
     *
     * @return string|null
     */
    public function getTestRatio(): ?string;

    /**
     * Set the test ratio.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setTestRatio(string $value): WebhookInterface;

    /**
     * Get the screenshot.
     *
     * @return string|null
     */
    public function getScreenshot(): ?string;

    /**
     * Set the screenshot.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setScreenshot(string $value): WebhookInterface;

    /**
     * Get the created by.
     *
     * @return string|null
     */
    public function getCreatedBy(): ?string;

    /**
     * Set the created by.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setCreatedBy(string $value): WebhookInterface;

    /**
     * Get the last updated by.
     *
     * @return string|null
     */
    public function getLastUpdatedBy(): ?string;

    /**
     * Set the last updated by.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setLastUpdatedBy(string $value): WebhookInterface;

    /**
     * Get the folders.
     *
     * @return string|null
     */
    public function getFolders(): ?string;

    /**
     * Set the folders.
     *
     * @param string $value
     * @return WebhookInterface
     */
    public function setFolders(string $value): WebhookInterface;
}
