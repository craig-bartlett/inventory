<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\InventoryApi\Api;

/**
 * @api
 */
interface SourceRepositoryInterface
{
    /**
     * Save Source data.
     *
     * @param \Magento\InventoryApi\Api\Data\SourceInterface $source
     * @return int
     *
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Magento\InventoryApi\Api\Data\SourceInterface $source);

    /**
     * Load Source data by given sourceId.
     *
     * @param int $sourceId
     * @return \Magento\InventoryApi\Api\Data\SourceInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($sourceId);

    /**
     * Load Source data collection by given search criteria
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\InventoryApi\Api\Data\SourceSearchResultsInterface
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    );
}
