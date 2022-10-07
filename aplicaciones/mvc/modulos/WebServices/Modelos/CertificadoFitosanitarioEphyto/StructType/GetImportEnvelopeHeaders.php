<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetImportEnvelopeHeaders StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetImportEnvelopeHeaders
 * @subpackage Structs
 */
class GetImportEnvelopeHeaders extends AbstractStructBase
{
    /**
     * The countryCode
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var string
     */
    public $countryCode;
    /**
     * Constructor method for GetImportEnvelopeHeaders
     * @uses GetImportEnvelopeHeaders::setCountryCode()
     * @param string $countryCode
     */
    public function __construct($countryCode = null)
    {
        $this
            ->setCountryCode($countryCode);
    }
    /**
     * Get countryCode value
     * @return string|null
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }
    /**
     * Set countryCode value
     * @param string $countryCode
     * @return \StructType\GetImportEnvelopeHeaders
     */
    public function setCountryCode($countryCode = null)
    {
        // validation for constraint: string
        if (!is_null($countryCode) && !is_string($countryCode)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($countryCode, true), gettype($countryCode)), __LINE__);
        }
        $this->countryCode = $countryCode;
        return $this;
    }
}
