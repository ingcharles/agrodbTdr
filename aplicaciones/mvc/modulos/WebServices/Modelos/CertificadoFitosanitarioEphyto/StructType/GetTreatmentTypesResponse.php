<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetTreatmentTypesResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetTreatmentTypesResponse
 * @subpackage Structs
 */
class GetTreatmentTypesResponse extends AbstractStructBase
{
    /**
     * The TreatmentType
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\TreatmentType[]
     */
    public $TreatmentType;
    /**
     * Constructor method for GetTreatmentTypesResponse
     * @uses GetTreatmentTypesResponse::setTreatmentType()
     * @param \StructType\TreatmentType[] $treatmentType
     */
    public function __construct(array $treatmentType = array())
    {
        $this
            ->setTreatmentType($treatmentType);
    }
    /**
     * Get TreatmentType value
     * @return \StructType\TreatmentType[]|null
     */
    public function getTreatmentType()
    {
        return $this->TreatmentType;
    }
    /**
     * This method is responsible for validating the values passed to the setTreatmentType method
     * This method is willingly generated in order to preserve the one-line inline validation within the setTreatmentType method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateTreatmentTypeForArrayConstraintsFromSetTreatmentType(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getTreatmentTypesResponseTreatmentTypeItem) {
            // validation for constraint: itemType
            if (!$getTreatmentTypesResponseTreatmentTypeItem instanceof \StructType\TreatmentType) {
                $invalidValues[] = is_object($getTreatmentTypesResponseTreatmentTypeItem) ? get_class($getTreatmentTypesResponseTreatmentTypeItem) : sprintf('%s(%s)', gettype($getTreatmentTypesResponseTreatmentTypeItem), var_export($getTreatmentTypesResponseTreatmentTypeItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The TreatmentType property can only contain items of type \StructType\TreatmentType, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set TreatmentType value
     * @throws \InvalidArgumentException
     * @param \StructType\TreatmentType[] $treatmentType
     * @return \StructType\GetTreatmentTypesResponse
     */
    public function setTreatmentType(array $treatmentType = array())
    {
        // validation for constraint: array
        if ('' !== ($treatmentTypeArrayErrorMessage = self::validateTreatmentTypeForArrayConstraintsFromSetTreatmentType($treatmentType))) {
            throw new \InvalidArgumentException($treatmentTypeArrayErrorMessage, __LINE__);
        }
        $this->TreatmentType = $treatmentType;
        return $this;
    }
    /**
     * Add item to TreatmentType value
     * @throws \InvalidArgumentException
     * @param \StructType\TreatmentType $item
     * @return \StructType\GetTreatmentTypesResponse
     */
    public function addToTreatmentType(\StructType\TreatmentType $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\TreatmentType) {
            throw new \InvalidArgumentException(sprintf('The TreatmentType property can only contain items of type \StructType\TreatmentType, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->TreatmentType[] = $item;
        return $this;
    }
}
