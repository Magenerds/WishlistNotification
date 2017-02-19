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
namespace Magenerds\WishlistNotification\Model\Notification;

/**
 * Class Action
 * @package Magenerds\WishlistNotification\Model\Notification
 */
class Action extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Magenerds\WishlistNotification\Model\ResourceModel\Notification');
    }

    /**
     * Retrieve resource instance wrapper
     *
     * @return \Magenerds\WishlistNotification\Model\ResourceModel\Notification
     */
    protected function _getResource()
    {
        return parent::_getResource();
    }

    /**
     * Update attribute values for entity list per store
     *
     * @param array $notificationIds
     * @param array $attrData
     * @return $this
     */
    public function updateAttributes($notificationIds, $attrData)
    {
        $this->_getResource()->updateAttributes($notificationIds, $attrData);

        return $this;
    }
}