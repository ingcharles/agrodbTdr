<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for SPSCertificate StructType
 * Meta information extracted from the WSDL
 * - type: ns1:SPSCertificate
 * @subpackage Structs
 */
class SPSCertificate extends AbstractStructBase
{
    /**
     * The SPSExchangedDocument
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns5:SPSExchangedDocument
     * @var \StructType\SpsExchangedDocument
     */
    public $SPSExchangedDocument;
    /**
     * The SPSConsignment
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns5:SPSConsignment
     * @var \StructType\SpsConsignment
     */
    public $SPSConsignment;
    /**
     * Constructor method for SPSCertificate
     * @uses SPSCertificate::setSPSExchangedDocument()
     * @uses SPSCertificate::setSPSConsignment()
     * @param \StructType\SpsExchangedDocument $sPSExchangedDocument
     * @param \StructType\SpsConsignment $sPSConsignment
     */
    public function __construct(\StructType\SpsExchangedDocument $sPSExchangedDocument = null, \StructType\SpsConsignment $sPSConsignment = null)
    {
        $this
            ->setSPSExchangedDocument($sPSExchangedDocument)
            ->setSPSConsignment($sPSConsignment);
    }
    /**
     * Get SPSExchangedDocument value
     * @return \StructType\SpsExchangedDocument|null
     */
    public function getSPSExchangedDocument()
    {
        return $this->SPSExchangedDocument;
    }
    /**
     * Set SPSExchangedDocument value
     * @param \StructType\SpsExchangedDocument $sPSExchangedDocument
     * @return \StructType\SPSCertificate
     */
    public function setSPSExchangedDocument(\StructType\SpsExchangedDocument $sPSExchangedDocument = null)
    {
        $this->SPSExchangedDocument = $sPSExchangedDocument;
        return $this;
    }
    /**
     * Get SPSConsignment value
     * @return \StructType\SpsConsignment|null
     */
    public function getSPSConsignment()
    {
        return $this->SPSConsignment;
    }
    /**
     * Set SPSConsignment value
     * @param \StructType\SpsConsignment $sPSConsignment
     * @return \StructType\SPSCertificate
     */
    public function setSPSConsignment(\StructType\SpsConsignment $sPSConsignment = null)
    {
        $this->SPSConsignment = $sPSConsignment;
        return $this;
    }
}
