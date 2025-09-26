<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Registry;

use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterface;
use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterfaceFactory;

/**
 * Class CurrentCmsContent
 *
 * This class manages the current CMS content in the registry.
 * It allows setting and getting the current PageContent, and creates a null content if none is set.
 *
 */
class CurrentPageContent
{
    /**
     * @var ContentPageInterface
     */
    private ContentPageInterface $pageContent;

    /**
     * CurrentPageContent constructor.
     *
     * @param ContentPageInterfaceFactory $pageContentFactory
     */
    public function __construct(
        private ContentPageInterfaceFactory $pageContentFactory
    ) {
    }

    /**
     * Set the current page content
     *
     * @param ContentPageInterface $pageContent
     */
    public function set(ContentPageInterface $pageContent): void
    {
        $this->pageContent = $pageContent;
    }

    /**
     * Get the current page content
     *
     * @return ContentPageInterface
     */
    public function get(): ContentPageInterface
    {
        return $this->pageContent ?? $this->createNullContent();
    }

    /**
     * Create a null content object
     *
     * @return ContentPageInterface
     */
    private function createNullContent(): ContentPageInterface
    {
        return $this->pageContentFactory->create();
    }
}
