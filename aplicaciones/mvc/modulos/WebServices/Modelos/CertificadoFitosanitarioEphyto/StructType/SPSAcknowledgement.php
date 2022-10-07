<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for SPSAcknowledgement StructType
 * Meta information extracted from the WSDL
 * - type: ns1:SPSAcknowledgement
 * @subpackage Structs
 */
class SPSAcknowledgement extends AbstractStructBase
{
    /**
     * The SPSAcknowledgementDocument
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns2:SPSAcknowledgementDocument
     * @var \StructType\SpsAcknowledgementDocument
     */
    public $SPSAcknowledgementDocument;
    /**
     * Constructor method for SPSAcknowledgement
     * @uses SPSAcknowledgement::setSPSAcknowledgementDocument()
     * @param \StructType\SpsAcknowledgementDocument $sPSAcknowledgementDocument
     */
    public function __construct(\StructType\SpsAcknowledgementDocument $sPSAcknowledgementDocument = null)
    {
        $this
            ->setSPSAcknowledgementDocument($sPSAcknowledgementDocument);
    }
    /**
     * Get SPSAcknowledgementDocument value
     * @return \StructType\SpsAcknowledgementDocument|null
     */
    public function getSPSAcknowledgementDocument()
    {
        return $this->SPSAcknowledgementDocument;
    }
    /**
     * Set SPSAcknowledgementDocument value
     * @param \StructType\SpsAcknowledgementDocument $sPSAcknowledgementDocument
     * @return \StructType\SPSAcknowledgement
     */
    public function setSPSAcknowledgementDocument(\StructType\SpsAcknowledgementDocument $sPSAcknowledgementDocument = null)
    {
        $this->SPSAcknowledgementDocument = $sPSAcknowledgementDocument;
        return $this;
    }
}
