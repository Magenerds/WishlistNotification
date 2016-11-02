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
 * @subpackage Controller
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Controller\Adminhtml\Notification;

use Magento\Backend\App\Action;

/**
 * Class Save
 * @package Magenerds\WishlistNotification\Controller\Adminhtml\Notification
 */
class Save extends \Magento\Backend\App\Action
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
     * @param Action\Context $context
     * @param \Magenerds\WishlistNotification\Model\NotificationFactory $notificationFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magenerds\WishlistNotification\Model\NotificationFactory $notificationFactory,
        \Magenerds\WishlistNotification\Logger\Logger $logger
    ) {
        $this->_notificationFactory = $notificationFactory;
        $this->_logger = $logger;
        parent::__construct($context);
    }

    /**
     * Save notification action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $notificationId = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        try {
            $notification = $this->_notificationFactory->create();
            $notification->load($notificationId);

            if (array_key_exists('notification', $data) && array_key_exists('status', $data['notification'])) {
                $notification->setData('status', $data['notification']['status']);
            }

            $notification->save();

            $this->messageManager->addSuccess(__('You saved the notification.'));
        } catch (\Exception $e) {
            $this->_logger->critical($e);
            $this->messageManager->addError($e->getMessage());
        }

        $resultRedirect->setPath('notification/*/index');

        return $resultRedirect;
    }

    /**
     * Check for is allowed
     *
     * @return boolean
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Magenerds_WishlistNotification::save');
    }
}