<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for GetPesticidesResponse StructType
 * Meta information extracted from the WSDL
 * - type: tns:GetPesticidesResponse
 * @subpackage Structs
 */
class GetPesticidesResponse extends AbstractStructBase
{
    /**
     * The Pesticide
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\Pesticide[]
     */
    public $Pesticide;
    /**
     * Constructor method for GetPesticidesResponse
     * @uses GetPesticidesResponse::setPesticide()
     * @param \StructType\Pesticide[] $pesticide
     */
    public function __construct(array $pesticide = array())
    {
        $this
            ->setPesticide($pesticide);
    }
    /**
     * Get Pesticide value
     * @return \StructType\Pesticide[]|null
     */
    public function getPesticide()
    {
        return $this->Pesticide;
    }
    /**
     * This method is responsible for validating the values passed to the setPesticide method
     * This method is willingly generated in order to preserve the one-line inline validation within the setPesticide method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validatePesticideForArrayConstraintsFromSetPesticide(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $getPesticidesResponsePesticideItem) {
            // validation for constraint: itemType
            if (!$getPesticidesResponsePesticideItem instanceof \StructType\Pesticide) {
                $invalidValues[] = is_object($getPesticidesResponsePesticideItem) ? get_class($getPesticidesResponsePesticideItem) : sprintf('%s(%s)', gettype($getPesticidesResponsePesticideItem), var_export($getPesticidesResponsePesticideItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The Pesticide property can only contain items of type \StructType\Pesticide, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set Pesticide value
     * @throws \InvalidArgumentException
     * @param \StructType\Pesticide[] $pesticide
     * @return \StructType\GetPesticidesResponse
     */
    public function setPesticide(array $pesticide = array())
    {
        // validation for constraint: array
        if ('' !== ($pesticideArrayErrorMessage = self::validatePesticideForArrayConstraintsFromSetPesticide($pesticide))) {
            throw new \InvalidArgumentException($pesticideArrayErrorMessage, __LINE__);
        }
        $this->Pesticide = $pesticide;
        return $this;
    }
    /**
     * Add item to Pesticide value
     * @throws \InvalidArgumentException
     * @param \StructType\Pesticide $item
     * @return \StructType\GetPesticidesResponse
     */
    public function addToPesticide(\StructType\Pesticide $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\Pesticide) {
            throw new \InvalidArgumentException(sprintf('The Pesticide property can only contain items of type \StructType\Pesticide, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->Pesticide[] = $item;
        return $this;
    }
}
