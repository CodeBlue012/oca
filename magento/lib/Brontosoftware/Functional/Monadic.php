<?php
/**
 * This file was generated by the ConvertToLegacy class in bronto-legacy.
 * The purpose of the conversion was to maintain PSR-0 compliance while
 * the main development focuses on modern styles found in PSR-4.
 *
 * For the original:
 * @see src/Bronto/Functional/Monadic.php
 */

/**
 * Simple monadic interface that provides filtering,
 * transform, and iterative capabilities
 *
 * @author Philip Cali <philip.cali@bronto.com>
 */
interface Brontosoftware_Functional_Monadic
{
    /**
     * If the contained type is wrapping something, invoke the function
     *
     * @param callable $function
     * @return Monadic
     */
    public function each($function);

    /**
     * Run a filter function on the contained type, to produce another
     * contained Monadic type
     *
     * @param callable $function
     * @return Monadic
     */
    public function filter($function);

    /**
     * Run a transform on the contained type, to produce another
     * contained Monadic type
     *
     * @param callable $function
     * @return Monadic
     */
    public function map($function);
}
