<?php

namespace ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for PULLI ServiceType
 * @subpackage Services
 */
class PULLI extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named PULLImportEnvelope
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\PULLImportEnvelope $parameters
     * @return \StructType\PULLImportEnvelopeResponse|bool
     */
    public function PULLImportEnvelope(\StructType\PULLImportEnvelope $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('PULLImportEnvelope', array(
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
     * @return \StructType\PULLImportEnvelopeResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
