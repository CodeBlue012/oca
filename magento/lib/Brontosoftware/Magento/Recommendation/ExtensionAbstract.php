<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Magento/Recommendation/ExtensionAbstract.php
 */

abstract class Brontosoftware_Magento_Recommendation_ExtensionAbstract implements Brontosoftware_Magento_Connector_Discovery_AdvancedExtensionInterface, Brontosoftware_Magento_Connector_Discovery_GroupInterface, Brontosoftware_Magento_Email_FilterEventInterface
{
    protected $_productRepo;
    protected $_categoryRepo;
    protected $_helper;
    protected $_reports;
    protected $_middleware;
    protected $_contextFactory;
    protected $_appEmulation;
    protected $_logger;

    /**
     * @param Brontosoftware_Magento_Core_Catalog_CategoryCacheInterface $categoryRepo
     * @param Brontosoftware_Magento_Core_Catalog_ProductCacheInterface $productRepo
     * @param Brontosoftware_Magento_Core_Report_ManagerInterface $reports
     * @param Brontosoftware_Magento_Connector_MiddlewareInterface $middleware
     * @param Brontosoftware_Magento_Recommendation_SettingsInterface $helper
     * @param Brontosoftware_Magento_Recommendation_Collect_ContextFactoryInterface $contextFactory
     * @param Brontosoftware_Magento_Core_App_EmulationInterface $appEmulation
     * @param Brontosoftware_Magento_Core_Log_LoggerInterface $logger
     */
    public function __construct(
        Brontosoftware_Magento_Core_Catalog_CategoryCacheInterface $categoryRepo,
        Brontosoftware_Magento_Core_Catalog_ProductCacheInterface $productRepo,
        Brontosoftware_Magento_Core_Report_ManagerInterface $reports,
        Brontosoftware_Magento_Connector_MiddlewareInterface $middleware,
        Brontosoftware_Magento_Recommendation_SettingsInterface $helper,
        Brontosoftware_Magento_Recommendation_Collect_ContextFactoryInterface $contextFactory,
        Brontosoftware_Magento_Core_App_EmulationInterface $appEmulation,
        Brontosoftware_Magento_Core_Log_LoggerInterface $logger
    ) {
        $this->_categoryRepo = $categoryRepo;
        $this->_productRepo = $productRepo;
        $this->_reports = $reports;
        $this->_middleware = $middleware;
        $this->_helper = $helper;
        $this->_contextFactory = $contextFactory;
        $this->_appEmulation = $appEmulation;
        $this->_logger = $logger;
    }

    /**
     * @see parent
     */
    public function getSortOrder()
    {
        return 75;
    }

    /**
     * @see parent
     */
    public function getEndpointId()
    {
        return 'recommendation';
    }

    /**
     * @see parent
     */
    public function getEndpointName()
    {
        return $this->translate('Recommendations');
    }

    /**
     * @see parent
     */
    public function getEndpointIcon()
    {
        return 'mage-icon-recommendations';
    }

    /**
     * @see parent
     */
    public function gatherEndpoints($observer)
    {
        $observer->getDiscovery()->addGroupHelper($this);
    }

