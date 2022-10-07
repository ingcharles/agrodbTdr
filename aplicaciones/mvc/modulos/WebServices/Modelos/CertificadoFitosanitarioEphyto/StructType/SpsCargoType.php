<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for spsCargoType StructType
 * @subpackage Structs
 */
class SpsCargoType extends AbstractStructBase
{
    /**
     * The TypeCode
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var string
     */
    public $TypeCode;
    /**
     * Constructor method for spsCargoType
     * @uses SpsCargoType::setTypeCode()
     * @param string $typeCode
     */
    public function __construct($typeCode = null)
    {
        $this
            ->setTypeCode($typeCode);
    }
    /**
     * Get TypeCode value
     * @return string|null
     */
    public function getTypeCode()
    {
        return $this->TypeCode;
    }
    /**
     * Set TypeCode value
     * @param string $typeCode
     * @return \StructType\SpsCargoType
     */
    public function setTypeCode($typeCode = null)
    {
        // validation for constraint: string
        if (!is_null($typeCode) && !is_string($typeCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($typeCode, true), gettype($typeCode)), __LINE__);
        }
        $this->TypeCode = $typeCode;
        return $this;
    }
}
