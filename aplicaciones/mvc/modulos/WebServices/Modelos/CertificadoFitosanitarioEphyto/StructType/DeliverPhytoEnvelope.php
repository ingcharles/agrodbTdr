<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DeliverPhytoEnvelope StructType
 * Meta information extracted from the WSDL
 * - type: tns:DeliverPhytoEnvelope
 * @subpackage Structs
 */
class DeliverPhytoEnvelope extends AbstractStructBase
{
    /**
     * The ePhytoEnvelope
     * Meta information extracted from the WSDL
     * - ref: tns:ePhytoEnvelope
     * @var \StructType\EPhytoEnvelope
     */
    public $ePhytoEnvelope;
    /**
     * Constructor method for DeliverPhytoEnvelope
     * @uses DeliverPhytoEnvelope::setEPhytoEnvelope()
     * @param \StructType\EPhytoEnvelope $ePhytoEnvelope
     */
    public function __construct(\StructType\EPhytoEnvelope $ePhytoEnvelope = null)
    {
        $this
            ->setEPhytoEnvelope($ePhytoEnvelope);
    }
    /**
     * Get ePhytoEnvelope value
     * @return \StructType\EPhytoEnvelope|null
     */
    public function getEPhytoEnvelope()
    {
        return $this->ePhytoEnvelope;
    }
    /**
     * Set ePhytoEnvelope value
     * @param \StructType\EPhytoEnvelope $ePhytoEnvelope
     * @return \StructType\DeliverPhytoEnvelope
     */
    public function setEPhytoEnvelope(\StructType\EPhytoEnvelope $ePhytoEnvelope = null)
    {
        $this->ePhytoEnvelope = $ePhytoEnvelope;
        return $this;
    }
}
