<?php

namespace EnumType;

use \WsdlToPhp\PackageBase\AbstractStructEnumBase;

/**
 * This class stands for HUBTrackingInfo EnumType
 * Meta information extracted from the WSDL
 * - type: tns:HUBTrackingInfo
 * @subpackage Enumerations
 */
class HUBTrackingInfo extends AbstractStructEnumBase
{
    /**
     * Constant for value 'PendingDelivery'
     * @return string 'PendingDelivery'
     */
    const VALUE_PENDING_DELIVERY = 'PendingDelivery';
    /**
     * Constant for value 'Delivered'
     * @return string 'Delivered'
     */
    const VALUE_DELIVERED = 'Delivered';
    /**
     * Constant for value 'FailedDelivery'
     * @return string 'FailedDelivery'
     */
    const VALUE_FAILED_DELIVERY = 'FailedDelivery';
    /**
     * Constant for value 'EnvelopeNotExists'
     * @return string 'EnvelopeNotExists'
     */
    const VALUE_ENVELOPE_NOT_EXISTS = 'EnvelopeNotExists';
    /**
     * Constant for value 'DeliveredWithWarnings'
     * @return string 'DeliveredWithWarnings'
     */
    const VALUE_DELIVERED_WITH_WARNINGS = 'DeliveredWithWarnings';
    /**
     * Constant for value 'DeliveredNotReadable'
     * @return string 'DeliveredNotReadable'
     */
    const VALUE_DELIVERED_NOT_READABLE = 'DeliveredNotReadable';
    /**
     * Return allowed values
     * @uses self::VALUE_PENDING_DELIVERY
     * @uses self::VALUE_DELIVERED
     * @uses self::VALUE_FAILED_DELIVERY
     * @uses self::VALUE_ENVELOPE_NOT_EXISTS
     * @uses self::VALUE_DELIVERED_WITH_WARNINGS
     * @uses self::VALUE_DELIVERED_NOT_READABLE
     * @return string[]
     */
    public static function getValidValues()
    {
        return array(
            self::VALUE_PENDING_DELIVERY,
            self::VALUE_DELIVERED,
            self::VALUE_FAILED_DELIVERY,
            self::VALUE_ENVELOPE_NOT_EXISTS,
            self::VALUE_DELIVERED_WITH_WARNINGS,
            self::VALUE_DELIVERED_NOT_READABLE,
        );
    }
}