    /**
     * @see parent
     */
    public function endpointInfo($observer)
    {
        $observer->getEndpoint()->addExtension(array(
            'id' => 'settings',
            'name' => $this->translate('Brontosoftware_Magento_Recommendation_Settings'),
            'fields' => array(
                array(
                    'id' => 'enabled',
                    'name' => $this->translate('Enabled'),
                    'type' => 'boolean',
                    'required' => true,
                    'typeProperties' => array( 'default' => false )
                ),
                array(
                    'id' => 'autoRefresh',
                    'name' => $this->translate('Refresh Report Indexes'),
                    'type' => 'boolean',
                    'typeProperties' => array( 'default' => false )
                ),
                array(
                    'id' => 'interval',
                    'name' => $this->translate('Refresh Index Every X Days'),
                    'type' => 'integer',
                    'required' => true,
                    'depends' => array(
                        array( 'id' => 'autoRefresh', 'values' => array( true ) )
                    ),
                    'typeProperties' => array(
                        'default' => 1,
                        'min' => 1
                    )
                ),
                array(
                    'id' => 'description',
                    'name' => $this->translate('Product Description'),
                    'type' => 'select',
                    'required' => true,
                    'typeProperties' => array(
                        'default' => 'description',
                        'options' => array(
                            array(
                                'id' => 'short_description',
                                'name' => $this->translate('Short Description'),
                            ),
                            array(
                                'id' => 'description',
                                'name' => $this->translate('Description'),
                            )
                        )
                    )
                ),
                array(
                    'id' => 'image_type',
                    'name' => $this->translate('Image View Type'),
                    'type' => 'select',
                    'required' => true,
                    'typeProperties' => array(
                        'default' => 'image',
                        'options' => array(
                            array(
                                'id' => 'image',
                                'name' => $this->translate('Base Image'),
                            ),
                            array(
                                'id' => 'small_image',
                                'name' => $this->translate('Small Image'),
                            ),
                            array(
                                'id' => 'thumbnail',
                                'name' => $this->translate('Thumbnail')
                            )
                        )
                    )
                ),
                array(
                    'id' => 'displaySymbol',
                    'name' => $this->translate('Display Currency Symbol'),
                    'type' => 'boolean',
                    'required' => true,
                    'typeProperties' => array( 'default' => false )
                ),
                array(
                    'id' => 'truncated',
                    'name' => $this->translate('Truncated Description'),
                    'type' => 'boolean',
                    'required' => true,
                    'typeProperties' => array( 'default' => false )
                ),
                array(
                    'id' => 'descLength',
                    'name' => $this->translate('Description Length'),
                    'type' => 'integer',
                    'required' => true,
                    'depends' => array( array( 'id' => 'truncated', 'values' => array(true) ) ),
                    'typeProperties' => array( 'default' => 100, 'min' => 10, 'max' => 1024 )
                )
            )
        ));

        $observer->getEndpoint()->addSource($this->_products());
        $observer->getEndpoint()->addSource(array(
            'id' => 'product_category',
            'name' => 'Catalog Category',
            'filters' => array(
                array(
                    'id' => 'name',
                    'name' => $this->translate('Name'),
                    'type' => 'text'
                )
            ),
            'fields' => array(
                array(
                    'id' => 'id',
                    'name' => $this->translate('ID'),
                    'width' => '2'
                ),
                array(
                    'id' => 'name',
                    'name' => $this->translate('Name'),
                    'width' => '4'
                ),
                array(
                    'id' => 'root',
                    'name' => $this->translate('Root'),
                    'width' => '2'
                ),
                array(
                    'id' => 'products',
                    'name' => $this->translate('Products'),
                    'width' => '2',
                ),
                array(
                    'id' => 'active',
                    'name' => $this->translate('Active'),
                    'width' => '2'
                )
            )
        ));

        $sources = $this->_sources();
        $objectFields = array(
            array(
                'id' => 'name',
                'name' => $this->translate('Name'),
                'type' => 'text',
                'required' => true,
            ),
            array(
                'id' => 'limit',
                'name' => $this->translate('Product Limit'),
                'type' => 'integer',
                'required' => true,
                'typeProperties' => array(
                    'min' => 1,
                    'default' => 4
                )
            )
        );
        foreach ($this->_helper->getSources() as $source => $sourceLabel) {
            $objectFields[] = array(
                'id' => $source,
                'name' => $this->translate("{$sourceLabel} Source"),
                'type' => 'select',
                'required' => $source == 'fallback',
                'typeProperties' => array(
                    'options' => $this->_filterSources($sources, $source)
                )
            );
            $objectFields[] = array(
                'id' => $source . 'Threshold',
                'name' => $this->translate('In the Last X Days'),
                'type' => 'integer',
                'required' => true,
                'typeProperties' => array( 'default' => 30, 'min' => 7, 'max' => 90 ),
                'depends' => array(
                    array( 'id' => $source, 'values' => array( 'markednew', 'bestsellers', 'viewed' ) )
                )
            );
            $objectFields[] = array(
                'id' => $source . 'Products',
                'name' => $this->translate("{$sourceLabel} Custom Products"),
                'type' => 'source',
                'required' => true,
                'typeProperties' => array(
                    'multiple' => true,
                    'source' => 'product'
                ),
                'depends' => array(
                    array( 'id' => $source, 'values' => array( 'manualproducts' ) )
                )
            );
            $objectFields[] = array(
                'id' => $source . 'Categories',
                'name' => $this->translate("${sourceLabel} Custom Categories"),
                'type' => 'source',
                'required' => true,
                'typeProperties' => array(
                    'multiple' => true,
                    'source' => 'product_category'
                ),
                'depends' => array(
                    array( 'id' => $source, 'values' => array( 'manualcategory' ) )
                )
            );
        }

        $objectFields[] = array(
            'id' => 'excludeProducts',
            'name' => $this->translate('Excluded Products'),
            'type' => 'source',
            'typeProperties' => array(
                'source' => 'product',
                'multiple' => true
            )
        );
        $objectFields[] = array(
            'id' => 'excludeCategories',
            'name' => $this->translate('Excluded Categories'),
            'type' => 'source',
            'typeProperties' => array(
                'source' => 'product_category',
                'multiple' => true
            )
        );

        $observer->getEndpoint()->addObject(array(
            'id' => 'promotion',
            'name' => $this->translate('Recommendation'),
            'shortName' => $this->translate('Recommendation'),
            'identifiable' => true,
            'fields' => $objectFields
        ));
    }

