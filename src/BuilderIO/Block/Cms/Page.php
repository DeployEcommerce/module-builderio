<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */
declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Block\Cms;

use DeployEcommerce\BuilderIO\Api\Data\ContentPageInterface;
use DeployEcommerce\BuilderIO\Registry\CurrentPageContent;
use Magento\Cms\Model\Page as MagentoPageModel;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Page\Config;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Page
 *
 * This class is a block for the Magento CMS Page.
 * It is used to render the Builder.io content for a CMS page.
 *
 */
class Page extends AbstractBlock implements IdentityInterface
{

    /**
     * Constructor
     *
     * @param Context $context
     * @param MagentoPageModel $page
     * @param FilterProvider $filterProvider
     * @param StoreManagerInterface $storeManager
     * @param PageFactory $pageFactory
     * @param CurrentPageContent $currentCmsContent
     * @param Config $pageConfig
     * @param array $data
     */
    public function __construct(
        private Context               $context,
        private MagentoPageModel      $page,
        private FilterProvider        $filterProvider,
        private StoreManagerInterface $storeManager,
        private PageFactory           $pageFactory,
        private CurrentPageContent    $currentCmsContent,
        private Config                $pageConfig,
        array                         $data = []
    ) {
        parent::__construct(
            $context,
            $data
        );
    }

    /**
     * Renders the HTML for the CMS page
     *
     * @return mixed
     */
    public function _toHtml(): mixed
    {
        if ($html = $this->getPage()->getHtml()) {
            return $this->filterProvider->getPageFilter()->filter($html);
        }

        return parent::_toHtml();
    }

    /**
     * Fetches the page from the registry
     *
     * @return ContentPageInterface
     */
    public function getPage()
    {
        return $this->currentCmsContent->get();
    }

    /**
     * Adds breadcrumbs to the page
     *
     * @param string $title
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function addBreadcrumbs(string $title)
    {
        if ($this->_scopeConfig->getValue('web/default/show_cms_breadcrumbs', ScopeInterface::SCOPE_STORE)
            && ($breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs'))
        ) {
            $breadcrumbsBlock->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->storeManager->getStore()->getBaseUrl()
                ]
            );
            $breadcrumbsBlock->addCrumb('cms_page', ['label' => $title, 'title' => $title]);
        }
    }

    /**
     * Prepares the layout for the page
     *
     * @return $this|Page
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function _prepareLayout()
    {
        $page = $this->getPage();

        $this->addBreadcrumbs($page->getTitle()??'');
        $this->pageConfig->getTitle()->set($page->getTitle());
        $this->pageConfig->setKeywords($page->getMetaKeywords());
        $this->pageConfig->setDescription($page->getMetaDescription());

        return parent::_prepareLayout();
    }

    /**
     * Get the cache key
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return [ContentPageInterface::CACHE_TAG . '_' . $this->getPage()->getId()];
    }
}
