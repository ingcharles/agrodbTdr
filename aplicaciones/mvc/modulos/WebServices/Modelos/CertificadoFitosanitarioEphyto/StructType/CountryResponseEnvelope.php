<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for CountryResponseEnvelope StructType
 * @subpackage Structs
 */
class CountryResponseEnvelope extends EnvelopeHeader
{
    /**
     * The SPSAcknowledgement
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:SPSAcknowledgement
     * @var \StructType\SPSAcknowledgement
     */
    public $SPSAcknowledgement;
    /**
     * Constructor method for CountryResponseEnvelope
     * @uses CountryResponseEnvelope::setSPSAcknowledgement()
     * @param \StructType\SPSAcknowledgement $sPSAcknowledgement
     */
    public function __construct(\StructType\SPSAcknowledgement $sPSAcknowledgement = null)
    {
        $this
            ->setSPSAcknowledgement($sPSAcknowledgement);
    }
    /**
     * Get SPSAcknowledgement value
     * @return \StructType\SPSAcknowledgement|null
     */
    public function getSPSAcknowledgement()
    {
        return $this->SPSAcknowledgement;
    }
    /**
     * Set SPSAcknowledgement value
     * @param \StructType\SPSAcknowledgement $sPSAcknowledgement
     * @return \StructType\CountryResponseEnvelope
     */
    public function setSPSAcknowledgement(\StructType\SPSAcknowledgement $sPSAcknowledgement = null)
    {
        $this->SPSAcknowledgement = $sPSAcknowledgement;
        return $this;
    }
}
