<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for startDateTime StructType
 * @subpackage Structs
 */
class StartDateTime extends AbstractStructBase
{
    /**
     * The DateTimeString
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns4:DateTimeString
     * @var string
     */
    public $DateTimeString;
    /**
     * Constructor method for startDateTime
     * @uses StartDateTime::setDateTimeString()
     * @param string $dateTimeString
     */
    public function __construct($dateTimeString = null)
    {
        $this
            ->setDateTimeString($dateTimeString);
    }
    /**
     * Get DateTimeString value
     * @return string|null
     */
    public function getDateTimeString()
    {
        return $this->DateTimeString;
    }
    /**
     * Set DateTimeString value
     * @param string $dateTimeString
     * @return \StructType\StartDateTime
     */
    public function setDateTimeString($dateTimeString = null)
    {
        // validation for constraint: string
        if (!is_null($dateTimeString) && !is_string($dateTimeString)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($dateTimeString, true), gettype($dateTimeString)), __LINE__);
        }
        $this->DateTimeString = $dateTimeString;
        return $this;
    }
}
