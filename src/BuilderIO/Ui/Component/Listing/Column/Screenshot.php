<?php
/*
 * @Author:    Nathan Chick (nathan.chick@deploy.co.uk)
 * @Copyright: 2024 Deploy Ecommerce (https://www.deploy.co.uk/)
 * @Package:   DeployEcommerce_BuilderIO
 */

namespace DeployEcommerce\BuilderIO\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Class Screenshot
 *
 * This class is responsible for preparing the data source for the screenshot column
 * in the webhook listing. It extends the Magento\Ui\Component\Listing\Columns\Column class.
 *
 */
class Screenshot extends Column
{
    /**
     * Prepare the data source for the screenshot column
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $item['screenshot' . '_src'] = $item['screenshot'];
                $item['screenshot' . '_link'] = $item['screenshot'];
                $item['screenshot' . '_alt'] = '';
                $item['screenshot' . '_orig_src'] = $item['screenshot'];
            }
        }
        return $dataSource;
    }
}
