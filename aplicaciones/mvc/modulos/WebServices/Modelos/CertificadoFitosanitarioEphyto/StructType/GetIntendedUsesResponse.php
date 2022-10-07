<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetIntendedUsesResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetIntendedUsesResponse
 * @subpackage Structs
 */
class GetIntendedUsesResponse extends AbstractStructBase
{
    /**
     * The IntendedUse
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\IntendedUse[]
     */
    public $IntendedUse;
    /**
     * Constructor method for GetIntendedUsesResponse
     * @uses GetIntendedUsesResponse::setIntendedUse()
     * @param \StructType\IntendedUse[] $intendedUse
     */
    public function __construct(array $intendedUse = array())
    {
        $this
            ->setIntendedUse($intendedUse);
    }
    /**
     * Get IntendedUse value
     * @return \StructType\IntendedUse[]|null
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
        foreach ($values as $getIntendedUsesResponseIntendedUseItem) {
            // validation for constraint: itemType
            if (!$getIntendedUsesResponseIntendedUseItem instanceof \StructType\IntendedUse) {
                $invalidValues[] = is_object($getIntendedUsesResponseIntendedUseItem) ? get_class($getIntendedUsesResponseIntendedUseItem) : sprintf('%s(%s)', gettype($getIntendedUsesResponseIntendedUseItem), var_export($getIntendedUsesResponseIntendedUseItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The IntendedUse property can only contain items of type \StructType\IntendedUse, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set IntendedUse value
     * @throws \InvalidArgumentException
     * @param \StructType\IntendedUse[] $intendedUse
     * @return \StructType\GetIntendedUsesResponse
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
     * @param \StructType\IntendedUse $item
     * @return \StructType\GetIntendedUsesResponse
     */
    public function addToIntendedUse(\StructType\IntendedUse $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\IntendedUse) {
            throw new \InvalidArgumentException(sprintf('The IntendedUse property can only contain items of type \StructType\IntendedUse, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->IntendedUse[] = $item;
        return $this;
    }
}
