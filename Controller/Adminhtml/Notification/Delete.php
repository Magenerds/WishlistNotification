<?php
/**
 * Magenerds\WishlistNotification\Controller\Adminhtml\Notification\Delete
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
 * @subpackage Controller
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Controller\Adminhtml\Notification;

use Magenerds\WishlistNotification\Model\NotificationFactory;

/**
 * Class Delete
 * @package Magenerds\WishlistNotification\Controller\Adminhtml\Notification
 */
class Delete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magenerds\WishlistNotification\Model\NotificationFactory
     */
    protected $_notificationFactory;

    /**
     * @var \Magenerds\WishlistNotification\Logger\Logger
     */
    protected $_logger;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        NotificationFactory $notificationFactory,
        \Magenerds\WishlistNotification\Logger\Logger $logger
    ) {
        parent::__construct($context);
        $this->_notificationFactory = $notificationFactory;
        $this->_logger = $logger;
    }

    /**
     * Delete notification action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $notificationId = (int)$this->getRequest()->getParam('id');
        if ($notificationId) {
            try {
                /** @var $notification \Magenerds\WishlistNotification\Model\Notification */
                $notification = $this->_notificationFactory->create()->load($notificationId);
                $notification->delete();
                $this->messageManager->addSuccess(__('You deleted the notification.'));
            } catch (\Exception $e) {
                $this->_logger->critical($e);
                $this->messageManager->addError(__('Something went wrong while trying to delete the notification.'));
                return $resultRedirect->setPath('notification/*/', ['_current' => true]);
            }
        }
        return $resultRedirect->setPath('notification/*/');
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenerds_WishlistNotification::delete');
    }
}