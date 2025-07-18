<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Controller\Preview;

use Magento\Cms\Controller\Page\View;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

/**
 * Class Cms
 *
 * This class is responsible for rendering a preview of a CMS page in the Builder.io integration.
 *
 */
class Page extends View implements HttpGetActionInterface
{

    /**
     * Preview BuilderIO page action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var  $resultPage */
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
