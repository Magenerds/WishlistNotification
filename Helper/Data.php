<?php
/**
 * Magenerds\WishlistNotification\Helper\Data
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
 * @subpackage Helper
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Helper;

use Magento\Framework\UrlInterface;
use Magento\Framework\Registry;

/**
 * Class Data
 * @package Magenerds\WishlistNotification\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;

    /**
     * @var \Magento\Catalog\Helper\Image
     */
    protected $_imageHelper;

    /**
     * Initialize dependencies.
     *
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Framework\ObjectManagerInterface
     */
    public function __construct(
        Registry $registry,
        UrlInterface $urlBuilder,
        \Magento\Catalog\Helper\Image $imageHelper
    ) {
        $this->_urlBuilder = $urlBuilder;
        $this->_registry = $registry;
        $this->_imageHelper = $imageHelper;
    }

    /**
     * Returns the customer edit link for the backend.
     *
     * @return string
     */
    public function getCustomerEditLink($customerId)
    {
        return $this->_urlBuilder->getUrl('customer/index/edit', ['id' => $customerId]);
    }

    /**
     * Returns the product edit link for the backend.
     * @return string
     */
    public function getProductEditLink($productId)
    {
        return $this->_urlBuilder->getUrl('catalog/product/edit', ['id' => $productId]);
    }

    /**
     * Registers objects to the registry.
     *
     * @param $key
     * @param $value
     */
    public function register($key, $value)
    {
        $this->_registry->unregister($key);
        $this->_registry->register($key, $value);
    }

    /**
     * Returns the product's image url.
     *
     * @return string
     */
    public function getProductImageSrc($product)
    {
        return $this->_imageHelper->init($product, 'product_listing_thumbnail')
            ->getUrl();
    }
}