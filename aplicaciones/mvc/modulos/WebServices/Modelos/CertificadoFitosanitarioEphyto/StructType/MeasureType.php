<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for measureType StructType
 * @subpackage Structs
 */
class MeasureType extends AbstractStructBase
{
    /**
     * The _
     * @var float
     */
    public $_;
    /**
     * The unitCode
     * @var string
     */
    public $unitCode;
    /**
     * Constructor method for measureType
     * @uses MeasureType::set_()
     * @uses MeasureType::setUnitCode()
     * @param float $_
     * @param string $unitCode
     */
    public function __construct($_ = null, $unitCode = null)
    {
        $this
            ->set_($_)
            ->setUnitCode($unitCode);
    }
    /**
     * Get _ value
     * @return float|null
     */
    public function get_()
    {
        return $this->_;
    }
    /**
     * Set _ value
     * @param float $_
     * @return \StructType\MeasureType
     */
    public function set_($_ = null)
    {
        // validation for constraint: float
        if (!is_null($_) && !(is_float($_) || is_numeric($_))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a float value, %s given', var_export($_, true), gettype($_)), __LINE__);
        }
        $this->_ = $_;
        return $this;
    }
    /**
     * Get unitCode value
     * @return string|null
     */
    public function getUnitCode()
    {
        return $this->unitCode;
    }
    /**
     * Set unitCode value
     * @param string $unitCode
     * @return \StructType\MeasureType
     */
    public function setUnitCode($unitCode = null)
    {
        // validation for constraint: string
        if (!is_null($unitCode) && !is_string($unitCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($unitCode, true), gettype($unitCode)), __LINE__);
        }
        $this->unitCode = $unitCode;
        return $this;
    }
}
