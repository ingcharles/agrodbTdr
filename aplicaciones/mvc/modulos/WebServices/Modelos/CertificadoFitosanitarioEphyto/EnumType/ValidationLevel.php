<?php

namespace EnumType;

use \WsdlToPhp\PackageBase\AbstractStructEnumBase;

/**
 * This class stands for validationLevel EnumType
 * @subpackage Enumerations
 */
class ValidationLevel extends AbstractStructEnumBase
{
    /**
     * Constant for value 'SEVERE'
     * @return string 'SEVERE'
     */
    const VALUE_SEVERE = 'SEVERE';
    /**
     * Constant for value 'WARNING'
     * @return string 'WARNING'
     */
    const VALUE_WARNING = 'WARNING';
    /**
     * Constant for value 'INFO'
     * @return string 'INFO'
     */
    const VALUE_INFO = 'INFO';
    /**
     * Return allowed values
     * @uses self::VALUE_SEVERE
     * @uses self::VALUE_WARNING
     * @uses self::VALUE_INFO
     * @return string[]
     */
    public static function getValidValues()
    {
        return array(
            self::VALUE_SEVERE,
            self::VALUE_WARNING,
            self::VALUE_INFO,
        );
    }
}
