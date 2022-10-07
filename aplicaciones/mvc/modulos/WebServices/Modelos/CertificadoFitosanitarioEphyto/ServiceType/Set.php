<?php

namespace ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Set ServiceType
 * @subpackage Services
 */
class Set extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named SetTrackingInfoUpdate
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\SetTrackingInfoUpdate $parameters
     * @return \StructType\SetTrackingInfoUpdateResponse|bool
     */
    public function SetTrackingInfoUpdate(\StructType\SetTrackingInfoUpdate $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('SetTrackingInfoUpdate', array(
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
     * @return \StructType\SetTrackingInfoUpdateResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
