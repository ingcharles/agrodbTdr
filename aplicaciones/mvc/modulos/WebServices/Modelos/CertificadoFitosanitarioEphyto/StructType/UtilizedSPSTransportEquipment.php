<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for utilizedSPSTransportEquipment StructType
 * @subpackage Structs
 */
class UtilizedSPSTransportEquipment extends AbstractStructBase
{
    /**
     * The ID
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $ID;
    /**
     * The AffixedSPSSeal
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:AffixedSPSSeal
     * @var \StructType\AffixedSPSSeal
     */
    public $AffixedSPSSeal;
    /**
     * Constructor method for utilizedSPSTransportEquipment
     * @uses UtilizedSPSTransportEquipment::setID()
     * @uses UtilizedSPSTransportEquipment::setAffixedSPSSeal()
     * @param string $iD
     * @param \StructType\AffixedSPSSeal $affixedSPSSeal
     */
    public function __construct($iD = null, \StructType\AffixedSPSSeal $affixedSPSSeal = null)
    {
        $this
            ->setID($iD)
            ->setAffixedSPSSeal($affixedSPSSeal);
    }
    /**
     * Get ID value
     * @return string|null
     */
    public function getID()
    {
        return $this->ID;
    }
    /**
     * Set ID value
     * @param string $iD
     * @return \StructType\UtilizedSPSTransportEquipment
     */
    public function setID($iD = null)
    {
        // validation for constraint: string
        if (!is_null($iD) && !is_string($iD)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($iD, true), gettype($iD)), __LINE__);
        }
        $this->ID = $iD;
        return $this;
    }
    /**
     * Get AffixedSPSSeal value
     * @return \StructType\AffixedSPSSeal|null
     */
    public function getAffixedSPSSeal()
    {
        return $this->AffixedSPSSeal;
    }
    /**
     * Set AffixedSPSSeal value
     * @param \StructType\AffixedSPSSeal $affixedSPSSeal
     * @return \StructType\UtilizedSPSTransportEquipment
     */
    public function setAffixedSPSSeal(\StructType\AffixedSPSSeal $affixedSPSSeal = null)
    {
        $this->AffixedSPSSeal = $affixedSPSSeal;
        return $this;
    }
}