    /**
     * @see parent
     */
    public function advancedAdditional($observer)
    {
        $observer->getEndpoint()->addSource($this->_products());
        $observer->getEndpoint()->addOptionToScript('event', 'jobName', array(
            'id' => 'triggerReportIndex',
            'name' => $this->translate('Index Recommendation Reports')
        ));

        $observer->getEndpoint()->addOptionToScript('event', 'moduleSettings', array(
            'id' => $this->getEndpointId(),
            'name' => $this->getEndpointName()
        ));

        $observer->getEndpoint()->addOptionToScript('event', 'jobName', array(
            'id' => 'previewRecommendation',
            'name' => $this->translate('Preview Recommendation')
        ));

        $observer->getEndpoint()->addFieldToScript('event', array(
            'id' => 'recommendationId',
            'name' => $this->translate('Recommendation'),
            'type' => 'select',
            'typeProperties' => array(
                'objectType' => array(
                    'extension' => 'recommendation',
                    'id' => 'promotion'
                )
            ),
            'depends' => array(
                array( 'id' => 'jobName', 'values' => array( 'previewRecommendation' ) )
            )
        ));

        $observer->getEndpoint()->addFieldToScript('event', array(
            'id' => 'shoppingCart',
            'name' => $this->translate('Based on Products'),
            'type' => 'source',
            'typeProperties' => array(
                'source' => 'product',
                'multiple' => true
            ),
            'depends' => array(
                array( 'id' => 'jobName', 'values' => array( 'previewRecommendation' ) )
            )
        ));
    }

    /**
     * @param mixed $observer
     */
    public function messageExtras($observer)
    {
        $fields = $observer->getContainer()->getFields();
        $options = $observer->getContainer()->getOptions();
        $fields[] = array(
            'id' => 'recommendationId',
            'name' => $this->translate('Recommendation'),
            'type' => 'object',
            'advanced' => isset($options['advanced']),
            'typeProperties' => array(
                'objectType' => array(
                    'extension' => 'recommendation',
                    'id' => 'promotion'
                )
            )
        );
        $observer->getContainer()->setFields($fields);
    }

    /**
     * Adds email template filter to add couponCode tag
     *
     * @param mixed $observer
     * @return void
     */
    public function eventFilter($observer)
    {
        $observer->getFilter()->addEventFilter($this);
    }

    /**
     * @see parent
     */
    public function apply($message, $templateVars = array(), $forceContext)
    {
        $fields = array();
        if (array_key_exists('recommendationId', $message)) {
            $promotion = $this->_helper->getPromotion($message['recommendationId']);
            if (!empty($promotion)) {
                $context = null;
                foreach ($templateVars as $key => $object) {
                    switch ($key) {
                    case 'invoice':
                    case 'shipment':
                    case 'order':
                    case 'quote':
                    case 'wishlist':
                        $context = $this->_contextFactory->get($key, $object);
                        break 2;
                    }
                }
                if ($forceContext && $context) {
                    $fields = array(
                        $context->getContextType() => $context->getContextId()
                    );
                } else {
                    $fields = $this->_helper->getFields($promotion, $context);
                }
            }
        }
        return $fields;
    }

    /**
     * Determines if a job should be started to refresh reports
     *
     * @param mixed $observer
     */
    public function triggerReportIndex($observer)
    {
        $script = $observer->getScript();
        if ($this->_helper->isAutoRefreshing($script->getRegistration()->getScope(), $script->getRegistration()->getScopeId())) {
            foreach ($this->_helper->getRefreshableKeys($script->getRegistration()) as $reportKey => $lastUpdate) {
                $script->addScheduledTask('triggerReportIndex');
                break;
            }
        }
    }

    /**
     * Refresh the report index for the promotions defined in the registration
     *
     * @param mixed $observer
     */
    public function refreshReportIndex($observer)
    {
        $script = $observer->getScript();
        $results = array('error' => 0);
        foreach ($this->_helper->getRefreshableKeys($script->getRegistration()) as $reportKey => $lastUpdate) {
            if ($this->_reports->refresh($reportKey, $lastUpdate)) {
                $results[$reportKey] = 1;
            } else {
                $results['error']++;
            }
        }
        $script->setProgress($results);
    }

