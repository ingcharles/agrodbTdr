<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for providerSPSParty StructType
 * @subpackage Structs
 */
class ProviderSPSParty extends AbstractStructBase
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
     * The SpecifiedSPSPerson
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:SpecifiedSPSPerson
     * @var \StructType\SpecifiedSPSPerson
     */
    public $SpecifiedSPSPerson;
    /**
     * Constructor method for providerSPSParty
     * @uses ProviderSPSParty::setName()
     * @uses ProviderSPSParty::setSpecifiedSPSPerson()
     * @param string $name
     * @param \StructType\SpecifiedSPSPerson $specifiedSPSPerson
     */
    public function __construct($name = null, \StructType\SpecifiedSPSPerson $specifiedSPSPerson = null)
    {
        $this
            ->setName($name)
            ->setSpecifiedSPSPerson($specifiedSPSPerson);
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
     * @return \StructType\ProviderSPSParty
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
     * Get SpecifiedSPSPerson value
     * @return \StructType\SpecifiedSPSPerson|null
     */
    public function getSpecifiedSPSPerson()
    {
        return $this->SpecifiedSPSPerson;
    }
    /**
     * Set SpecifiedSPSPerson value
     * @param \StructType\SpecifiedSPSPerson $specifiedSPSPerson
     * @return \StructType\ProviderSPSParty
     */
    public function setSpecifiedSPSPerson(\StructType\SpecifiedSPSPerson $specifiedSPSPerson = null)
    {
        $this->SpecifiedSPSPerson = $specifiedSPSPerson;
        return $this;
    }
}
