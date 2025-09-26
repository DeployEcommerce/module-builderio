<?php
declare(strict_types=1);
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Ui\Component\Listing\Column;

use DeployEcommerce\BuilderIO\Api\Data\WebhookInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class to build edit and delete link for each item.
 */
class WebhookBlockActions extends Column
{
    /**
     * Entity name.
     */
    private const ENTITY_NAME = 'Webhook';

    /**
     * Url paths.
     */
    private const VIEW_URL_PATH = 'builderio/webhook/view';

    /**
     * WebhookBlockActions constructor.
     *
     * @param ContextInterface   $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface       $urlBuilder
     * @param array              $components
     * @param array              $data
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        private UrlInterface $urlBuilder,
        array              $components = [],
        array              $data = []
    ) {
        parent::__construct(
            $context,
            $uiComponentFactory,
            $components,
            $data
        );
    }

    /**
     * Prepare data source.
     *
     * @param array $dataSource
     *
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item[WebhookInterface::WEBHOOK_ID])) {
                    $urlData = [WebhookInterface::WEBHOOK_ID => $item[WebhookInterface::WEBHOOK_ID]];
                    $viewUrl = $this->urlBuilder->getUrl(static::VIEW_URL_PATH, $urlData);

                    $item[$this->getData('name')] = [
                        'view' => [
                            'href' => $viewUrl,
                            'label' => __('Process'),
                            'post' => true,
                            '__disableTmpl' => true
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
