<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Serialize/Decode.php
 */

/**
 * Interface that defines a decoder
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface Brontosoftware_Serialize_Decode
{
    /**
     * Decodes a string into an associative state
     *
     * @param string $input
     * @return mixed
     */
    public function decode($input);
}
