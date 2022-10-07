<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for DeliverEnvelope StructType
 * Meta information extracted from the WSDL
 * - type: tns:DeliverEnvelope
 * @subpackage Structs
 */
class DeliverEnvelope extends AbstractStructBase
{
    /**
     * The env
     * @var \StructType\Envelope
     */
    public $env;
    /**
     * Constructor method for DeliverEnvelope
     * @uses DeliverEnvelope::setEnv()
     * @param \StructType\Envelope $env
     */
    public function __construct(\StructType\Envelope $env = null)
    {
        $this
            ->setEnv($env);
    }
    /**
     * Get env value
     * @return \StructType\Envelope|null
     */
    public function getEnv()
    {
        return $this->env;
    }
    /**
     * Set env value
     * @param \StructType\Envelope $env
     * @return \StructType\DeliverEnvelope
     */
    public function setEnv(\StructType\Envelope $env = null)
    {
        $this->env = $env;
        return $this;
    }
}
