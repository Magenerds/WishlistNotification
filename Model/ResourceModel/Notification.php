<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
namespace Magenerds\WishlistNotification\Model\ResourceModel;

/**
 * Class Notification
 * @package Magenerds\WishlistNotification\Model\ResourceModel
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