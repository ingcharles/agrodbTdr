<?php

namespace ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Deliver ServiceType
 * @subpackage Services
 */
class Deliver extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named DeliverCountryResponseEnvelope
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\DeliverCountryResponseEnvelope $parameters
     * @return \StructType\DeliverCountryResponseEnvelopeResponse|bool
     */
    public function DeliverCountryResponseEnvelope(\StructType\DeliverCountryResponseEnvelope $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('DeliverCountryResponseEnvelope', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named DeliverPhytoEnvelope
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\DeliverPhytoEnvelope $parameters
     * @return \StructType\DeliverPhytoEnvelopeResponse|bool
     */
    public function DeliverPhytoEnvelope(\StructType\DeliverPhytoEnvelope $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('DeliverPhytoEnvelope', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named DeliverEnvelope
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\DeliverEnvelope $parameters
     * @return \StructType\DeliverEnvelopeResponse|bool
     */
    public function DeliverEnvelope(\StructType\DeliverEnvelope $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('DeliverEnvelope', array(
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
     * @return \StructType\DeliverCountryResponseEnvelopeResponse|\StructType\DeliverEnvelopeResponse|\StructType\DeliverPhytoEnvelopeResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
