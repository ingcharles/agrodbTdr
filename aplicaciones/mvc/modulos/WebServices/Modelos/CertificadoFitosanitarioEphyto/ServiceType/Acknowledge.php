<?php

namespace ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Acknowledge ServiceType
 * @subpackage Services
 */
class Acknowledge extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named AcknowledgeFailedEnvelopeReceipt
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\AcknowledgeFailedEnvelopeReceipt $parameters
     * @return \StructType\AcknowledgeFailedEnvelopeReceiptResponse|bool
     */
    public function AcknowledgeFailedEnvelopeReceipt(\StructType\AcknowledgeFailedEnvelopeReceipt $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('AcknowledgeFailedEnvelopeReceipt', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named AcknowledgeEnvelopeReceipt
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\AcknowledgeEnvelopeReceipt $parameters
     * @return \StructType\AcknowledgeEnvelopeReceiptResponse|bool
     */
    public function AcknowledgeEnvelopeReceipt(\StructType\AcknowledgeEnvelopeReceipt $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('AcknowledgeEnvelopeReceipt', array(
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
     * @return \StructType\AcknowledgeEnvelopeReceiptResponse|\StructType\AcknowledgeFailedEnvelopeReceiptResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
