<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for signatorySPSAuthentication StructType
 * @subpackage Structs
 */
class SignatorySPSAuthentication extends AbstractStructBase
{
    /**
     * The ActualDateTime
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:ActualDateTime
     * @var \StructType\ActualDateTime
     */
    public $ActualDateTime;
    /**
     * The IssueSPSLocation
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:IssueSPSLocation
     * @var \StructType\IssueSPSLocation
     */
    public $IssueSPSLocation;
    /**
     * The ProviderSPSParty
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:ProviderSPSParty
     * @var \StructType\ProviderSPSParty
     */
    public $ProviderSPSParty;
    /**
     * The IncludedSPSClause
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: ns3:IncludedSPSClause
     * @var \StructType\IncludedSPSClause[]
     */
    public $IncludedSPSClause;
    /**
     * Constructor method for signatorySPSAuthentication
     * @uses SignatorySPSAuthentication::setActualDateTime()
     * @uses SignatorySPSAuthentication::setIssueSPSLocation()
     * @uses SignatorySPSAuthentication::setProviderSPSParty()
     * @uses SignatorySPSAuthentication::setIncludedSPSClause()
     * @param \StructType\ActualDateTime $actualDateTime
     * @param \StructType\IssueSPSLocation $issueSPSLocation
     * @param \StructType\ProviderSPSParty $providerSPSParty
     * @param \StructType\IncludedSPSClause[] $includedSPSClause
     */
    public function __construct(\StructType\ActualDateTime $actualDateTime = null, \StructType\IssueSPSLocation $issueSPSLocation = null, \StructType\ProviderSPSParty $providerSPSParty = null, array $includedSPSClause = array())
    {
        $this
            ->setActualDateTime($actualDateTime)
            ->setIssueSPSLocation($issueSPSLocation)
            ->setProviderSPSParty($providerSPSParty)
            ->setIncludedSPSClause($includedSPSClause);
    }
    /**
     * Get ActualDateTime value
     * @return \StructType\ActualDateTime|null
     */
    public function getActualDateTime()
    {
        return $this->ActualDateTime;
    }
    /**
     * Set ActualDateTime value
     * @param \StructType\ActualDateTime $actualDateTime
     * @return \StructType\SignatorySPSAuthentication
     */
    public function setActualDateTime(\StructType\ActualDateTime $actualDateTime = null)
    {
        $this->ActualDateTime = $actualDateTime;
        return $this;
    }
    /**
     * Get IssueSPSLocation value
     * @return \StructType\IssueSPSLocation|null
     */
    public function getIssueSPSLocation()
    {
        return $this->IssueSPSLocation;
    }
    /**
     * Set IssueSPSLocation value
     * @param \StructType\IssueSPSLocation $issueSPSLocation
     * @return \StructType\SignatorySPSAuthentication
     */
    public function setIssueSPSLocation(\StructType\IssueSPSLocation $issueSPSLocation = null)
    {
        $this->IssueSPSLocation = $issueSPSLocation;
        return $this;
    }
    /**
     * Get ProviderSPSParty value
     * @return \StructType\ProviderSPSParty|null
     */
    public function getProviderSPSParty()
    {
        return $this->ProviderSPSParty;
    }
    /**
     * Set ProviderSPSParty value
     * @param \StructType\ProviderSPSParty $providerSPSParty
     * @return \StructType\SignatorySPSAuthentication
     */
    public function setProviderSPSParty(\StructType\ProviderSPSParty $providerSPSParty = null)
    {
        $this->ProviderSPSParty = $providerSPSParty;
        return $this;
    }
    /**
     * Get IncludedSPSClause value
     * @return \StructType\IncludedSPSClause[]|null
     */
    public function getIncludedSPSClause()
    {
        return $this->IncludedSPSClause;
    }
    /**
     * This method is responsible for validating the values passed to the setIncludedSPSClause method
     * This method is willingly generated in order to preserve the one-line inline validation within the setIncludedSPSClause method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateIncludedSPSClauseForArrayConstraintsFromSetIncludedSPSClause(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $signatorySPSAuthenticationIncludedSPSClauseItem) {
            // validation for constraint: itemType
            if (!$signatorySPSAuthenticationIncludedSPSClauseItem instanceof \StructType\IncludedSPSClause) {
                $invalidValues[] = is_object($signatorySPSAuthenticationIncludedSPSClauseItem) ? get_class($signatorySPSAuthenticationIncludedSPSClauseItem) : sprintf('%s(%s)', gettype($signatorySPSAuthenticationIncludedSPSClauseItem), var_export($signatorySPSAuthenticationIncludedSPSClauseItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The IncludedSPSClause property can only contain items of type \StructType\IncludedSPSClause, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set IncludedSPSClause value
     * @throws \InvalidArgumentException
     * @param \StructType\IncludedSPSClause[] $includedSPSClause
     * @return \StructType\SignatorySPSAuthentication
     */
    public function setIncludedSPSClause(array $includedSPSClause = array())
    {
        // validation for constraint: array
        if ('' !== ($includedSPSClauseArrayErrorMessage = self::validateIncludedSPSClauseForArrayConstraintsFromSetIncludedSPSClause($includedSPSClause))) {
            throw new \InvalidArgumentException($includedSPSClauseArrayErrorMessage, __LINE__);
        }
        $this->IncludedSPSClause = $includedSPSClause;
        return $this;
    }
    /**
     * Add item to IncludedSPSClause value
     * @throws \InvalidArgumentException
     * @param \StructType\IncludedSPSClause $item
     * @return \StructType\SignatorySPSAuthentication
     */
    public function addToIncludedSPSClause(\StructType\IncludedSPSClause $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\IncludedSPSClause) {
            throw new \InvalidArgumentException(sprintf('The IncludedSPSClause property can only contain items of type \StructType\IncludedSPSClause, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->IncludedSPSClause[] = $item;
        return $this;
    }
}
