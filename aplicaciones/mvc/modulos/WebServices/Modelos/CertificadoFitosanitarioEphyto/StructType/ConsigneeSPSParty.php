<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for consigneeSPSParty StructType
 * @subpackage Structs
 */
class ConsigneeSPSParty extends AbstractStructBase
{
    /**
     * The Name
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:Name
     * @var string
     */
    public $Name;
    /**
     * The SpecifiedSPSAddress
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:SpecifiedSPSAddress
     * @var \StructType\SpecifiedSPSAddress
     */
    public $SpecifiedSPSAddress;
    /**
     * Constructor method for consigneeSPSParty
     * @uses ConsigneeSPSParty::setName()
     * @uses ConsigneeSPSParty::setSpecifiedSPSAddress()
     * @param string $name
     * @param \StructType\SpecifiedSPSAddress $specifiedSPSAddress
     */
    public function __construct($name = null, \StructType\SpecifiedSPSAddress $specifiedSPSAddress = null)
    {
        $this
            ->setName($name)
            ->setSpecifiedSPSAddress($specifiedSPSAddress);
    }
    /**
     * Get Name value
     * @return string|null
     */
    public function getName()
    {
        return $this->Name;
    }
    /**
     * Set Name value
     * @param string $name
     * @return \StructType\ConsigneeSPSParty
     */
    public function setName($name = null)
    {
        // validation for constraint: string
        if (!is_null($name) && !is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($name, true), gettype($name)), __LINE__);
        }
        $this->Name = $name;
        return $this;
    }
    /**
     * Get SpecifiedSPSAddress value
     * @return \StructType\SpecifiedSPSAddress|null
     */
    public function getSpecifiedSPSAddress()
    {
        return $this->SpecifiedSPSAddress;
    }
    /**
     * Set SpecifiedSPSAddress value
     * @param \StructType\SpecifiedSPSAddress $specifiedSPSAddress
     * @return \StructType\ConsigneeSPSParty
     */
    public function setSpecifiedSPSAddress(\StructType\SpecifiedSPSAddress $specifiedSPSAddress = null)
    {
        $this->SpecifiedSPSAddress = $specifiedSPSAddress;
        return $this;
    }
}
