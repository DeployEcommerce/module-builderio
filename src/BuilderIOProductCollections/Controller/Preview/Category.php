<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Controller\Preview;

use Magento\Catalog\Controller\Category\View;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class Category
 *
 * This class is responsible for rendering a preview of a category page in the Builder.io integration.
 *
 */
class Category extends View implements HttpGetActionInterface
{
}
