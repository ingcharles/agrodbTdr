<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetUnitMeasuresResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetUnitMeasuresResponse
 * @subpackage Structs
 */
class GetUnitMeasuresResponse extends AbstractStructBase
{
    /**
     * The UnitMeasure
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\UnitMeasure[]
     */
    public $UnitMeasure;
    /**
     * Constructor method for GetUnitMeasuresResponse
     * @uses GetUnitMeasuresResponse::setUnitMeasure()
     * @param \StructType\UnitMeasure[] $unitMeasure
     */
    public function __construct(array $unitMeasure = array())
    {
        $this
            ->setUnitMeasure($unitMeasure);
    }
    /**
     * Get UnitMeasure value
     * @return \StructType\UnitMeasure[]|null
     */
    public function getUnitMeasure()
    {
        return $this->UnitMeasure;
    }
    /**
     * This method is responsible for validating the values passed to the setUnitMeasure method
     * This method is willingly generated in order to preserve the one-line inline validation within the setUnitMeasure method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateUnitMeasureForArrayConstraintsFromSetUnitMeasure(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getUnitMeasuresResponseUnitMeasureItem) {
            // validation for constraint: itemType
            if (!$getUnitMeasuresResponseUnitMeasureItem instanceof \StructType\UnitMeasure) {
                $invalidValues[] = is_object($getUnitMeasuresResponseUnitMeasureItem) ? get_class($getUnitMeasuresResponseUnitMeasureItem) : sprintf('%s(%s)', gettype($getUnitMeasuresResponseUnitMeasureItem), var_export($getUnitMeasuresResponseUnitMeasureItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The UnitMeasure property can only contain items of type \StructType\UnitMeasure, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set UnitMeasure value
     * @throws \InvalidArgumentException
     * @param \StructType\UnitMeasure[] $unitMeasure
     * @return \StructType\GetUnitMeasuresResponse
     */
    public function setUnitMeasure(array $unitMeasure = array())
    {
        // validation for constraint: array
        if ('' !== ($unitMeasureArrayErrorMessage = self::validateUnitMeasureForArrayConstraintsFromSetUnitMeasure($unitMeasure))) {
            throw new \InvalidArgumentException($unitMeasureArrayErrorMessage, __LINE__);
        }
        $this->UnitMeasure = $unitMeasure;
        return $this;
    }
    /**
     * Add item to UnitMeasure value
     * @throws \InvalidArgumentException
     * @param \StructType\UnitMeasure $item
     * @return \StructType\GetUnitMeasuresResponse
     */
    public function addToUnitMeasure(\StructType\UnitMeasure $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\UnitMeasure) {
            throw new \InvalidArgumentException(sprintf('The UnitMeasure property can only contain items of type \StructType\UnitMeasure, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->UnitMeasure[] = $item;
        return $this;
    }
}
