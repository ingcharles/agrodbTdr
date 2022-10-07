<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetConditionsResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetConditionsResponse
 * @subpackage Structs
 */
class GetConditionsResponse extends AbstractStructBase
{
    /**
     * The Condition
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\Condition[]
     */
    public $Condition;
    /**
     * Constructor method for GetConditionsResponse
     * @uses GetConditionsResponse::setCondition()
     * @param \StructType\Condition[] $condition
     */
    public function __construct(array $condition = array())
    {
        $this
            ->setCondition($condition);
    }
    /**
     * Get Condition value
     * @return \StructType\Condition[]|null
     */
    public function getCondition()
    {
        return $this->Condition;
    }
    /**
     * This method is responsible for validating the values passed to the setCondition method
     * This method is willingly generated in order to preserve the one-line inline validation within the setCondition method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateConditionForArrayConstraintsFromSetCondition(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getConditionsResponseConditionItem) {
            // validation for constraint: itemType
            if (!$getConditionsResponseConditionItem instanceof \StructType\Condition) {
                $invalidValues[] = is_object($getConditionsResponseConditionItem) ? get_class($getConditionsResponseConditionItem) : sprintf('%s(%s)', gettype($getConditionsResponseConditionItem), var_export($getConditionsResponseConditionItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The Condition property can only contain items of type \StructType\Condition, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set Condition value
     * @throws \InvalidArgumentException
     * @param \StructType\Condition[] $condition
     * @return \StructType\GetConditionsResponse
     */
    public function setCondition(array $condition = array())
    {
        // validation for constraint: array
        if ('' !== ($conditionArrayErrorMessage = self::validateConditionForArrayConstraintsFromSetCondition($condition))) {
            throw new \InvalidArgumentException($conditionArrayErrorMessage, __LINE__);
        }
        $this->Condition = $condition;
        return $this;
    }
    /**
     * Add item to Condition value
     * @throws \InvalidArgumentException
     * @param \StructType\Condition $item
     * @return \StructType\GetConditionsResponse
     */
    public function addToCondition(\StructType\Condition $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\Condition) {
            throw new \InvalidArgumentException(sprintf('The Condition property can only contain items of type \StructType\Condition, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->Condition[] = $item;
        return $this;
    }
}
