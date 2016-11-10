<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Inventory/CatalogMapper.php
 */

class Brontosoftware_Magento_Inventory_CatalogMapper extends Brontosoftware_Magento_Product_CatalogMapperAbstract
{
    private static $_codes = array(
        'is_in_stock' => 'Stock Availability',
        'qty' => 'Inventory Quantity',
        'min_qty' => 'Inventory Minimum Quantity',
        'min_sale_qty' => 'Inventory Minimum Sale in Cart',
        'max_sale_qty' => 'Inventory Maximum Sale in Cart',
        'backorders' => 'Backorders'
    );

    private static $_defaultCodes = array(
        'quantity' => 'qty',
        'availability' => 'is_in_stock',
    );

    private static $_typeCodes = array(
        'is_in_stock' => 'boolean',
        'qty' => 'double',
        'min_qty' => 'double',
        'min_sale_qty' => 'double',
        'max_sale_qty' => 'double',
        'backorders' => 'integer',
    );

    protected $_extension;
    protected $_products;

    /**
     * @param Brontosoftware_Magento_Product_ExtensionAbstract $extension
     * @param Brontosoftware_Magento_Core_Catalog_ProductCacheInterface $products
     * @param Brontosoftware_Magento_Product_CatalogMapperManagerInterface $manager
     */
    public function __construct(
        Brontosoftware_Magento_Product_ExtensionAbstract $extension,
        Brontosoftware_Magento_Core_Catalog_ProductCacheInterface $products,
        Brontosoftware_Magento_Product_CatalogMapperManagerInterface $manager
    ) {
        parent::__construct($manager);
        $this->_products = $products;
        $this->_extension = $extension;
    }

    /**
     * @see parent
     */
    public function getExtraFields()
    {
        return self::$_codes;
    }

    /**
     * @see parent
     */
    public function getDefaultFields()
    {
        return self::$_defaultCodes;
    }

    /**
     * @see parent
     */
    public function getFieldAttributes()
    {
        return self::$_typeCodes;
    }

    /**
     * Push inventory changes on checkout
     *
     * @param mixed $observer
     * @return void
     */
    public function checkoutSubmitAllAfter($observer)
    {
        $quote = $observer->getQuote();
        $storeId = $quote->getStoreId();
        $this->_pushItems($quote->getAllItems(), $storeId);
    }

    /**
     * Revert an inventory changes upon cancelation
     *
     * @param mixed $observer
     * @return void
     */
    public function orderItemCancel($observer)
    {
        $item = $observer->getEvent()->getItem();
        $this->_pushItems(array($item), $item->getStoreId());
    }

    /**
     * Adjust inventory based on credit memos
     *
     * @param mixed $observer
     * @return void
     */
    public function creditMemoSaveAfter($observer)
    {
        $products = array();
        $creditmemo = $observer->getEvent()->getCreditmemo();
        foreach ($creditmemo->getAllItems() as $item) {
            if ($this->_pushItem($item, $products, $creditmemo->getStoreId())) {
                $products[$item->getProductId()] = true;
            }
        }
    }

    /**
     * Internal push item
     *
     * @param mixed $item
     * @param array $products
     * @param mixed $storeId
     * @return boolean
     */
    protected function _pushItem($item, $products, $storeId)
    {
        if (array_key_exists($item->getProductId(), $products)) {
            return false;
        }
        $product = $this->_products->getById($item->getProductId(), $storeId);
        $event = new Brontosoftware_Magento_Core_DataObject(array('product' => $product));
        $this->_extension->pushChangesToAll($event);
        return true;
    }

    /**
     * Push an array of products and children
     *
     * @param array $items
     * @param mixed $storeId
     * @return void
     */
    protected function _pushItems($items, $storeId)
    {
        $products = array();
        foreach ($items as $item) {
            $children = $item->getChildrenItems();
            if ($children) {
                foreach ($children as $child) {
                    if ($this->_pushItem($child, $products, $storeId)) {
                        $products[$child->getProductId()] = true;
                    }
                }
            } else {
                if ($this->_pushItem($item, $products, $storeId)) {
                    $products[$item->getProductId()] = true;
                }
            }
        }
    }
}