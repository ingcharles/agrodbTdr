<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for applicableSPSProcessCharacteristic StructType
 * @subpackage Structs
 */
class ApplicableSPSProcessCharacteristic extends AbstractStructBase
{
    /**
     * The Description
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: ns3:Description
     * @var \StructType\TextType[]
     */
    public $Description;
    /**
     * The ValueMeasure
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:ValueMeasure
     * @var \StructType\MeasureType
     */
    public $ValueMeasure;
    /**
     * Constructor method for applicableSPSProcessCharacteristic
     * @uses ApplicableSPSProcessCharacteristic::setDescription()
     * @uses ApplicableSPSProcessCharacteristic::setValueMeasure()
     * @param \StructType\TextType[] $description
     * @param \StructType\MeasureType $valueMeasure
     */
    public function __construct(array $description = array(), \StructType\MeasureType $valueMeasure = null)
    {
        $this
            ->setDescription($description)
            ->setValueMeasure($valueMeasure);
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
        foreach ($values as $applicableSPSProcessCharacteristicDescriptionItem) {
            // validation for constraint: itemType
            if (!$applicableSPSProcessCharacteristicDescriptionItem instanceof \StructType\TextType) {
                $invalidValues[] = is_object($applicableSPSProcessCharacteristicDescriptionItem) ? get_class($applicableSPSProcessCharacteristicDescriptionItem) : sprintf('%s(%s)', gettype($applicableSPSProcessCharacteristicDescriptionItem), var_export($applicableSPSProcessCharacteristicDescriptionItem, true));
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
     * @return \StructType\ApplicableSPSProcessCharacteristic
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
     * @return \StructType\ApplicableSPSProcessCharacteristic
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
     * Get ValueMeasure value
     * @return \StructType\MeasureType|null
     */
    public function getValueMeasure()
    {
        return $this->ValueMeasure;
    }
    /**
     * Set ValueMeasure value
     * @param \StructType\MeasureType $valueMeasure
     * @return \StructType\ApplicableSPSProcessCharacteristic
     */
    public function setValueMeasure(\StructType\MeasureType $valueMeasure = null)
    {
        $this->ValueMeasure = $valueMeasure;
        return $this;
    }
}
