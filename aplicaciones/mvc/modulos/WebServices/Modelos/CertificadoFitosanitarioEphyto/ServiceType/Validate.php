<?php

namespace ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Validate ServiceType
 * @subpackage Services
 */
class Validate extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named ValidateAndDeliverEnvelope
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\ValidateAndDeliverEnvelope $parameters
     * @return \StructType\ValidateAndDeliverEnvelopeResponse|bool
     */
    public function ValidateAndDeliverEnvelope(\StructType\ValidateAndDeliverEnvelope $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('ValidateAndDeliverEnvelope', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named ValidatePhytoXML
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\ValidatePhytoXML $parameters
     * @return \StructType\ValidatePhytoXMLResponse|bool
     */
    public function ValidatePhytoXML(\StructType\ValidatePhytoXML $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('ValidatePhytoXML', array(
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
     * @return \StructType\ValidateAndDeliverEnvelopeResponse|\StructType\ValidatePhytoXMLResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
