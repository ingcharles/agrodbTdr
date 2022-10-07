<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for subordinateSPSCountrySubDivision StructType
 * @subpackage Structs
 */
class SubordinateSPSCountrySubDivision extends AbstractStructBase
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
     * The HierarchicalLevelCode
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:HierarchicalLevelCode
     * @var string
     */
    public $HierarchicalLevelCode;
    /**
     * Constructor method for subordinateSPSCountrySubDivision
     * @uses SubordinateSPSCountrySubDivision::setName()
     * @uses SubordinateSPSCountrySubDivision::setHierarchicalLevelCode()
     * @param string $name
     * @param string $hierarchicalLevelCode
     */
    public function __construct($name = null, $hierarchicalLevelCode = null)
    {
        $this
            ->setName($name)
            ->setHierarchicalLevelCode($hierarchicalLevelCode);
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
     * @return \StructType\SubordinateSPSCountrySubDivision
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
     * Get HierarchicalLevelCode value
     * @return string|null
     */
    public function getHierarchicalLevelCode()
    {
        return $this->HierarchicalLevelCode;
    }
    /**
     * Set HierarchicalLevelCode value
     * @param string $hierarchicalLevelCode
     * @return \StructType\SubordinateSPSCountrySubDivision
     */
    public function setHierarchicalLevelCode($hierarchicalLevelCode = null)
    {
        // validation for constraint: string
        if (!is_null($hierarchicalLevelCode) && !is_string($hierarchicalLevelCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($hierarchicalLevelCode, true), gettype($hierarchicalLevelCode)), __LINE__);
        }
        $this->HierarchicalLevelCode = $hierarchicalLevelCode;
        return $this;
    }
}
