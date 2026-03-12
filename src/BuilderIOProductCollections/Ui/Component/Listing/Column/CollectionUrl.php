<?php
declare(strict_types=1);

namespace DeployEcommerce\BuilderIOProductCollections\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

class CollectionUrl extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private UrlInterface $urlBuilder,
        private StoreManagerInterface $storeManager,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare data source for the collection URL column.
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $links = [];

                foreach ($this->storeManager->getStores() as $store) {
                    if (!$store->isActive()) {
                        continue;
                    }

                    $storeUrl = $store->getBaseUrl() . 'rest/' .
                        $store->getCode() . '/V1/builderio/collections/' . $item['id'];

                    $links[] = sprintf(
                        '<a href="%s" target="_blank" title="View in %s" style="margin-right: 10px;">%s</a>',
                        $storeUrl,
                        htmlspecialchars($store->getName()),
                        $storeUrl
                    );
                }

                if (!empty($links)) {
                    $item['collection_url'] = '<div>' . implode('<br/>', $links) . '</div>';
                } else {
                    $item['collection_url'] = '<em>No active stores</em>';
                }
            }
        }

        return $dataSource;
    }
}
