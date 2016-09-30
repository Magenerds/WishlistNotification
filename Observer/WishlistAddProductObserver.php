<?php
/**
 * Magenerds\WishlistNotification\Observer\WishlistAddProductObserver
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
 * @subpackage Observer
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Class WishlistAddProductObserver
 * @package Magenerds\WishlistNotification\Observer
 */
class WishlistAddProductObserver implements ObserverInterface
{
    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @var \Magenerds\WishlistNotification\Model\NotificationFactory
     */
    protected $_notificationFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magenerds\WishlistNotification\Model\NotificationFactory $notificationFactory
     * @param \Psr\Log\LoggerInterface
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Catalog\Helper\Image $imageHelper,
        \Magento\Customer\Model\CustomerFactory $customerFactory,
        \Magenerds\WishlistNotification\Model\NotificationFactory $notificationFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_imageHelper = $imageHelper;
        $this->_customerFactory = $customerFactory;
        $this->_notificationFactory = $notificationFactory;
        $this->_logger = $logger;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Observer method which gets called if a product is added to the wishlist. Creates a new
     * notification model.
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // check if module is enabled
        if (!$this->_scopeConfig->getValue('wishlist_notification/general/enabled')) {
            return;
        }

        try {
            // init empty data array
            $data = array();

            // get customer data
            $customerId = $observer->getData('wishlist')->getCustomerId();
            /** @var $customer \Magento\Customer\Model\Customer */
            $customer = $this->_customerFactory->create()->load($customerId);

            $data['customer_mail'] = $customer->getEmail();
            $data['customer_name'] = $customer->getName();
            $data['customer_id'] = $customerId;

            // get product data
            /** @var $product \Magento\Catalog\Model\Product */
            $product = $observer->getData('product');
            $data['sku'] = $product->getSku();

            $price = $product->getPrice();
            $specialPrice = $product->getSpecialPrice();
            $data['price'] = ($price > $specialPrice) ? $price : $product->getFinalPrice();
            $data['special_price'] = $specialPrice;

            $data['image_url'] = $this->_imageHelper->init($product, 'product_thumbnail_image')->getUrl();
            $data['product_id'] = $product->getId();
            $data['product_name'] = $product->getName();

            // get wishlist item data
            /** @var $item \Magento\Wishlist\Model\Item */
            $item = $observer->getData('item');
            $data['added_at'] = $item->getAddedAt();

            // set notification status
            $data['status'] = 0;

            // create notification model
            /** @var $notification \Magenerds\WishlistNotification\Model\Notification */
            $notificationModel = $this->_notificationFactory->create();
            $notificationModel->setData($data)->save();
        } catch (\Exception $e) {
            // catch any exception here in order to avoid error message in frontend
            // just log exceptions
            $this->_logger->critical($e);
        }
    }
}