    /**
     * Attempt to test promotion
     *
     * @param mixed $observer
     */
    public function testPromotion($observer)
    {
        $events = array();
        $script = $observer->getScript()->getObject();
        if (array_key_exists('recommendationId', $script['data'])) {
            list($scopeName, $scopeId) = explode('.', $script['data']['scopeId']);
            $storeId = $this->_middleware->defaultStoreId($scopeName, $scopeId);
            $promotion = $this->_helper->getPromotion($script['data']['recommendationId'], $storeId, true);
            if ($promotion) {
                $this->_appEmulation->startEnvironmentEmulation($storeId, 'frontend', true);
                try {
                    $context = array_key_exists('shoppingCart', $script['data']) ?
                        new Brontosoftware_Magento_Recommendation_Collect_Context_Custom($script['data']['shoppingCart'], $storeId) :
                        new Brontosoftware_Magento_Recommendation_Collect_Context_Nothing();
                    $eData = $this->_helper->getFields($promotion, $context);
                    $events[] = array('context' => empty($eData) ? null : $eData);
                } catch (Exception $e) {
                    $this->_logger->critical($e);
                }
                $this->_appEmulation->stopEnvironmentEmulation();
            }
        }
        $observer->getScript()->setResults($events);
    }

    /**
     * Supplies the products for Connector
     *
     * @param mixed $observer
     */
    public function pullProducts($observer)
    {
        $results = array();
        foreach ($this->_productRepo->getBySource($observer->getSource()) as $product) {
            $results[] = array(
                'id' => $product->getId(),
                'name' => $product->getName(),
                'sku' => $product->getSku(),
                'active' => ($product->getStatus() == 1 && $product->getVisibility() != 1) ?
                    $this->translate('Yes') :
                    $this->translate('No')
            );
        }
        $observer->getSource()->setResults($results);
    }

    /**
     * Supplies the categories for Connector
     *
     * @param mixed $observer
     */
    public function pullCategories($observer)
    {
        $results = array();
        foreach ($this->_categoryRepo->getBySource($observer->getSource()) as $category) {
            $results[] = array(
                'id' => $category->getId(),
                'name' => $category->getName(),
                'root' => $category->getLevel() <= 1 ?
                    $this->translate('Yes') :
                    $this->translate('No'),
                'products' => $category->getProductCount(),
                'active' => $category->getIsActive() ?
                    $this->translate('Yes') :
                    $this->translate('No')
            );
        }
        $observer->getSource()->setResults($results);
    }

    /**
     * Gets the recommendation sources for promotions
     *
     * @return array
     */
    protected function _sources()
    {
        return array(
            array('id' => 'related', 'name' => $this->translate('Related Products')),
            array('id' => 'crosssell', 'name' => $this->translate('Cross Sell Products')),
            array('id' => 'upsell', 'name' => $this->translate('Up Sell Products')),
            array('id' => 'markednew', 'name' => $this->translate('New Products')),
            array('id' => 'bestsellers', 'name' => $this->translate('Best Selling')),
            array('id' => 'viewed', 'name' => $this->translate('Most Viewed')),
            array('id' => 'manualproducts', 'name' => $this->translate('Custom Products')),
            array('id' => 'manualcategory', 'name' =>$this->translate('Custom Category'))
        );
    }

    /**
     * Provides a subset depending on the input source
     *
     * @param array $sources
     * @param string $source
     * @return array
     */
    protected function _filterSources($sources, $source)
    {
        if ($source == 'fallback') {
            return array_slice($sources, 3);
        } else {
            return $sources;
        }
    }

    /**
     * Gets the source object for products
     *
     * @return array
     */
    protected function _products()
    {
        return array(
            'id' => 'product',
            'name' => $this->translate('Product'),
            'filters' => array(
                array(
                    'id' => 'sku',
                    'name' => $this->translate('SKU'),
                    'type' => 'text'
                ),
                array(
                    'id' => 'name',
                    'name' => $this->translate('Name'),
                    'type' => 'text'
                )
            ),
            'fields' => array(
                array(
                    'id' => 'id',
                    'name' => $this->translate('ID'),
                    'width' => '2'
                ),
                array(
                    'id' => 'sku',
                    'name' => $this->translate('SKU'),
                    'width' => '4'
                ),
                array(
                    'id' => 'name',
                    'name' => $this->translate('Name'),
                    'width' => '4'
                ),
                array(
                    'id' => 'active',
                    'name' => $this->translate('Visible, In Stock'),
                    'width' => '2'
                )
            )
        );
    }
}
