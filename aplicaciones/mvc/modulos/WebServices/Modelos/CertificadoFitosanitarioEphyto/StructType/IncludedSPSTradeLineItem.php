<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for IncludedSPSTradeLineItem StructType
 * Meta information extracted from the WSDL
 * - type: tns:IncludedSPSTradeLineItem
 * @subpackage Structs
 */
class IncludedSPSTradeLineItem extends AbstractStructBase
{
    /**
     * The SequenceNumeric
     * Meta information extracted from the WSDL
     * - form: qualified
     * @var int
     */
    public $SequenceNumeric;
    /**
     * The Description
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\TextType[]
     */
    public $Description;
    /**
     * The CommonName
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\TextType[]
     */
    public $CommonName;
    /**
     * The ScientificName
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\TextType[]
     */
    public $ScientificName;
    /**
     * The IntendedUse
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\TextType[]
     */
    public $IntendedUse;
    /**
     * The NetWeightMeasure
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\MeasureType
     */
    public $NetWeightMeasure;
    /**
     * The GrossWeightMeasure
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\MeasureType
     */
    public $GrossWeightMeasure;
    /**
     * The NetVolumeMeasure
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\MeasureType
     */
    public $NetVolumeMeasure;
    /**
     * The GrossVolumeMeasure
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\MeasureType
     */
    public $GrossVolumeMeasure;
    /**
     * The AdditionalInformationSPSNote
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\AdditionalInformationSPSNote[]
     */
    public $AdditionalInformationSPSNote;
    /**
     * The ApplicableSPSClassification
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: tns:ApplicableSPSClassification
     * @var \StructType\ApplicableSPSClassification[]
     */
    public $ApplicableSPSClassification;
    /**
     * The PhysicalSPSPackage
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\PhysicalSPSPackage[]
     */
    public $PhysicalSPSPackage;
    /**
     * The OriginSPSCountry
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\OriginSPSCountry[]
     */
    public $OriginSPSCountry;
    /**
     * The AppliedSPSProcess
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\AppliedSPSProcess[]
     */
    public $AppliedSPSProcess;
    /**
     * Constructor method for IncludedSPSTradeLineItem
     * @uses IncludedSPSTradeLineItem::setSequenceNumeric()
     * @uses IncludedSPSTradeLineItem::setDescription()
     * @uses IncludedSPSTradeLineItem::setCommonName()
     * @uses IncludedSPSTradeLineItem::setScientificName()
     * @uses IncludedSPSTradeLineItem::setIntendedUse()
     * @uses IncludedSPSTradeLineItem::setNetWeightMeasure()
     * @uses IncludedSPSTradeLineItem::setGrossWeightMeasure()
     * @uses IncludedSPSTradeLineItem::setNetVolumeMeasure()
     * @uses IncludedSPSTradeLineItem::setGrossVolumeMeasure()
     * @uses IncludedSPSTradeLineItem::setAdditionalInformationSPSNote()
     * @uses IncludedSPSTradeLineItem::setApplicableSPSClassification()
     * @uses IncludedSPSTradeLineItem::setPhysicalSPSPackage()
     * @uses IncludedSPSTradeLineItem::setOriginSPSCountry()
     * @uses IncludedSPSTradeLineItem::setAppliedSPSProcess()
     * @param int $sequenceNumeric
     * @param \StructType\TextType[] $description
     * @param \StructType\TextType[] $commonName
     * @param \StructType\TextType[] $scientificName
     * @param \StructType\TextType[] $intendedUse
     * @param \StructType\MeasureType $netWeightMeasure
     * @param \StructType\MeasureType $grossWeightMeasure
     * @param \StructType\MeasureType $netVolumeMeasure
     * @param \StructType\MeasureType $grossVolumeMeasure
     * @param \StructType\AdditionalInformationSPSNote[] $additionalInformationSPSNote
     * @param \StructType\ApplicableSPSClassification[] $applicableSPSClassification
     * @param \StructType\PhysicalSPSPackage[] $physicalSPSPackage
     * @param \StructType\OriginSPSCountry[] $originSPSCountry
     * @param \StructType\AppliedSPSProcess[] $appliedSPSProcess
     */
    public function __construct($sequenceNumeric = null, array $description = array(), array $commonName = array(), array $scientificName = array(), array $intendedUse = array(), \StructType\MeasureType $netWeightMeasure = null, \StructType\MeasureType $grossWeightMeasure = null, \StructType\MeasureType $netVolumeMeasure = null, \StructType\MeasureType $grossVolumeMeasure = null, array $additionalInformationSPSNote = array(), array $applicableSPSClassification = array(), array $physicalSPSPackage = array(), array $originSPSCountry = array(), array $appliedSPSProcess = array())
    {
        $this
            ->setSequenceNumeric($sequenceNumeric)
            ->setDescription($description)
            ->setCommonName($commonName)
            ->setScientificName($scientificName)
            ->setIntendedUse($intendedUse)
            ->setNetWeightMeasure($netWeightMeasure)
            ->setGrossWeightMeasure($grossWeightMeasure)
            ->setNetVolumeMeasure($netVolumeMeasure)
            ->setGrossVolumeMeasure($grossVolumeMeasure)
            ->setAdditionalInformationSPSNote($additionalInformationSPSNote)
            ->setApplicableSPSClassification($applicableSPSClassification)
            ->setPhysicalSPSPackage($physicalSPSPackage)
            ->setOriginSPSCountry($originSPSCountry)
            ->setAppliedSPSProcess($appliedSPSProcess);
    }
    /**
     * Get SequenceNumeric value
     * @return int|null
     */
    public function getSequenceNumeric()
    {
        return $this->SequenceNumeric;
    }
    /**
     * Set SequenceNumeric value
     * @param int $sequenceNumeric
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setSequenceNumeric($sequenceNumeric = null)
    {
        // validation for constraint: int
        if (!is_null($sequenceNumeric) && !(is_int($sequenceNumeric) || ctype_digit($sequenceNumeric))) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide an integer value, %s given', var_export($sequenceNumeric, true), gettype($sequenceNumeric)), __LINE__);
        }
        $this->SequenceNumeric = $sequenceNumeric;
        return $this;
    }
    /**
     * Get Description value
     * @return \StructType\TextType[]|null
     */
    public function getDescription()
    {
        return $this->Description;
    }
    /**
     * This method is responsible for validating the values passed to the setDescription method
     * This method is willingly generated in order to preserve the one-line inline validation within the setDescription method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateDescriptionForArrayConstraintsFromSetDescription(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemDescriptionItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemDescriptionItem instanceof \StructType\TextType) {
                $invalidValues[] = is_object($includedSPSTradeLineItemDescriptionItem) ? get_class($includedSPSTradeLineItemDescriptionItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemDescriptionItem), var_export($includedSPSTradeLineItemDescriptionItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The Description property can only contain items of type \StructType\TextType, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set Description value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType[] $description
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setDescription(array $description = array())
    {
        // validation for constraint: array
        if ('' !== ($descriptionArrayErrorMessage = self::validateDescriptionForArrayConstraintsFromSetDescription($description))) {
            throw new \InvalidArgumentException($descriptionArrayErrorMessage, __LINE__);
        }
        $this->Description = $description;
        return $this;
    }
    /**
     * Add item to Description value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToDescription(\StructType\TextType $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\TextType) {
            throw new \InvalidArgumentException(sprintf('The Description property can only contain items of type \StructType\TextType, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->Description[] = $item;
        return $this;
    }
    /**
     * Get CommonName value
     * @return \StructType\TextType[]|null
     */
    public function getCommonName()
    {
        return $this->CommonName;
    }
    /**
     * This method is responsible for validating the values passed to the setCommonName method
     * This method is willingly generated in order to preserve the one-line inline validation within the setCommonName method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateCommonNameForArrayConstraintsFromSetCommonName(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemCommonNameItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemCommonNameItem instanceof \StructType\TextType) {
                $invalidValues[] = is_object($includedSPSTradeLineItemCommonNameItem) ? get_class($includedSPSTradeLineItemCommonNameItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemCommonNameItem), var_export($includedSPSTradeLineItemCommonNameItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The CommonName property can only contain items of type \StructType\TextType, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set CommonName value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType[] $commonName
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setCommonName(array $commonName = array())
    {
        // validation for constraint: array
        if ('' !== ($commonNameArrayErrorMessage = self::validateCommonNameForArrayConstraintsFromSetCommonName($commonName))) {
            throw new \InvalidArgumentException($commonNameArrayErrorMessage, __LINE__);
        }
        $this->CommonName = $commonName;
        return $this;
    }
    /**
     * Add item to CommonName value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToCommonName(\StructType\TextType $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\TextType) {
            throw new \InvalidArgumentException(sprintf('The CommonName property can only contain items of type \StructType\TextType, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->CommonName[] = $item;
        return $this;
    }
    /**
     * Get ScientificName value
     * @return \StructType\TextType[]|null
     */
    public function getScientificName()
    {
        return $this->ScientificName;
    }
    /**
     * This method is responsible for validating the values passed to the setScientificName method
     * This method is willingly generated in order to preserve the one-line inline validation within the setScientificName method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateScientificNameForArrayConstraintsFromSetScientificName(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemScientificNameItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemScientificNameItem instanceof \StructType\TextType) {
                $invalidValues[] = is_object($includedSPSTradeLineItemScientificNameItem) ? get_class($includedSPSTradeLineItemScientificNameItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemScientificNameItem), var_export($includedSPSTradeLineItemScientificNameItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The ScientificName property can only contain items of type \StructType\TextType, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set ScientificName value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType[] $scientificName
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setScientificName(array $scientificName = array())
    {
        // validation for constraint: array
        if ('' !== ($scientificNameArrayErrorMessage = self::validateScientificNameForArrayConstraintsFromSetScientificName($scientificName))) {
            throw new \InvalidArgumentException($scientificNameArrayErrorMessage, __LINE__);
        }
        $this->ScientificName = $scientificName;
        return $this;
    }
    /**
     * Add item to ScientificName value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToScientificName(\StructType\TextType $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\TextType) {
            throw new \InvalidArgumentException(sprintf('The ScientificName property can only contain items of type \StructType\TextType, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->ScientificName[] = $item;
        return $this;
    }
    /**
     * Get IntendedUse value
     * @return \StructType\TextType[]|null
     */
    public function getIntendedUse()
    {
        return $this->IntendedUse;
    }
    /**
     * This method is responsible for validating the values passed to the setIntendedUse method
     * This method is willingly generated in order to preserve the one-line inline validation within the setIntendedUse method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateIntendedUseForArrayConstraintsFromSetIntendedUse(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemIntendedUseItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemIntendedUseItem instanceof \StructType\TextType) {
                $invalidValues[] = is_object($includedSPSTradeLineItemIntendedUseItem) ? get_class($includedSPSTradeLineItemIntendedUseItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemIntendedUseItem), var_export($includedSPSTradeLineItemIntendedUseItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The IntendedUse property can only contain items of type \StructType\TextType, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set IntendedUse value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType[] $intendedUse
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setIntendedUse(array $intendedUse = array())
    {
        // validation for constraint: array
        if ('' !== ($intendedUseArrayErrorMessage = self::validateIntendedUseForArrayConstraintsFromSetIntendedUse($intendedUse))) {
            throw new \InvalidArgumentException($intendedUseArrayErrorMessage, __LINE__);
        }
        $this->IntendedUse = $intendedUse;
        return $this;
    }
    /**
     * Add item to IntendedUse value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToIntendedUse(\StructType\TextType $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\TextType) {
            throw new \InvalidArgumentException(sprintf('The IntendedUse property can only contain items of type \StructType\TextType, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->IntendedUse[] = $item;
        return $this;
    }
    /**
     * Get NetWeightMeasure value
     * @return \StructType\MeasureType|null
     */
    public function getNetWeightMeasure()
    {
        return $this->NetWeightMeasure;
    }
    /**
     * Set NetWeightMeasure value
     * @param \StructType\MeasureType $netWeightMeasure
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setNetWeightMeasure(\StructType\MeasureType $netWeightMeasure = null)
    {
        $this->NetWeightMeasure = $netWeightMeasure;
        return $this;
    }
    /**
     * Get GrossWeightMeasure value
     * @return \StructType\MeasureType|null
     */
    public function getGrossWeightMeasure()
    {
        return $this->GrossWeightMeasure;
    }
    /**
     * Set GrossWeightMeasure value
     * @param \StructType\MeasureType $grossWeightMeasure
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setGrossWeightMeasure(\StructType\MeasureType $grossWeightMeasure = null)
    {
        $this->GrossWeightMeasure = $grossWeightMeasure;
        return $this;
    }
    /**
     * Get NetVolumeMeasure value
     * @return \StructType\MeasureType|null
     */
    public function getNetVolumeMeasure()
    {
        return $this->NetVolumeMeasure;
    }
    /**
     * Set NetVolumeMeasure value
     * @param \StructType\MeasureType $netVolumeMeasure
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setNetVolumeMeasure(\StructType\MeasureType $netVolumeMeasure = null)
    {
        $this->NetVolumeMeasure = $netVolumeMeasure;
        return $this;
    }
    /**
     * Get GrossVolumeMeasure value
     * @return \StructType\MeasureType|null
     */
    public function getGrossVolumeMeasure()
    {
        return $this->GrossVolumeMeasure;
    }
    /**
     * Set GrossVolumeMeasure value
     * @param \StructType\MeasureType $grossVolumeMeasure
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setGrossVolumeMeasure(\StructType\MeasureType $grossVolumeMeasure = null)
    {
        $this->GrossVolumeMeasure = $grossVolumeMeasure;
        return $this;
    }
    /**
     * Get AdditionalInformationSPSNote value
     * @return \StructType\AdditionalInformationSPSNote[]|null
     */
    public function getAdditionalInformationSPSNote()
    {
        return $this->AdditionalInformationSPSNote;
    }
    /**
     * This method is responsible for validating the values passed to the setAdditionalInformationSPSNote method
     * This method is willingly generated in order to preserve the one-line inline validation within the setAdditionalInformationSPSNote method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateAdditionalInformationSPSNoteForArrayConstraintsFromSetAdditionalInformationSPSNote(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemAdditionalInformationSPSNoteItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemAdditionalInformationSPSNoteItem instanceof \StructType\AdditionalInformationSPSNote) {
                $invalidValues[] = is_object($includedSPSTradeLineItemAdditionalInformationSPSNoteItem) ? get_class($includedSPSTradeLineItemAdditionalInformationSPSNoteItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemAdditionalInformationSPSNoteItem), var_export($includedSPSTradeLineItemAdditionalInformationSPSNoteItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The AdditionalInformationSPSNote property can only contain items of type \StructType\AdditionalInformationSPSNote, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set AdditionalInformationSPSNote value
     * @throws \InvalidArgumentException
     * @param \StructType\AdditionalInformationSPSNote[] $additionalInformationSPSNote
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setAdditionalInformationSPSNote(array $additionalInformationSPSNote = array())
    {
        // validation for constraint: array
        if ('' !== ($additionalInformationSPSNoteArrayErrorMessage = self::validateAdditionalInformationSPSNoteForArrayConstraintsFromSetAdditionalInformationSPSNote($additionalInformationSPSNote))) {
            throw new \InvalidArgumentException($additionalInformationSPSNoteArrayErrorMessage, __LINE__);
        }
        $this->AdditionalInformationSPSNote = $additionalInformationSPSNote;
        return $this;
    }
    /**
     * Add item to AdditionalInformationSPSNote value
     * @throws \InvalidArgumentException
     * @param \StructType\AdditionalInformationSPSNote $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToAdditionalInformationSPSNote(\StructType\AdditionalInformationSPSNote $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\AdditionalInformationSPSNote) {
            throw new \InvalidArgumentException(sprintf('The AdditionalInformationSPSNote property can only contain items of type \StructType\AdditionalInformationSPSNote, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->AdditionalInformationSPSNote[] = $item;
        return $this;
    }
    /**
     * Get ApplicableSPSClassification value
     * @return \StructType\ApplicableSPSClassification[]|null
     */
    public function getApplicableSPSClassification()
    {
        return $this->ApplicableSPSClassification;
    }
    /**
     * This method is responsible for validating the values passed to the setApplicableSPSClassification method
     * This method is willingly generated in order to preserve the one-line inline validation within the setApplicableSPSClassification method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateApplicableSPSClassificationForArrayConstraintsFromSetApplicableSPSClassification(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemApplicableSPSClassificationItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemApplicableSPSClassificationItem instanceof \StructType\ApplicableSPSClassification) {
                $invalidValues[] = is_object($includedSPSTradeLineItemApplicableSPSClassificationItem) ? get_class($includedSPSTradeLineItemApplicableSPSClassificationItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemApplicableSPSClassificationItem), var_export($includedSPSTradeLineItemApplicableSPSClassificationItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The ApplicableSPSClassification property can only contain items of type \StructType\ApplicableSPSClassification, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set ApplicableSPSClassification value
     * @throws \InvalidArgumentException
     * @param \StructType\ApplicableSPSClassification[] $applicableSPSClassification
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setApplicableSPSClassification(array $applicableSPSClassification = array())
    {
        // validation for constraint: array
        if ('' !== ($applicableSPSClassificationArrayErrorMessage = self::validateApplicableSPSClassificationForArrayConstraintsFromSetApplicableSPSClassification($applicableSPSClassification))) {
            throw new \InvalidArgumentException($applicableSPSClassificationArrayErrorMessage, __LINE__);
        }
        $this->ApplicableSPSClassification = $applicableSPSClassification;
        return $this;
    }
    /**
     * Add item to ApplicableSPSClassification value
     * @throws \InvalidArgumentException
     * @param \StructType\ApplicableSPSClassification $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToApplicableSPSClassification(\StructType\ApplicableSPSClassification $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\ApplicableSPSClassification) {
            throw new \InvalidArgumentException(sprintf('The ApplicableSPSClassification property can only contain items of type \StructType\ApplicableSPSClassification, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->ApplicableSPSClassification[] = $item;
        return $this;
    }
    /**
     * Get PhysicalSPSPackage value
     * @return \StructType\PhysicalSPSPackage[]|null
     */
    public function getPhysicalSPSPackage()
    {
        return $this->PhysicalSPSPackage;
    }
    /**
     * This method is responsible for validating the values passed to the setPhysicalSPSPackage method
     * This method is willingly generated in order to preserve the one-line inline validation within the setPhysicalSPSPackage method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validatePhysicalSPSPackageForArrayConstraintsFromSetPhysicalSPSPackage(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemPhysicalSPSPackageItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemPhysicalSPSPackageItem instanceof \StructType\PhysicalSPSPackage) {
                $invalidValues[] = is_object($includedSPSTradeLineItemPhysicalSPSPackageItem) ? get_class($includedSPSTradeLineItemPhysicalSPSPackageItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemPhysicalSPSPackageItem), var_export($includedSPSTradeLineItemPhysicalSPSPackageItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The PhysicalSPSPackage property can only contain items of type \StructType\PhysicalSPSPackage, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set PhysicalSPSPackage value
     * @throws \InvalidArgumentException
     * @param \StructType\PhysicalSPSPackage[] $physicalSPSPackage
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setPhysicalSPSPackage(array $physicalSPSPackage = array())
    {
        // validation for constraint: array
        if ('' !== ($physicalSPSPackageArrayErrorMessage = self::validatePhysicalSPSPackageForArrayConstraintsFromSetPhysicalSPSPackage($physicalSPSPackage))) {
            throw new \InvalidArgumentException($physicalSPSPackageArrayErrorMessage, __LINE__);
        }
        $this->PhysicalSPSPackage = $physicalSPSPackage;
        return $this;
    }
    /**
     * Add item to PhysicalSPSPackage value
     * @throws \InvalidArgumentException
     * @param \StructType\PhysicalSPSPackage $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToPhysicalSPSPackage(\StructType\PhysicalSPSPackage $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\PhysicalSPSPackage) {
            throw new \InvalidArgumentException(sprintf('The PhysicalSPSPackage property can only contain items of type \StructType\PhysicalSPSPackage, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->PhysicalSPSPackage[] = $item;
        return $this;
    }
    /**
     * Get OriginSPSCountry value
     * @return \StructType\OriginSPSCountry[]|null
     */
    public function getOriginSPSCountry()
    {
        return $this->OriginSPSCountry;
    }
    /**
     * This method is responsible for validating the values passed to the setOriginSPSCountry method
     * This method is willingly generated in order to preserve the one-line inline validation within the setOriginSPSCountry method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateOriginSPSCountryForArrayConstraintsFromSetOriginSPSCountry(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemOriginSPSCountryItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemOriginSPSCountryItem instanceof \StructType\OriginSPSCountry) {
                $invalidValues[] = is_object($includedSPSTradeLineItemOriginSPSCountryItem) ? get_class($includedSPSTradeLineItemOriginSPSCountryItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemOriginSPSCountryItem), var_export($includedSPSTradeLineItemOriginSPSCountryItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The OriginSPSCountry property can only contain items of type \StructType\OriginSPSCountry, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set OriginSPSCountry value
     * @throws \InvalidArgumentException
     * @param \StructType\OriginSPSCountry[] $originSPSCountry
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setOriginSPSCountry(array $originSPSCountry = array())
    {
        // validation for constraint: array
        if ('' !== ($originSPSCountryArrayErrorMessage = self::validateOriginSPSCountryForArrayConstraintsFromSetOriginSPSCountry($originSPSCountry))) {
            throw new \InvalidArgumentException($originSPSCountryArrayErrorMessage, __LINE__);
        }
        $this->OriginSPSCountry = $originSPSCountry;
        return $this;
    }
    /**
     * Add item to OriginSPSCountry value
     * @throws \InvalidArgumentException
     * @param \StructType\OriginSPSCountry $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToOriginSPSCountry(\StructType\OriginSPSCountry $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\OriginSPSCountry) {
            throw new \InvalidArgumentException(sprintf('The OriginSPSCountry property can only contain items of type \StructType\OriginSPSCountry, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->OriginSPSCountry[] = $item;
        return $this;
    }
    /**
     * Get AppliedSPSProcess value
     * @return \StructType\AppliedSPSProcess[]|null
     */
    public function getAppliedSPSProcess()
    {
        return $this->AppliedSPSProcess;
    }
    /**
     * This method is responsible for validating the values passed to the setAppliedSPSProcess method
     * This method is willingly generated in order to preserve the one-line inline validation within the setAppliedSPSProcess method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateAppliedSPSProcessForArrayConstraintsFromSetAppliedSPSProcess(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSTradeLineItemAppliedSPSProcessItem) {
            // validation for constraint: itemType
            if (!$includedSPSTradeLineItemAppliedSPSProcessItem instanceof \StructType\AppliedSPSProcess) {
                $invalidValues[] = is_object($includedSPSTradeLineItemAppliedSPSProcessItem) ? get_class($includedSPSTradeLineItemAppliedSPSProcessItem) : sprintf('%s(%s)', gettype($includedSPSTradeLineItemAppliedSPSProcessItem), var_export($includedSPSTradeLineItemAppliedSPSProcessItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The AppliedSPSProcess property can only contain items of type \StructType\AppliedSPSProcess, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set AppliedSPSProcess value
     * @throws \InvalidArgumentException
     * @param \StructType\AppliedSPSProcess[] $appliedSPSProcess
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function setAppliedSPSProcess(array $appliedSPSProcess = array())
    {
        // validation for constraint: array
        if ('' !== ($appliedSPSProcessArrayErrorMessage = self::validateAppliedSPSProcessForArrayConstraintsFromSetAppliedSPSProcess($appliedSPSProcess))) {
            throw new \InvalidArgumentException($appliedSPSProcessArrayErrorMessage, __LINE__);
        }
        $this->AppliedSPSProcess = $appliedSPSProcess;
        return $this;
    }
    /**
     * Add item to AppliedSPSProcess value
     * @throws \InvalidArgumentException
     * @param \StructType\AppliedSPSProcess $item
     * @return \StructType\IncludedSPSTradeLineItem
     */
    public function addToAppliedSPSProcess(\StructType\AppliedSPSProcess $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\AppliedSPSProcess) {
            throw new \InvalidArgumentException(sprintf('The AppliedSPSProcess property can only contain items of type \StructType\AppliedSPSProcess, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->AppliedSPSProcess[] = $item;
        return $this;
    }
}
