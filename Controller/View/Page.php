<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Controller\View;

use DeployEcommerce\BuilderIO\Api\ContentPageRepositoryInterface;
use DeployEcommerce\BuilderIO\Helper\Settings;
use DeployEcommerce\BuilderIO\Registry\CurrentPageContent;
use DeployEcommerce\BuilderIO\Service\BuilderIO\Page as PageService;
use Magento\Cms\Controller\Page\View;
use Magento\Cms\Helper\Page as PageHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Cms
 *
 * This class is responsible for rendering a preview of a CMS page in the Builder.io integration.
 *
 */
class Page extends View implements HttpGetActionInterface
{
    /**
     * @param Context $context
     * @param RequestInterface $request
     * @param PageHelper $pageHelper
     * @param ForwardFactory $resultForwardFactory
     * @param Settings $settings
     * @param PageFactory $pageFactory
     * @param PageService $pageService
     * @param CurrentPageContent $currentCmsContent
     * @param ContentPageRepositoryInterface $contentPageRepository
     */
    public function __construct(
        Context                                $context,
        RequestInterface                       $request,
        PageHelper                             $pageHelper,
        ForwardFactory                         $resultForwardFactory,
        private Settings                       $settings,
        private PageFactory                    $pageFactory,
        private PageService                    $pageService,
        private CurrentPageContent             $currentCmsContent,
        private ContentPageRepositoryInterface $contentPageRepository,
    ) {
        parent::__construct($context, $request, $pageHelper, $resultForwardFactory);
    }

    /**
     * Execute action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            try {
                $page = $this->contentPageRepository->getById($id);
            } catch (NoSuchEntityException $e) {
                $resultForward = $this->resultForwardFactory->create();
                return $resultForward->forward('noroute');
            }

            if ($page->getHtml()) {
                $this->currentCmsContent->set($page);
                return $this->pageFactory->create();
            }
        }

        $resultForward = $this->resultForwardFactory->create();
        return $resultForward->forward('noroute');
    }
}
