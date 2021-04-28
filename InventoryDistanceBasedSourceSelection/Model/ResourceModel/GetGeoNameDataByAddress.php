<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Magento\InventoryDistanceBasedSourceSelection\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\InventoryDistanceBasedSourceSelection\Model\Convert\AddressToString;
use Magento\InventorySourceSelectionApi\Api\Data\AddressInterface;

/**
 * Get geoname data by postcode
 */
class GetGeoNameDataByAddress
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var AddressToString
     */
    private $addressToString;

    /**
     * GetGeoNameDataByPostcode constructor.
     *
     * @param ResourceConnection $resourceConnection
     * @param AddressToString $addressToString
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        AddressToString $addressToString
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->addressToString = $addressToString;
    }

    /**
     * Return geonames information using a fallback mechanism
     *
     * @param AddressInterface $address
     * @return array
     * @throws NoSuchEntityException
     */
    public function execute(AddressInterface $address): array
    {
        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName('inventory_geoname');

        $qry = $connection->select()->from($tableName)
            ->where('country_code = ?', $address->getCountry())
            ->where('postcode = ?', $this->getFormattedPostcode($address->getPostcode()))
            ->limit(1);

        $row = $connection->fetchRow($qry);
        if (!$row && $address->getCity()) {
            $qry = $connection->select()->from($tableName)
                ->where('country_code = ?', $address->getCountry())
                ->where('city like ?', $address->getCity() . '%')
                ->limit(1);

            $row = $connection->fetchRow($qry);
        }

        if (!$row) {
            $qry = $connection->select()->from($tableName)
                ->where('country_code = ?', $address->getCountry())
                ->where('region = ?', $address->getRegion())
                ->limit(1);

            $row = $connection->fetchRow($qry);
        }

        if (!$row) {
            throw new NoSuchEntityException(
                __('Unknown geoname for %1', $this->addressToString->execute($address))
            );
        }

        return $row;
    }

     /**
     * Format postcode for searching inventory_geoname
     *
     * @param string $postcode
     * @return string
     */
    private function getFormattedPostcode(string $postcode): string
    {
        $pattern = '/^([a-zA-Z]{1,2}[0-9]{1,2})\s?[0-9]{1}[a-zA-Z]{2}$/';
        preg_match( $pattern, $postcode, $match);
        if (count($match) > 1) {
            return trim($match[1]);
        } else {
            return $postcode;
        }
    }
}
