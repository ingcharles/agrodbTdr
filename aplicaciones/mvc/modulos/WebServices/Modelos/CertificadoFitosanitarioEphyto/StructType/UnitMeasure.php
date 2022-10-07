<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for unitMeasure StructType
 * @subpackage Structs
 */
class UnitMeasure extends AbstractStructBase
{
    /**
     * The active
     * Meta information extracted from the WSDL
     * - form: unqualified
     * @var bool
     */
    public $active;
    /**
     * The code
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $code;
    /**
     * The lastModified
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $lastModified;
    /**
     * The lastModifiedBy
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $lastModifiedBy;
    /**
     * The name
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $name;
    /**
     * The symbol
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $symbol;
    /**
     * The type
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $type;
    /**
     * Constructor method for unitMeasure
     * @uses UnitMeasure::setActive()
     * @uses UnitMeasure::setCode()
     * @uses UnitMeasure::setLastModified()
     * @uses UnitMeasure::setLastModifiedBy()
     * @uses UnitMeasure::setName()
     * @uses UnitMeasure::setSymbol()
     * @uses UnitMeasure::setType()
     * @param bool $active
     * @param string $code
     * @param string $lastModified
     * @param string $lastModifiedBy
     * @param string $name
     * @param string $symbol
     * @param string $type
     */
    public function __construct($active = null, $code = null, $lastModified = null, $lastModifiedBy = null, $name = null, $symbol = null, $type = null)
    {
        $this
            ->setActive($active)
            ->setCode($code)
            ->setLastModified($lastModified)
            ->setLastModifiedBy($lastModifiedBy)
            ->setName($name)
            ->setSymbol($symbol)
            ->setType($type);
    }
    /**
     * Get active value
     * @return bool|null
     */
    public function getActive()
    {
        return $this->active;
    }
    /**
     * Set active value
     * @param bool $active
     * @return \StructType\UnitMeasure
     */
    public function setActive($active = null)
    {
        // validation for constraint: boolean
        if (!is_null($active) && !is_bool($active)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($active, true), gettype($active)), __LINE__);
        }
        $this->active = $active;
        return $this;
    }
    /**
     * Get code value
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }
    /**
     * Set code value
     * @param string $code
     * @return \StructType\UnitMeasure
     */
    public function setCode($code = null)
    {
        // validation for constraint: string
        if (!is_null($code) && !is_string($code)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($code, true), gettype($code)), __LINE__);
        }
        $this->code = $code;
        return $this;
    }
    /**
     * Get lastModified value
     * @return string|null
     */
    public function getLastModified()
    {
        return $this->lastModified;
    }
    /**
     * Set lastModified value
     * @param string $lastModified
     * @return \StructType\UnitMeasure
     */
    public function setLastModified($lastModified = null)
    {
        // validation for constraint: string
        if (!is_null($lastModified) && !is_string($lastModified)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lastModified, true), gettype($lastModified)), __LINE__);
        }
        $this->lastModified = $lastModified;
        return $this;
    }
    /**
     * Get lastModifiedBy value
     * @return string|null
     */
    public function getLastModifiedBy()
    {
        return $this->lastModifiedBy;
    }
    /**
     * Set lastModifiedBy value
     * @param string $lastModifiedBy
     * @return \StructType\UnitMeasure
     */
    public function setLastModifiedBy($lastModifiedBy = null)
    {
        // validation for constraint: string
        if (!is_null($lastModifiedBy) && !is_string($lastModifiedBy)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($lastModifiedBy, true), gettype($lastModifiedBy)), __LINE__);
        }
        $this->lastModifiedBy = $lastModifiedBy;
        return $this;
    }
    /**
     * Get name value
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Set name value
     * @param string $name
     * @return \StructType\UnitMeasure
     */
    public function setName($name = null)
    {
        // validation for constraint: string
        if (!is_null($name) && !is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($name, true), gettype($name)), __LINE__);
        }
        $this->name = $name;
        return $this;
    }
    /**
     * Get symbol value
     * @return string|null
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
    /**
     * Set symbol value
     * @param string $symbol
     * @return \StructType\UnitMeasure
     */
    public function setSymbol($symbol = null)
    {
        // validation for constraint: string
        if (!is_null($symbol) && !is_string($symbol)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($symbol, true), gettype($symbol)), __LINE__);
        }
        $this->symbol = $symbol;
        return $this;
    }
    /**
     * Get type value
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Set type value
     * @uses \EnumType\UnitMeasureType::valueIsValid()
     * @uses \EnumType\UnitMeasureType::getValidValues()
     * @throws \InvalidArgumentException
     * @param string $type
     * @return \StructType\UnitMeasure
     */
    public function setType($type = null)
    {
        // validation for constraint: enumeration
        if (!\EnumType\UnitMeasureType::valueIsValid($type)) {
            throw new \InvalidArgumentException(sprintf('Invalid value(s) %s, please use one of: %s from enumeration class \EnumType\UnitMeasureType', is_array($type) ? implode(', ', $type) : var_export($type, true), implode(', ', \EnumType\UnitMeasureType::getValidValues())), __LINE__);
        }
        $this->type = $type;
        return $this;
    }
}
