<?php
declare(strict_types=1);



namespace DeployEcommerce\BuilderIO\Model\Indexer\ProductCollection;

use DeployEcommerce\BuilderIO\Model\ResourceModel\ProductCollection\CollectionFactory as ProductCollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Indexer\ActionInterface as IndexerActionInterface;
use Magento\Framework\Mview\ActionInterface as MviewActionInterface;

class ProductCollectionIndexer implements IndexerActionInterface, MviewActionInterface
{
    /**
     * @var ProductIndexer
     */
    private $productIndexer;

    /**
     * @var ProductCollectionFactory
     */

    /**
     * @param ProductIndexer $productIndexer
     * @param ProductCollectionFactory $collectionFactory
     */
    public function __construct(
        ProductIndexer $productIndexer,
        ProductCollectionFactory $collectionFactory
    ) {
        $this->productIndexer = $productIndexer;
    }

    /**
     * Execute indexing for given IDs.
     *
     * @param int[] $ids
     * @return void
     * @throws LocalizedException
     */
    public function execute($ids): void
    {
        $this->productIndexer->reindexRows($ids);
    }

    /**
     * Execute full indexing.
     *
     * @return void
     * @throws LocalizedException
     */
    public function executeFull(): void
    {
        $this->productIndexer->reindexAll();
    }

    /**
     * Execute indexing for list of IDs.
     *
     * @param int[] $ids
     * @return void
     * @throws LocalizedException
     */
    public function executeList(array $ids): void
    {
        $this->productIndexer->reindexRows($ids);
    }

    /**
     * Execute indexing for single row.
     *
     * @param int $id
     * @return void
     * @throws LocalizedException
     */
    public function executeRow($id): void
    {
        $this->productIndexer->reindexRow($id);
    }
}
