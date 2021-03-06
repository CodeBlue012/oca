<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Transfer/Headers.php
 */

/**
 * Enum for common HTTP headers
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface Brontosoftware_Transfer_Headers
{
    const ACCEPT = 'Accept';
    const APPLICATION_JSON = 'application/json';
    const APPLICATION_FORM_URLENCODED = 'application/x-www-form-urlencoded';
    const AUTHORIZATION = 'Authorization';
    const CONTENT_TYPE = 'Content-Type';
}
