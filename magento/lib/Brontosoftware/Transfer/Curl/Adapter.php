<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Transfer/Curl/Adapter.php
 */

/**
 * Implemented transfer adapter that handles cURL requests
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
class Brontosoftware_Transfer_Curl_Adapter implements Brontosoftware_Transfer_Adapter
{
    protected $_options;

    /**
     * Set any additional cURL parameters in the option collection
     *
     * @param mixed $options
     */
    public function __construct($options = array())
    {
        if (is_array($options)) {
            $this->_options = new Brontosoftware_DataObject($options);
        } else {
            $this->_options = $options;
        }
    }

    /**
     * @see parent
     */
    public function createRequest($method, $uri)
    {
        return new Brontosoftware_Transfer_Curl_Request($method, $uri, $this->_options);
    }
}
