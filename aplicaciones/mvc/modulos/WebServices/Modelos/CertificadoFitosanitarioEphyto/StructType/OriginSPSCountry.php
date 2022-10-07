<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for originSPSCountry StructType
 * @subpackage Structs
 */
class OriginSPSCountry extends AbstractStructBase
{
    /**
     * The ID
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $ID;
    /**
     * The Name
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Name;
    /**
     * The SubordinateSPSCountrySubDivision
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:SubordinateSPSCountrySubDivision
     * @var \StructType\SubordinateSPSCountrySubDivision
     */
    public $SubordinateSPSCountrySubDivision;
    /**
     * Constructor method for originSPSCountry
     * @uses OriginSPSCountry::setID()
     * @uses OriginSPSCountry::setName()
     * @uses OriginSPSCountry::setSubordinateSPSCountrySubDivision()
     * @param string $iD
     * @param string $name
     * @param \StructType\SubordinateSPSCountrySubDivision $subordinateSPSCountrySubDivision
     */
    public function __construct($iD = null, $name = null, \StructType\SubordinateSPSCountrySubDivision $subordinateSPSCountrySubDivision = null)
    {
        $this
            ->setID($iD)
            ->setName($name)
            ->setSubordinateSPSCountrySubDivision($subordinateSPSCountrySubDivision);
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
     * @return \StructType\OriginSPSCountry
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
     * @return \StructType\OriginSPSCountry
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
     * Get SubordinateSPSCountrySubDivision value
     * @return \StructType\SubordinateSPSCountrySubDivision|null
     */
    public function getSubordinateSPSCountrySubDivision()
    {
        return $this->SubordinateSPSCountrySubDivision;
    }
    /**
     * Set SubordinateSPSCountrySubDivision value
     * @param \StructType\SubordinateSPSCountrySubDivision $subordinateSPSCountrySubDivision
     * @return \StructType\OriginSPSCountry
     */
    public function setSubordinateSPSCountrySubDivision(\StructType\SubordinateSPSCountrySubDivision $subordinateSPSCountrySubDivision = null)
    {
        $this->SubordinateSPSCountrySubDivision = $subordinateSPSCountrySubDivision;
        return $this;
    }
}
