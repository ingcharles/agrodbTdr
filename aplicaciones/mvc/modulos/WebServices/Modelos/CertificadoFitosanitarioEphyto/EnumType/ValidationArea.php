<?php

namespace EnumType;

use \WsdlToPhp\PackageBase\AbstractStructEnumBase;

/**
 * This class stands for validationArea EnumType
 * @subpackage Enumerations
 */
class ValidationArea extends AbstractStructEnumBase
{
    /**
     * Constant for value 'MandatoryElements'
     * @return string 'MandatoryElements'
     */
    const VALUE_MANDATORY_ELEMENTS = 'MandatoryElements';
    /**
     * Constant for value 'Mapping'
     * @return string 'Mapping'
     */
    const VALUE_MAPPING = 'Mapping';
    /**
     * Constant for value 'Schema'
     * @return string 'Schema'
     */
    const VALUE_SCHEMA = 'Schema';
    /**
     * Return allowed values
     * @uses self::VALUE_MANDATORY_ELEMENTS
     * @uses self::VALUE_MAPPING
     * @uses self::VALUE_SCHEMA
     * @return string[]
     */
    public static function getValidValues()
    {
        return array(
            self::VALUE_MANDATORY_ELEMENTS,
            self::VALUE_MAPPING,
            self::VALUE_SCHEMA,
        );
    }
}
