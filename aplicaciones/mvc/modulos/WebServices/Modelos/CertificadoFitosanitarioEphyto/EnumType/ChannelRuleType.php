<?php

namespace EnumType;

use \WsdlToPhp\PackageBase\AbstractStructEnumBase;

/**
 * This class stands for channelRuleType EnumType
 * @subpackage Enumerations
 */
class ChannelRuleType extends AbstractStructEnumBase
{
    /**
     * Constant for value 'FORWARD'
     * @return string 'FORWARD'
     */
    const VALUE_FORWARD = 'FORWARD';
    /**
     * Constant for value 'DELEGATE'
     * @return string 'DELEGATE'
     */
    const VALUE_DELEGATE = 'DELEGATE';
    /**
     * Return allowed values
     * @uses self::VALUE_FORWARD
     * @uses self::VALUE_DELEGATE
     * @return string[]
     */
    public static function getValidValues()
    {
        return array(
            self::VALUE_FORWARD,
            self::VALUE_DELEGATE,
        );
    }
}
