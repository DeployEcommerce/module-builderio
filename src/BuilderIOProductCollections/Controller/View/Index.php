<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIOProductCollections\Controller\View;

use DeployEcommerce\BuilderIOProductCollections\Api\ContentPageRepositoryInterface;
use DeployEcommerce\BuilderIOProductCollections\Registry\CurrentPageContent;
use DeployEcommerce\BuilderIOProductCollections\Service\BuilderIO\Page as PageService;
use DeployEcommerce\BuilderIOProductCollections\System\Config;
use Magento\Cms\Controller\Page\View;
use Magento\Cms\Helper\Page as PageHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 *
 * This class is a controller for the Builder.io page view.
 * It is used to display the content of a Builder.io page for the homepage.
 */
class Index extends View implements HttpGetActionInterface
{
    /**
     * @param Context $context
     * @param RequestInterface $request
     * @param PageHelper $pageHelper
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $pageFactory
     * @param PageService $pageService
     * @param CurrentPageContent $currentCmsContent
     * @param ContentPageRepositoryInterface $contentPageRepository
     * @param Config $config
     */
    public function __construct(
        Context                                $context,
        RequestInterface                       $request,
        PageHelper                             $pageHelper,
        ForwardFactory                         $resultForwardFactory,
        private PageFactory                    $pageFactory,
        private PageService                    $pageService,
        private CurrentPageContent             $currentCmsContent,
        private ContentPageRepositoryInterface $contentPageRepository,
        private Config                         $config
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
