<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ePhytoEnvelope StructType
 * Meta information extracted from the WSDL
 * - type: ns1:ePhytoEnvelope
 * @subpackage Structs
 */
class EPhytoEnvelope extends EnvelopeHeader
{
    /**
     * The SPSCertificate
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns4:SPSCertificate
     * @var \StructType\SPSCertificate
     */
    public $SPSCertificate;
    /**
     * Constructor method for ePhytoEnvelope
     * @uses EPhytoEnvelope::setSPSCertificate()
     * @param \StructType\SPSCertificate $sPSCertificate
     */
    public function __construct(\StructType\SPSCertificate $sPSCertificate = null)
    {
        $this
            ->setSPSCertificate($sPSCertificate);
    }
    /**
     * Get SPSCertificate value
     * @return \StructType\SPSCertificate|null
     */
    public function getSPSCertificate()
    {
        return $this->SPSCertificate;
    }
    /**
     * Set SPSCertificate value
     * @param \StructType\SPSCertificate $sPSCertificate
     * @return \StructType\EPhytoEnvelope
     */
    public function setSPSCertificate(\StructType\SPSCertificate $sPSCertificate = null)
    {
        $this->SPSCertificate = $sPSCertificate;
        return $this;
    }
}
