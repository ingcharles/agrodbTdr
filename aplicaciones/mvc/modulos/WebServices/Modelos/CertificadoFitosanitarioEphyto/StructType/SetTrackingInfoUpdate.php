<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for SetTrackingInfoUpdate StructType
 * Meta information extracted from the WSDL
 * - type: tns:SetTrackingInfoUpdate
 * @subpackage Structs
 */
class SetTrackingInfoUpdate extends AbstractStructBase
{
    /**
     * The header
     * @var \StructType\EnvelopeHeader
     */
    public $header;
    /**
     * Constructor method for SetTrackingInfoUpdate
     * @uses SetTrackingInfoUpdate::setHeader()
     * @param \StructType\EnvelopeHeader $header
     */
    public function __construct(\StructType\EnvelopeHeader $header = null)
    {
        $this
            ->setHeader($header);
    }
    /**
     * Get header value
     * @return \StructType\EnvelopeHeader|null
     */
    public function getHeader()
    {
        return $this->header;
    }
    /**
     * Set header value
     * @param \StructType\EnvelopeHeader $header
     * @return \StructType\SetTrackingInfoUpdate
     */
    public function setHeader(\StructType\EnvelopeHeader $header = null)
    {
        $this->header = $header;
        return $this;
    }
}
