<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for EnvelopeFrowarding StructType
 * @subpackage Structs
 */
class EnvelopeFrowarding extends AbstractStructBase
{
    /**
     * The Code
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var string
     */
    public $Code;
    /**
     * The HubDeliveryNumber
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $HubDeliveryNumber;
    /**
     * Constructor method for EnvelopeFrowarding
     * @uses EnvelopeFrowarding::setCode()
     * @uses EnvelopeFrowarding::setHubDeliveryNumber()
     * @param string $code
     * @param string $hubDeliveryNumber
     */
    public function __construct($code = null, $hubDeliveryNumber = null)
    {
        $this
            ->setCode($code)
            ->setHubDeliveryNumber($hubDeliveryNumber);
    }
    /**
     * Get Code value
     * @return string|null
     */
    public function getCode()
    {
        return $this->Code;
    }
    /**
     * Set Code value
     * @param string $code
     * @return \StructType\EnvelopeFrowarding
     */
    public function setCode($code = null)
    {
        // validation for constraint: string
        if (!is_null($code) && !is_string($code)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($code, true), gettype($code)), __LINE__);
        }
        $this->Code = $code;
        return $this;
    }
    /**
     * Get HubDeliveryNumber value
     * @return string|null
     */
    public function getHubDeliveryNumber()
    {
        return $this->HubDeliveryNumber;
    }
    /**
     * Set HubDeliveryNumber value
     * @param string $hubDeliveryNumber
     * @return \StructType\EnvelopeFrowarding
     */
    public function setHubDeliveryNumber($hubDeliveryNumber = null)
    {
        // validation for constraint: string
        if (!is_null($hubDeliveryNumber) && !is_string($hubDeliveryNumber)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($hubDeliveryNumber, true), gettype($hubDeliveryNumber)), __LINE__);
        }
        $this->HubDeliveryNumber = $hubDeliveryNumber;
        return $this;
    }
}
