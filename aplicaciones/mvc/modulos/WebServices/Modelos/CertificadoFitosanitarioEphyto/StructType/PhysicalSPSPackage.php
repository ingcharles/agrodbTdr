<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for physicalSPSPackage StructType
 * @subpackage Structs
 */
class PhysicalSPSPackage extends AbstractStructBase
{
    /**
     * The LevelCode
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $LevelCode;
    /**
     * The TypeCode
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $TypeCode;
    /**
     * The ItemQuantity
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var float
     */
    public $ItemQuantity;
    /**
     * Constructor method for physicalSPSPackage
     * @uses PhysicalSPSPackage::setLevelCode()
     * @uses PhysicalSPSPackage::setTypeCode()
     * @uses PhysicalSPSPackage::setItemQuantity()
     * @param int $levelCode
     * @param string $typeCode
     * @param float $itemQuantity
     */
    public function __construct($levelCode = null, $typeCode = null, $itemQuantity = null)
    {
        $this
            ->setLevelCode($levelCode)
            ->setTypeCode($typeCode)
            ->setItemQuantity($itemQuantity);
    }
    /**
     * Get LevelCode value
     * @return int|null
     */
    public function getLevelCode()
    {
        return $this->LevelCode;
    }
    /**
     * Set LevelCode value
     * @param int $levelCode
     * @return \StructType\PhysicalSPSPackage
     */
    public function setLevelCode($levelCode = null)
    {
        // validation for constraint: int
        if (!is_null($levelCode) && !(is_int($levelCode) || ctype_digit($levelCode))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($levelCode, true), gettype($levelCode)), __LINE__);
        }
        $this->LevelCode = $levelCode;
        return $this;
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
     * @return \StructType\PhysicalSPSPackage
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
    /**
     * Get ItemQuantity value
     * @return float|null
     */
    public function getItemQuantity()
    {
        return $this->ItemQuantity;
    }
    /**
     * Set ItemQuantity value
     * @param float $itemQuantity
     * @return \StructType\PhysicalSPSPackage
     */
    public function setItemQuantity($itemQuantity = null)
    {
        // validation for constraint: float
        if (!is_null($itemQuantity) && !(is_float($itemQuantity) || is_numeric($itemQuantity))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a float value, %s given', var_export($itemQuantity, true), gettype($itemQuantity)), __LINE__);
        }
        $this->ItemQuantity = $itemQuantity;
        return $this;
    }
}
