<?php 
/**
 * PERFICIENT INDIA PVT LTD.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://shop.perficient.com/license-community.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * This package designed for Magento COMMUNITY edition
 * =================================================================
 * Perficient does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Perficient does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * PHP version 5.x
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   GIT:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */



/**
 * Perficient_GoogleTagManager_Model_Googletagmanager
 *
 * @category  Perficient
 * @package   Perficient_GoogleTagManager
 * @author    Perficient <mukesh.soni@perficient.com>
 * @copyright 2016 PERFICIENT INDIA PVT LTD
 * @license   OSL http://shop.perficient.com/license-community.txt
 * @version   Release:0.0.2.1
 * @link      http://shop.perficient.com/extensions/google-tag-manager-by-perficient
 */
class Perficient_GoogleTagManager_Model_Googletagmanager extends Mage_Core_Model_Abstract
{

    const XML_PATH_ECOMMERCE_TRANSACTIONS = 'perficient_googletagmanager/general/ecommerce_transactions';

    /**
     * Add transaction data to the data layer?
     *
     * @return bool
     */
    public function isEcommerceTransactionsEnabled()
    {
            return Mage::getStoreConfig(self::XML_PATH_ECOMMERCE_TRANSACTIONS);
    }

    /**
     * get Transaction data for the data layer?
     *
     * @return arrays
     */
    public function getTransactionData($orderIds)
    {
        if (!empty($orderIds) && $this->isEcommerceTransactionsEnabled()) {
            try {
                $collection = Mage::getResourceModel('sales/order_collection')->addFieldToFilter(
                    'entity_id',
                    array('in' => $orderIds)
                );
                $data = array();

                $i = 0;
                $products = array();

                foreach ($collection as $order) {
                    if ($order->getShippingCarrier()) {
                        $carrierCode = $order->getShippingCarrier()->getCarrierCode();
                    } else {
                        $carrierCode = "No";
                    }
                    if ($i == 0) {
                        // Build all fields for first order.
                        $data = array(
                            'transactionId'             => $order->getIncrementId(),
                            'transactionDate'           => date("Y-m-d"),
                            'transactionTotal'          => round($order->getBaseGrandTotal(), 2),
                            'transactionShipping'       => round($order->getBaseShippingAmount(), 2),
                            'transactionTax'            => round($order->getBaseTaxAmount(), 2),
                            'transactionPaymentType'    => $order->getPayment()->getMethodInstance()->getTitle(),
                            'transactionCurrency'       => $order->getOrderCurrencyCode(),
                            'transactionShippingMethod' => $carrierCode,
                            'transactionPromoCode'      => $order->getCouponCode(),
                            'transactionProducts'       => array()
                        );
                    } else {
                        // For subsequent orders, append to order ID, totals and shipping method.
                        $data['transactionId']             .= '|' . $order->getIncrementId();
                        $data['transactionTotal']          += $order->getBaseGrandTotal();
                        $data['transactionShipping']       += $order->getBaseShippingAmount();
                        $data['transactionTax']            += $order->getBaseTaxAmount();
                        $data['transactionShippingMethod'] .= '|' . $carrierCode;
                    }

                    // Build products array.
                    foreach ($order->getAllItems() as $item) {
                        $product = $item->getProduct();
                        $catPath = '';
                        if (is_object($product)) {
                            $catIds = $product->getCategoryIds();
                            $catPath = Mage::helper('perficient_googletagmanager')->getCategoryPath($catIds);
                        }
                        if (empty($products[$item->getSku()])) {
                            // Build all fields the first time we encounter this item.
                            $products[$item->getSku()] = array(
                                'name'     => Mage::helper('perficient_googletagmanager')->jsQuoteEscape(Mage::helper('core')->escapeHtml($item->getName())),
                                'sku'      => $item->getSku(),
                                'category' => Mage::helper('perficient_googletagmanager')->jsQuoteEscape($catPath),
                                'price'    => (double) number_format($item->getBasePrice(), 2, '.', ''),
                                'quantity' => (int) $item->getQtyOrdered()
                            );
                            if ($item->getParentItemId()) {
                                $products[$item->getSku()]['id'] = $item->getId();
                                $products[$item->getSku()]['parent_id'] = $item->getParentItem()->getSku();
                                $parentOptions = $item->getParentItem()->getProductOptions();
                                if (isset($parentOptions['product_calculations'])
                                    && $parentOptions['product_calculations'] != Mage_Catalog_Model_Product_Type_Abstract::CALCULATE_CHILD
                                ) {
                                    $options = unserialize($item->getProductOptionByCode('bundle_selection_attributes'));
                                    if (isset($options['price'])) {
                                        $products[$item->getSku()]['price'] = (double) number_format($options['price'], 2, '.', '');
                                    }
                                }
                            }
                        } else {
                            // If we already have the item, update quantity.
                            $products[$item->getSku()]['quantity'] += (int) $item->getQtyOrdered();
                        }
                    }

                    $i++;
                }

                // Push products into main data array.
                foreach ($products as $product) {
                    $data['transactionProducts'][] = $product;
                }

                // Trim empty fields from the final output.
                foreach ($data as $key => $value) {
                    if (!is_numeric($value) && empty($value))
                        unset($data[$key]);
                }

                return $data;
            }
            catch(Exception $e)
            {
                Mage::logException($e);
            }
        } else {
            return array();
        }
    }

    /**
     * get Transaction data for the data layer?
     *
     * @return array
     */
    public function getVisitorData()
    {
        $data = array();
        $customer = Mage::getSingleton('customer/session');

        // visitorId
        if ($customer->getCustomerId())
            $data['visitorId'] = (string) $customer->getCustomerId();

        // visitorLoginState
        $data['visitorLoginState'] = ($customer->isLoggedIn()) ? 'Logged in' : 'Logged out';

        // visitorType
        $data['visitorType'] = (string) Mage::getSingleton('customer/group')
            ->load($customer->getCustomerGroupId())->getCode();

        // visitorExistingCustomer / visitorLifetimeValue
        $orders = Mage::getResourceModel('sales/order_collection')
            ->addFieldToSelect('*')->addFieldToFilter('customer_id', $customer->getId());
        $ordersTotal = 0;
        foreach ($orders as $order) {
            $ordersTotal += $order->getGrandTotal();
        }
        $data['visitorLifetimeValue'] = round($ordersTotal, 2);
        $data['visitorExistingCustomer'] = ($ordersTotal > 0) ? 'Yes' : 'No';

        return $data;
    }

}