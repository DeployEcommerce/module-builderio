<?php
/*
 * @Author:    Dan Lewis (dan.lewis@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

declare(strict_types=1);

namespace DeployEcommerce\BuilderIO\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;

class Settings extends AbstractHelper
{
    public const LOG_PREFIX = "DeployEcommerce - Builder IO";

    /**
     * Settings constructor.
     *
     * @param Context $context
     * @param LoggerInterface $logger
     */
    public function __construct(
        public Context                  $context,
        protected LoggerInterface       $logger
    ) {
        parent::__construct($context);
    }

    /**
     * Log an error message.
     *
     * @param string $message
     * @param $resource
     * @return void
     */
    public function logError(string $message = "", $resource = null): void
    {
        $this->logger->error(
            self::LOG_PREFIX .
            " - ERROR - " .
            $message
        );
    }
}
