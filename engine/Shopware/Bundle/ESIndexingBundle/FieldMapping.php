<?php
/**
 * Shopware 5
 * Copyright (c) shopware AG
 *
 * According to our dual licensing model, this program can be used either
 * under the terms of the GNU Affero General Public License, version 3,
 * or under a proprietary license.
 *
 * The texts of the GNU Affero General Public License with an additional
 * permission and of our proprietary license can be found at and
 * in the LICENSE file you have received along with this program.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * "Shopware" is a registered trademark of shopware AG.
 * The licensing of the program under the AGPLv3 does not imply a
 * trademark license. Therefore any rights, title and interest in
 * our trademarks remain entirely with us.
 */
namespace Shopware\Bundle\ESIndexingBundle;

use Shopware\Bundle\StoreFrontBundle\Struct\Shop;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

/**
 * Class FieldMapping
 * @package Shopware\Bundle\ESIndexingBundle
 */
class FieldMapping implements FieldMappingInterface
{
    /**
     * @var ShopAnalyzerInterface
     */
    private $shopAnalyzer;

    /**
     * @param ShopAnalyzerInterface $shopAnalyzer
     */
    public function __construct(ShopAnalyzerInterface $shopAnalyzer)
    {
        $this->shopAnalyzer = $shopAnalyzer;
    }

    /**
     * @param ShopContextInterface $context
     * @return string
     */
    public function getPriceField(ShopContextInterface $context)
    {
        $key = $context->getCurrentCustomerGroup()->getKey();
        $currency = $context->getCurrency()->getId();
        return 'calculatedPrices.' . $key . '_' . $currency;
    }

    /**
     * @param Shop $shop
     * @return array
     */
    public function getLanguageField(Shop $shop)
    {
        $analyzers = $this->shopAnalyzer->get($shop);

        $fields = [];
        foreach ($analyzers as $analyzer) {
            $key = $analyzer . '_analyzer';
            $fields[$key] = ['type' => 'string', 'analyzer' => $analyzer];
        }

        return ['type' => 'string', 'fields' => $fields];
    }
}
