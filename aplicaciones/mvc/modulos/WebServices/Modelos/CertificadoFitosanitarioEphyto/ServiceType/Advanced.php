<?php

namespace ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Advanced ServiceType
 * @subpackage Services
 */
class Advanced extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named AdvancedAcknowledgeEnvelopeReceipt
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\AdvancedAcknowledgeEnvelopeReceipt $parameters
     * @return \StructType\AdvancedAcknowledgeEnvelopeReceiptResponse|bool
     */
    public function AdvancedAcknowledgeEnvelopeReceipt(\StructType\AdvancedAcknowledgeEnvelopeReceipt $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('AdvancedAcknowledgeEnvelopeReceipt', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Returns the result
     * @see AbstractSoapClientBase::getResult()
     * @return \StructType\AdvancedAcknowledgeEnvelopeReceiptResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
