<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for certificateType StructType
 * @subpackage Structs
 */
class CertificateType extends AbstractStructBase
{
    /**
     * The number
     * Meta information extracted from the WSDL
     * - use: required
     * @var int
     */
    public $number;
    /**
     * The value
     * @var string
     */
    public $value;
    /**
     * Constructor method for certificateType
     * @uses CertificateType::setNumber()
     * @uses CertificateType::setValue()
     * @param int $number
     * @param string $value
     */
    public function __construct($number = null, $value = null)
    {
        $this
            ->setNumber($number)
            ->setValue($value);
    }
    /**
     * Get number value
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }
    /**
     * Set number value
     * @param int $number
     * @return \StructType\CertificateType
     */
    public function setNumber($number = null)
    {
        // validation for constraint: int
        if (!is_null($number) && !(is_int($number) || ctype_digit($number))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($number, true), gettype($number)), __LINE__);
        }
        $this->number = $number;
        return $this;
    }
    /**
     * Get value value
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * Set value value
     * @param string $value
     * @return \StructType\CertificateType
     */
    public function setValue($value = null)
    {
        // validation for constraint: string
        if (!is_null($value) && !is_string($value)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($value, true), gettype($value)), __LINE__);
        }
        $this->value = $value;
        return $this;
    }
}
