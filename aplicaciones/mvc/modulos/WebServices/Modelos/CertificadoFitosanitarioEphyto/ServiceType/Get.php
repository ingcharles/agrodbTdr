<?php

namespace ServiceType;

use \WsdlToPhp\PackageBase\AbstractSoapClientBase;

/**
 * This class stands for Get ServiceType
 * @subpackage Services
 */
class Get extends AbstractSoapClientBase
{
    /**
     * Method to call the operation originally named GetTreatmentTypes
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetTreatmentTypes $parameters
     * @return \StructType\GetTreatmentTypesResponse|bool
     */
    public function GetTreatmentTypes(\StructType\GetTreatmentTypes $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetTreatmentTypes', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetAdditionalDeclarations
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetAdditionalDeclarations $parameters
     * @return \StructType\GetAdditionalDeclarationsResponse|bool
     */
    public function GetAdditionalDeclarations(\StructType\GetAdditionalDeclarations $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetAdditionalDeclarations', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetActiveNppos
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetActiveNppos $parameters
     * @return \StructType\GetActiveNpposResponse|bool
     */
    public function GetActiveNppos(\StructType\GetActiveNppos $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetActiveNppos', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetImportEnvelopeHeaders
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetImportEnvelopeHeaders $parameters
     * @return \StructType\GetImportEnvelopeHeadersResponse|bool
     */
    public function GetImportEnvelopeHeaders(\StructType\GetImportEnvelopeHeaders $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetImportEnvelopeHeaders', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetIntendedUses
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetIntendedUses $parameters
     * @return \StructType\GetIntendedUsesResponse|bool
     */
    public function GetIntendedUses(\StructType\GetIntendedUses $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetIntendedUses', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetPesticides
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetPesticides $parameters
     * @return \StructType\GetPesticidesResponse|bool
     */
    public function GetPesticides(\StructType\GetPesticides $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetPesticides', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetEnvelopeTrackingInfo
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetEnvelopeTrackingInfo $parameters
     * @return \StructType\GetEnvelopeTrackingInfoResponse|bool
     */
    public function GetEnvelopeTrackingInfo(\StructType\GetEnvelopeTrackingInfo $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetEnvelopeTrackingInfo', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetProfile
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetProfile $parameters
     * @return \StructType\GetProfileResponse|bool
     */
    public function GetProfile(\StructType\GetProfile $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetProfile', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetProductDescriptions
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetProductDescriptions $parameters
     * @return \StructType\GetProductDescriptionsResponse|bool
     */
    public function GetProductDescriptions(\StructType\GetProductDescriptions $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetProductDescriptions', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetUnitMeasures
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetUnitMeasures $parameters
     * @return \StructType\GetUnitMeasuresResponse|bool
     */
    public function GetUnitMeasures(\StructType\GetUnitMeasures $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetUnitMeasures', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetMeanOfTransports
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetMeanOfTransports $parameters
     * @return \StructType\GetMeanOfTransportsResponse|bool
     */
    public function GetMeanOfTransports(\StructType\GetMeanOfTransports $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetMeanOfTransports', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetStatements
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetStatements $parameters
     * @return \StructType\GetStatementsResponse|bool
     */
    public function GetStatements(\StructType\GetStatements $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetStatements', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetUnderDeliveryEnvelope
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetUnderDeliveryEnvelope $parameters
     * @return \StructType\GetUnderDeliveryEnvelopeResponse|bool
     */
    public function GetUnderDeliveryEnvelope(\StructType\GetUnderDeliveryEnvelope $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetUnderDeliveryEnvelope', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetConditions
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetConditions $parameters
     * @return \StructType\GetConditionsResponse|bool
     */
    public function GetConditions(\StructType\GetConditions $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetConditions', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetAvailableChannels
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetAvailableChannels $parameters
     * @return \StructType\GetAvailableChannelsResponse|bool
     */
    public function GetAvailableChannels(\StructType\GetAvailableChannels $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetAvailableChannels', array(
                $parameters,
            ), array(), array(), $this->outputHeaders));
            return $this->getResult();
        } catch (\SoapFault $soapFault) {
            $this->saveLastError(__METHOD__, $soapFault);
            return false;
        }
    }
    /**
     * Method to call the operation originally named GetPackageTypes
     * @uses AbstractSoapClientBase::getSoapClient()
     * @uses AbstractSoapClientBase::setResult()
     * @uses AbstractSoapClientBase::getResult()
     * @uses AbstractSoapClientBase::saveLastError()
     * @param \StructType\GetPackageTypes $parameters
     * @return \StructType\GetPackageTypesResponse|bool
     */
    public function GetPackageTypes(\StructType\GetPackageTypes $parameters)
    {
        try {
            $this->setResult($this->getSoapClient()->__soapCall('GetPackageTypes', array(
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
     * @return \StructType\GetActiveNpposResponse|\StructType\GetAdditionalDeclarationsResponse|\StructType\GetAvailableChannelsResponse|\StructType\GetConditionsResponse|\StructType\GetEnvelopeTrackingInfoResponse|\StructType\GetImportEnvelopeHeadersResponse|\StructType\GetIntendedUsesResponse|\StructType\GetMeanOfTransportsResponse|\StructType\GetPackageTypesResponse|\StructType\GetPesticidesResponse|\StructType\GetProductDescriptionsResponse|\StructType\GetProfileResponse|\StructType\GetStatementsResponse|\StructType\GetTreatmentTypesResponse|\StructType\GetUnderDeliveryEnvelopeResponse|\StructType\GetUnitMeasuresResponse
     */
    public function getResult()
    {
        return parent::getResult();
    }
}
