<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for spsConsignment StructType
 * @subpackage Structs
 */
class SpsConsignment extends AbstractStructBase
{
    /**
     * The ConsignorSPSParty
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:ConsignorSPSParty
     * @var \StructType\ConsignorSPSParty
     */
    public $ConsignorSPSParty;
    /**
     * The ConsigneeSPSParty
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:ConsigneeSPSParty
     * @var \StructType\ConsigneeSPSParty
     */
    public $ConsigneeSPSParty;
    /**
     * The ExportSPSCountry
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\ExportSPSCountry
     */
    public $ExportSPSCountry;
    /**
     * The ImportSPSCountry
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\ImportSPSCountry
     */
    public $ImportSPSCountry;
    /**
     * The TransitSPSCountry
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: tns:TransitSPSCountry
     * @var \StructType\TransitSPSCountry[]
     */
    public $TransitSPSCountry;
    /**
     * The UnloadingBaseportSPSLocation
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:UnloadingBaseportSPSLocation
     * @var \StructType\UnloadingBaseportSPSLocation
     */
    public $UnloadingBaseportSPSLocation;
    /**
     * The ExaminationSPSEvent
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\ExaminationSPSEvent
     */
    public $ExaminationSPSEvent;
    /**
     * The MainCarriageSPSTransportMovement
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\MainCarriageSPSTransportMovement[]
     */
    public $MainCarriageSPSTransportMovement;
    /**
     * The UtilizedSPSTransportEquipment
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: tns:UtilizedSPSTransportEquipment
     * @var \StructType\UtilizedSPSTransportEquipment
     */
    public $UtilizedSPSTransportEquipment;
    /**
     * The IncludedSPSConsignmentItem
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: tns:IncludedSPSConsignmentItem
     * @var \StructType\IncludedSPSConsignmentItem[]
     */
    public $IncludedSPSConsignmentItem;
    /**
     * Constructor method for spsConsignment
     * @uses SpsConsignment::setConsignorSPSParty()
     * @uses SpsConsignment::setConsigneeSPSParty()
     * @uses SpsConsignment::setExportSPSCountry()
     * @uses SpsConsignment::setImportSPSCountry()
     * @uses SpsConsignment::setTransitSPSCountry()
     * @uses SpsConsignment::setUnloadingBaseportSPSLocation()
     * @uses SpsConsignment::setExaminationSPSEvent()
     * @uses SpsConsignment::setMainCarriageSPSTransportMovement()
     * @uses SpsConsignment::setUtilizedSPSTransportEquipment()
     * @uses SpsConsignment::setIncludedSPSConsignmentItem()
     * @param \StructType\ConsignorSPSParty $consignorSPSParty
     * @param \StructType\ConsigneeSPSParty $consigneeSPSParty
     * @param \StructType\ExportSPSCountry $exportSPSCountry
     * @param \StructType\ImportSPSCountry $importSPSCountry
     * @param \StructType\TransitSPSCountry[] $transitSPSCountry
     * @param \StructType\UnloadingBaseportSPSLocation $unloadingBaseportSPSLocation
     * @param \StructType\ExaminationSPSEvent $examinationSPSEvent
     * @param \StructType\MainCarriageSPSTransportMovement[] $mainCarriageSPSTransportMovement
     * @param \StructType\UtilizedSPSTransportEquipment $utilizedSPSTransportEquipment
     * @param \StructType\IncludedSPSConsignmentItem[] $includedSPSConsignmentItem
     */
    public function __construct(\StructType\ConsignorSPSParty $consignorSPSParty = null, \StructType\ConsigneeSPSParty $consigneeSPSParty = null, \StructType\ExportSPSCountry $exportSPSCountry = null, \StructType\ImportSPSCountry $importSPSCountry = null, array $transitSPSCountry = array(), \StructType\UnloadingBaseportSPSLocation $unloadingBaseportSPSLocation = null, \StructType\ExaminationSPSEvent $examinationSPSEvent = null, array $mainCarriageSPSTransportMovement = array(), \StructType\UtilizedSPSTransportEquipment $utilizedSPSTransportEquipment = null, array $includedSPSConsignmentItem = array())
    {
        $this
            ->setConsignorSPSParty($consignorSPSParty)
            ->setConsigneeSPSParty($consigneeSPSParty)
            ->setExportSPSCountry($exportSPSCountry)
            ->setImportSPSCountry($importSPSCountry)
            ->setTransitSPSCountry($transitSPSCountry)
            ->setUnloadingBaseportSPSLocation($unloadingBaseportSPSLocation)
            ->setExaminationSPSEvent($examinationSPSEvent)
            ->setMainCarriageSPSTransportMovement($mainCarriageSPSTransportMovement)
            ->setUtilizedSPSTransportEquipment($utilizedSPSTransportEquipment)
            ->setIncludedSPSConsignmentItem($includedSPSConsignmentItem);
    }
    /**
     * Get ConsignorSPSParty value
     * @return \StructType\ConsignorSPSParty|null
     */
    public function getConsignorSPSParty()
    {
        return $this->ConsignorSPSParty;
    }
    /**
     * Set ConsignorSPSParty value
     * @param \StructType\ConsignorSPSParty $consignorSPSParty
     * @return \StructType\SpsConsignment
     */
    public function setConsignorSPSParty(\StructType\ConsignorSPSParty $consignorSPSParty = null)
    {
        $this->ConsignorSPSParty = $consignorSPSParty;
        return $this;
    }
    /**
     * Get ConsigneeSPSParty value
     * @return \StructType\ConsigneeSPSParty|null
     */
    public function getConsigneeSPSParty()
    {
        return $this->ConsigneeSPSParty;
    }
    /**
     * Set ConsigneeSPSParty value
     * @param \StructType\ConsigneeSPSParty $consigneeSPSParty
     * @return \StructType\SpsConsignment
     */
    public function setConsigneeSPSParty(\StructType\ConsigneeSPSParty $consigneeSPSParty = null)
    {
        $this->ConsigneeSPSParty = $consigneeSPSParty;
        return $this;
    }
    /**
     * Get ExportSPSCountry value
     * @return \StructType\ExportSPSCountry|null
     */
    public function getExportSPSCountry()
    {
        return $this->ExportSPSCountry;
    }
    /**
     * Set ExportSPSCountry value
     * @param \StructType\ExportSPSCountry $exportSPSCountry
     * @return \StructType\SpsConsignment
     */
    public function setExportSPSCountry(\StructType\ExportSPSCountry $exportSPSCountry = null)
    {
        $this->ExportSPSCountry = $exportSPSCountry;
        return $this;
    }
    /**
     * Get ImportSPSCountry value
     * @return \StructType\ImportSPSCountry|null
     */
    public function getImportSPSCountry()
    {
        return $this->ImportSPSCountry;
    }
    /**
     * Set ImportSPSCountry value
     * @param \StructType\ImportSPSCountry $importSPSCountry
     * @return \StructType\SpsConsignment
     */
    public function setImportSPSCountry(\StructType\ImportSPSCountry $importSPSCountry = null)
    {
        $this->ImportSPSCountry = $importSPSCountry;
        return $this;
    }
    /**
     * Get TransitSPSCountry value
     * @return \StructType\TransitSPSCountry[]|null
     */
    public function getTransitSPSCountry()
    {
        return $this->TransitSPSCountry;
    }
    /**
     * This method is responsible for validating the values passed to the setTransitSPSCountry method
     * This method is willingly generated in order to preserve the one-line inline validation within the setTransitSPSCountry method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateTransitSPSCountryForArrayConstraintsFromSetTransitSPSCountry(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $spsConsignmentTransitSPSCountryItem) {
            // validation for constraint: itemType
            if (!$spsConsignmentTransitSPSCountryItem instanceof \StructType\TransitSPSCountry) {
                $invalidValues[] = is_object($spsConsignmentTransitSPSCountryItem) ? get_class($spsConsignmentTransitSPSCountryItem) : sprintf('%s(%s)', gettype($spsConsignmentTransitSPSCountryItem), var_export($spsConsignmentTransitSPSCountryItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The TransitSPSCountry property can only contain items of type \StructType\TransitSPSCountry, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set TransitSPSCountry value
     * @throws \InvalidArgumentException
     * @param \StructType\TransitSPSCountry[] $transitSPSCountry
     * @return \StructType\SpsConsignment
     */
    public function setTransitSPSCountry(array $transitSPSCountry = array())
    {
        // validation for constraint: array
        if ('' !== ($transitSPSCountryArrayErrorMessage = self::validateTransitSPSCountryForArrayConstraintsFromSetTransitSPSCountry($transitSPSCountry))) {
            throw new \InvalidArgumentException($transitSPSCountryArrayErrorMessage, __LINE__);
        }
        $this->TransitSPSCountry = $transitSPSCountry;
        return $this;
    }
    /**
     * Add item to TransitSPSCountry value
     * @throws \InvalidArgumentException
     * @param \StructType\TransitSPSCountry $item
     * @return \StructType\SpsConsignment
     */
    public function addToTransitSPSCountry(\StructType\TransitSPSCountry $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\TransitSPSCountry) {
            throw new \InvalidArgumentException(sprintf('The TransitSPSCountry property can only contain items of type \StructType\TransitSPSCountry, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->TransitSPSCountry[] = $item;
        return $this;
    }
    /**
     * Get UnloadingBaseportSPSLocation value
     * @return \StructType\UnloadingBaseportSPSLocation|null
     */
    public function getUnloadingBaseportSPSLocation()
    {
        return $this->UnloadingBaseportSPSLocation;
    }
    /**
     * Set UnloadingBaseportSPSLocation value
     * @param \StructType\UnloadingBaseportSPSLocation $unloadingBaseportSPSLocation
     * @return \StructType\SpsConsignment
     */
    public function setUnloadingBaseportSPSLocation(\StructType\UnloadingBaseportSPSLocation $unloadingBaseportSPSLocation = null)
    {
        $this->UnloadingBaseportSPSLocation = $unloadingBaseportSPSLocation;
        return $this;
    }
    /**
     * Get ExaminationSPSEvent value
     * @return \StructType\ExaminationSPSEvent|null
     */
    public function getExaminationSPSEvent()
    {
        return $this->ExaminationSPSEvent;
    }
    /**
     * Set ExaminationSPSEvent value
     * @param \StructType\ExaminationSPSEvent $examinationSPSEvent
     * @return \StructType\SpsConsignment
     */
    public function setExaminationSPSEvent(\StructType\ExaminationSPSEvent $examinationSPSEvent = null)
    {
        $this->ExaminationSPSEvent = $examinationSPSEvent;
        return $this;
    }
    /**
     * Get MainCarriageSPSTransportMovement value
     * @return \StructType\MainCarriageSPSTransportMovement[]|null
     */
    public function getMainCarriageSPSTransportMovement()
    {
        return $this->MainCarriageSPSTransportMovement;
    }
    /**
     * This method is responsible for validating the values passed to the setMainCarriageSPSTransportMovement method
     * This method is willingly generated in order to preserve the one-line inline validation within the setMainCarriageSPSTransportMovement method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateMainCarriageSPSTransportMovementForArrayConstraintsFromSetMainCarriageSPSTransportMovement(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $spsConsignmentMainCarriageSPSTransportMovementItem) {
            // validation for constraint: itemType
            if (!$spsConsignmentMainCarriageSPSTransportMovementItem instanceof \StructType\MainCarriageSPSTransportMovement) {
                $invalidValues[] = is_object($spsConsignmentMainCarriageSPSTransportMovementItem) ? get_class($spsConsignmentMainCarriageSPSTransportMovementItem) : sprintf('%s(%s)', gettype($spsConsignmentMainCarriageSPSTransportMovementItem), var_export($spsConsignmentMainCarriageSPSTransportMovementItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The MainCarriageSPSTransportMovement property can only contain items of type \StructType\MainCarriageSPSTransportMovement, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set MainCarriageSPSTransportMovement value
     * @throws \InvalidArgumentException
     * @param \StructType\MainCarriageSPSTransportMovement[] $mainCarriageSPSTransportMovement
     * @return \StructType\SpsConsignment
     */
    public function setMainCarriageSPSTransportMovement(array $mainCarriageSPSTransportMovement = array())
    {
        // validation for constraint: array
        if ('' !== ($mainCarriageSPSTransportMovementArrayErrorMessage = self::validateMainCarriageSPSTransportMovementForArrayConstraintsFromSetMainCarriageSPSTransportMovement($mainCarriageSPSTransportMovement))) {
            throw new \InvalidArgumentException($mainCarriageSPSTransportMovementArrayErrorMessage, __LINE__);
        }
        $this->MainCarriageSPSTransportMovement = $mainCarriageSPSTransportMovement;
        return $this;
    }
    /**
     * Add item to MainCarriageSPSTransportMovement value
     * @throws \InvalidArgumentException
     * @param \StructType\MainCarriageSPSTransportMovement $item
     * @return \StructType\SpsConsignment
     */
    public function addToMainCarriageSPSTransportMovement(\StructType\MainCarriageSPSTransportMovement $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\MainCarriageSPSTransportMovement) {
            throw new \InvalidArgumentException(sprintf('The MainCarriageSPSTransportMovement property can only contain items of type \StructType\MainCarriageSPSTransportMovement, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->MainCarriageSPSTransportMovement[] = $item;
        return $this;
    }
    /**
     * Get UtilizedSPSTransportEquipment value
     * @return \StructType\UtilizedSPSTransportEquipment|null
     */
    public function getUtilizedSPSTransportEquipment()
    {
        return $this->UtilizedSPSTransportEquipment;
    }
    /**
     * Set UtilizedSPSTransportEquipment value
     * @param \StructType\UtilizedSPSTransportEquipment $utilizedSPSTransportEquipment
     * @return \StructType\SpsConsignment
     */
    public function setUtilizedSPSTransportEquipment(\StructType\UtilizedSPSTransportEquipment $utilizedSPSTransportEquipment = null)
    {
        $this->UtilizedSPSTransportEquipment = $utilizedSPSTransportEquipment;
        return $this;
    }
    /**
     * Get IncludedSPSConsignmentItem value
     * @return \StructType\IncludedSPSConsignmentItem[]|null
     */
    public function getIncludedSPSConsignmentItem()
    {
        return $this->IncludedSPSConsignmentItem;
    }
    /**
     * This method is responsible for validating the values passed to the setIncludedSPSConsignmentItem method
     * This method is willingly generated in order to preserve the one-line inline validation within the setIncludedSPSConsignmentItem method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateIncludedSPSConsignmentItemForArrayConstraintsFromSetIncludedSPSConsignmentItem(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $spsConsignmentIncludedSPSConsignmentItemItem) {
            // validation for constraint: itemType
            if (!$spsConsignmentIncludedSPSConsignmentItemItem instanceof \StructType\IncludedSPSConsignmentItem) {
                $invalidValues[] = is_object($spsConsignmentIncludedSPSConsignmentItemItem) ? get_class($spsConsignmentIncludedSPSConsignmentItemItem) : sprintf('%s(%s)', gettype($spsConsignmentIncludedSPSConsignmentItemItem), var_export($spsConsignmentIncludedSPSConsignmentItemItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The IncludedSPSConsignmentItem property can only contain items of type \StructType\IncludedSPSConsignmentItem, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set IncludedSPSConsignmentItem value
     * @throws \InvalidArgumentException
     * @param \StructType\IncludedSPSConsignmentItem[] $includedSPSConsignmentItem
     * @return \StructType\SpsConsignment
     */
    public function setIncludedSPSConsignmentItem(array $includedSPSConsignmentItem = array())
    {
        // validation for constraint: array
        if ('' !== ($includedSPSConsignmentItemArrayErrorMessage = self::validateIncludedSPSConsignmentItemForArrayConstraintsFromSetIncludedSPSConsignmentItem($includedSPSConsignmentItem))) {
            throw new \InvalidArgumentException($includedSPSConsignmentItemArrayErrorMessage, __LINE__);
        }
        $this->IncludedSPSConsignmentItem = $includedSPSConsignmentItem;
        return $this;
    }
    /**
     * Add item to IncludedSPSConsignmentItem value
     * @throws \InvalidArgumentException
     * @param \StructType\IncludedSPSConsignmentItem $item
     * @return \StructType\SpsConsignment
     */
    public function addToIncludedSPSConsignmentItem(\StructType\IncludedSPSConsignmentItem $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\IncludedSPSConsignmentItem) {
            throw new \InvalidArgumentException(sprintf('The IncludedSPSConsignmentItem property can only contain items of type \StructType\IncludedSPSConsignmentItem, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->IncludedSPSConsignmentItem[] = $item;
        return $this;
    }
}
