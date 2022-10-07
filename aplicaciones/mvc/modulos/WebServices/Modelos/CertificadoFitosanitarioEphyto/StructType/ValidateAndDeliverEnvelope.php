<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for ValidateAndDeliverEnvelope StructType
 * Meta information extracted from the WSDL
 * - type: tns:ValidateAndDeliverEnvelope
 * @subpackage Structs
 */
class ValidateAndDeliverEnvelope extends AbstractStructBase
{
    /**
     * The envelope
     * Meta information extracted from the WSDL
     * - ref: tns:envelope
     * @var \StructType\Envelope
     */
    public $envelope;
    /**
     * Constructor method for ValidateAndDeliverEnvelope
     * @uses ValidateAndDeliverEnvelope::setEnvelope()
     * @param \StructType\Envelope $envelope
     */
    public function __construct(\StructType\Envelope $envelope = null)
    {
        $this
            ->setEnvelope($envelope);
    }
    /**
     * Get envelope value
     * @return \StructType\Envelope|null
     */
    public function getEnvelope()
    {
        return $this->envelope;
    }
    /**
     * Set envelope value
     * @param \StructType\Envelope $envelope
     * @return \StructType\ValidateAndDeliverEnvelope
     */
    public function setEnvelope(\StructType\Envelope $envelope = null)
    {
        $this->envelope = $envelope;
        return $this;
    }
}
