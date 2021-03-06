<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Serialize/Encode.php
 */

/**
 * Interface that defines an encoder
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface Brontosoftware_Serialize_Encode
{
    /**
     * Encodes some value into a string state
     *
     * @param mixed $thing
     * @return string
     */
    public function encode($thing);
}
