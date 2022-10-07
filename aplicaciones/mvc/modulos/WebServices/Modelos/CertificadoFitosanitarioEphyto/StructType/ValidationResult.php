<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for validationResult StructType
 * @subpackage Structs
 */
class ValidationResult extends AbstractStructBase
{
    /**
     * The area
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $area;
    /**
     * The field
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $field;
    /**
     * The level
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $level;
    /**
     * The msg
     * Meta information extracted from the WSDL
     * - form: unqualified
     * - minOccurs: 0
     * @var string
     */
    public $msg;
    /**
     * Constructor method for validationResult
     * @uses ValidationResult::setArea()
     * @uses ValidationResult::setField()
     * @uses ValidationResult::setLevel()
     * @uses ValidationResult::setMsg()
     * @param string $area
     * @param string $field
     * @param string $level
     * @param string $msg
     */
    public function __construct($area = null, $field = null, $level = null, $msg = null)
    {
        $this
            ->setArea($area)
            ->setField($field)
            ->setLevel($level)
            ->setMsg($msg);
    }
    /**
     * Get area value
     * @return string|null
     */
    public function getArea()
    {
        return $this->area;
    }
    /**
     * Set area value
     * @uses \EnumType\ValidationArea::valueIsValid()
     * @uses \EnumType\ValidationArea::getValidValues()
     * @throws \InvalidArgumentException
     * @param string $area
     * @return \StructType\ValidationResult
     */
    public function setArea($area = null)
    {
        // validation for constraint: enumeration
        if (!\EnumType\ValidationArea::valueIsValid($area)) {
            throw new \InvalidArgumentException(sprintf('Invalid value(s) %s, please use one of: %s from enumeration class \EnumType\ValidationArea', is_array($area) ? implode(', ', $area) : var_export($area, true), implode(', ', \EnumType\ValidationArea::getValidValues())), __LINE__);
        }
        $this->area = $area;
        return $this;
    }
    /**
     * Get field value
     * @return string|null
     */
    public function getField()
    {
        return $this->field;
    }
    /**
     * Set field value
     * @param string $field
     * @return \StructType\ValidationResult
     */
    public function setField($field = null)
    {
        // validation for constraint: string
        if (!is_null($field) && !is_string($field)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($field, true), gettype($field)), __LINE__);
        }
        $this->field = $field;
        return $this;
    }
    /**
     * Get level value
     * @return string|null
     */
    public function getLevel()
    {
        return $this->level;
    }
    /**
     * Set level value
     * @uses \EnumType\ValidationLevel::valueIsValid()
     * @uses \EnumType\ValidationLevel::getValidValues()
     * @throws \InvalidArgumentException
     * @param string $level
     * @return \StructType\ValidationResult
     */
    public function setLevel($level = null)
    {
        // validation for constraint: enumeration
        if (!\EnumType\ValidationLevel::valueIsValid($level)) {
            throw new \InvalidArgumentException(sprintf('Invalid value(s) %s, please use one of: %s from enumeration class \EnumType\ValidationLevel', is_array($level) ? implode(', ', $level) : var_export($level, true), implode(', ', \EnumType\ValidationLevel::getValidValues())), __LINE__);
        }
        $this->level = $level;
        return $this;
    }
    /**
     * Get msg value
     * @return string|null
     */
    public function getMsg()
    {
        return $this->msg;
    }
    /**
     * Set msg value
     * @param string $msg
     * @return \StructType\ValidationResult
     */
    public function setMsg($msg = null)
    {
        // validation for constraint: string
        if (!is_null($msg) && !is_string($msg)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($msg, true), gettype($msg)), __LINE__);
        }
        $this->msg = $msg;
        return $this;
    }
}
