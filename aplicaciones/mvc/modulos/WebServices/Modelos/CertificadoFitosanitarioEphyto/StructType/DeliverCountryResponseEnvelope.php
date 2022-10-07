<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DeliverCountryResponseEnvelope StructType
 * Meta information extracted from the WSDL
 * - type: tns:DeliverCountryResponseEnvelope
 * @subpackage Structs
 */
class DeliverCountryResponseEnvelope extends AbstractStructBase
{
    /**
     * The CountryResponseEnvelope
     * @var \StructType\CountryResponseEnvelope
     */
    public $CountryResponseEnvelope;
    /**
     * Constructor method for DeliverCountryResponseEnvelope
     * @uses DeliverCountryResponseEnvelope::setCountryResponseEnvelope()
     * @param \StructType\CountryResponseEnvelope $countryResponseEnvelope
     */
    public function __construct(\StructType\CountryResponseEnvelope $countryResponseEnvelope = null)
    {
        $this
            ->setCountryResponseEnvelope($countryResponseEnvelope);
    }
    /**
     * Get CountryResponseEnvelope value
     * @return \StructType\CountryResponseEnvelope|null
     */
    public function getCountryResponseEnvelope()
    {
        return $this->CountryResponseEnvelope;
    }
    /**
     * Set CountryResponseEnvelope value
     * @param \StructType\CountryResponseEnvelope $countryResponseEnvelope
     * @return \StructType\DeliverCountryResponseEnvelope
     */
    public function setCountryResponseEnvelope(\StructType\CountryResponseEnvelope $countryResponseEnvelope = null)
    {
        $this->CountryResponseEnvelope = $countryResponseEnvelope;
        return $this;
    }
}
