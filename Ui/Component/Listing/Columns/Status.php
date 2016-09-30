<?php
/**
 * Magenerds\WishlistNotification\Ui\Component\Listing\Columns\Status
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
 * @subpackage Ui
 * @copyright  Copyright (c) 2016 TechDivision GmbH (http://www.techdivision.com)
 * @version    ${release.version}
 * @link       http://www.techdivision.com/
 * @author     Florian Sydekum <f.sydekum@techdivision.com>
 */
namespace Magenerds\WishlistNotification\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * Class Status
 * @package Magenerds\WishlistNotification\Ui\Component\Listing\Columns
 */
class Status extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                if (!array_key_exists('status', $item)) {
                    continue;
                }
                if ($item['status'] == 0) {
                    $item['status'] = __('Unchecked');
                    continue;
                }
                if ($item['status'] == 1) {
                    $item['status'] = __('Checked');
                    continue;
                }
            }
        }

        return $dataSource;
    }
}