<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
use Magento\InventoryApi\Api\StockRepositoryInterface;
use Magento\TestFramework\Helper\Bootstrap;

/** @var StockRepositoryInterface $stockRepository */
$stockRepository = Bootstrap::getObjectManager()->get(StockRepositoryInterface::class);
foreach ([1, 2, 3, 4] as $stockId) {
    $stockRepository->deleteById($stockId);
}
