<?php

namespace EnumType;

use \WsdlToPhp\PackageBase\AbstractStructEnumBase;

/**
 * This class stands for channelDirection EnumType
 * @subpackage Enumerations
 */
class ChannelDirection extends AbstractStructEnumBase
{
    /**
     * Constant for value 'OUT'
     * @return string 'OUT'
     */
    const VALUE_OUT = 'OUT';
    /**
     * Constant for value 'INC'
     * @return string 'INC'
     */
    const VALUE_INC = 'INC';
    /**
     * Return allowed values
     * @uses self::VALUE_OUT
     * @uses self::VALUE_INC
     * @return string[]
     */
    public static function getValidValues()
    {
        return array(
            self::VALUE_OUT,
            self::VALUE_INC,
        );
    }
}
