<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Controller\Webhooks;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NotFoundException;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 *
 * This class handles the structured data webhook requests for the Builder.io integration.
 * It implements the CsrfAwareActionInterface to manage CSRF validation.
 *
 */
class Data implements CsrfAwareActionInterface
{

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var ResponseInterface
     */
    private ResponseInterface $response;

    /**
     * @param Context $context
     * @param LoggerInterface $logger
     */
    public function __construct(
        private Context $context,
        private LoggerInterface $logger
    ) {
        $this->request = $context->getRequest();
        $this->response = $context->getResponse();
    }

    /**
     * Execute action based on request and return result
     *
     * @return ResultInterface|ResponseInterface
     * @throws NotFoundException
     */
    public function execute()
    {
        if (!$this->request->isPost()) {
            return $this->response;
        }
        // todo implement structured content
        return $this->response;
    }

    /**
     * Validate for CSRF
     *
     * @param RequestInterface $request
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException {
        return null;
    }

    /**
     * Validate for CSRF
     *
     * @param RequestInterface $request
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
