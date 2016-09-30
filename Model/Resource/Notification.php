<?php
/**
 * Magenerds\WishlistNotification\Model\Resource\Notification
 *
 * Copyright (c) 2016 TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see http://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */

/**
 * @category   Magenerds
 * @package    Magenerds_WishlistNotification
 * @subpackage Model
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Model\Resource;

/**
 * Class Notification
 * @package Magenerds\WishlistNotification\Model\Resource
 */
class Notification extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magenerds_wishlistnotification_notification', 'id');
    }

    /**
     * Update attribute values for notifications
     *
     * @param array $entityIds
     * @param array $attrData
     * @return $this
     * @throws \Exception
     */
    public function updateAttributes($entityIds, $attrData)
    {
        $connection = $this->_getConnection('default');
        $connection->beginTransaction();
        try {
            foreach ($entityIds as $id) {
                $connection->update(
                    $this->getMainTable(),
                    $attrData,
                    $connection->quoteInto($this->getIdFieldName() . ' = ?', $id)
                );
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            throw $e;
        }

        return $this;
    }
}