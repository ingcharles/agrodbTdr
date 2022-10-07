<?php

namespace EnumType;

use \WsdlToPhp\PackageBase\AbstractStructEnumBase;

/**
 * This class stands for unitMeasureType EnumType
 * @subpackage Enumerations
 */
class UnitMeasureType extends AbstractStructEnumBase
{
    /**
     * Constant for value 'WEIGHT'
     * @return string 'WEIGHT'
     */
    const VALUE_WEIGHT = 'WEIGHT';
    /**
     * Constant for value 'VOLUME'
     * @return string 'VOLUME'
     */
    const VALUE_VOLUME = 'VOLUME';
    /**
     * Constant for value 'DURATION'
     * @return string 'DURATION'
     */
    const VALUE_DURATION = 'DURATION';
    /**
     * Constant for value 'TEMPERATURE'
     * @return string 'TEMPERATURE'
     */
    const VALUE_TEMPERATURE = 'TEMPERATURE';
    /**
     * Constant for value 'CONCENTRATION'
     * @return string 'CONCENTRATION'
     */
    const VALUE_CONCENTRATION = 'CONCENTRATION';
    /**
     * Constant for value 'AREA'
     * @return string 'AREA'
     */
    const VALUE_AREA = 'AREA';
    /**
     * Constant for value 'DISTANCE'
     * @return string 'DISTANCE'
     */
    const VALUE_DISTANCE = 'DISTANCE';
    /**
     * Return allowed values
     * @uses self::VALUE_WEIGHT
     * @uses self::VALUE_VOLUME
     * @uses self::VALUE_DURATION
     * @uses self::VALUE_TEMPERATURE
     * @uses self::VALUE_CONCENTRATION
     * @uses self::VALUE_AREA
     * @uses self::VALUE_DISTANCE
     * @return string[]
     */
    public static function getValidValues()
    {
        return array(
            self::VALUE_WEIGHT,
            self::VALUE_VOLUME,
            self::VALUE_DURATION,
            self::VALUE_TEMPERATURE,
            self::VALUE_CONCENTRATION,
            self::VALUE_AREA,
            self::VALUE_DISTANCE,
        );
    }
}
