<?php

namespace ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for PULLS ServiceType
 * @subpackage Services
 */
class PULLS extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named PULLSingleImportEnvelope
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\PULLSingleImportEnvelope $parameters
     * @return \StructType\PULLSingleImportEnvelopeResponse|bool
     */
    public function PULLSingleImportEnvelope(\StructType\PULLSingleImportEnvelope $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('PULLSingleImportEnvelope', array(
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
     * @return \StructType\PULLSingleImportEnvelopeResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
