<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for IncludedSPSConsignmentItem StructType
 * Meta information extracted from the WSDL
 * - type: tns:IncludedSPSConsignmentItem
 * @subpackage Structs
 */
class IncludedSPSConsignmentItem extends AbstractStructBase
{
    /**
     * The NatureIdentificationSPSCargo
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * @var \StructType\SpsCargoType
     */
    public $NatureIdentificationSPSCargo;
    /**
     * The IncludedSPSTradeLineItem
     * Meta information extracted from the WSDL
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - ref: tns:IncludedSPSTradeLineItem
     * @var \StructType\IncludedSPSTradeLineItem[]
     */
    public $IncludedSPSTradeLineItem;
    /**
     * Constructor method for IncludedSPSConsignmentItem
     * @uses IncludedSPSConsignmentItem::setNatureIdentificationSPSCargo()
     * @uses IncludedSPSConsignmentItem::setIncludedSPSTradeLineItem()
     * @param \StructType\SpsCargoType $natureIdentificationSPSCargo
     * @param \StructType\IncludedSPSTradeLineItem[] $includedSPSTradeLineItem
     */
    public function __construct(\StructType\SpsCargoType $natureIdentificationSPSCargo = null, array $includedSPSTradeLineItem = array())
    {
        $this
            ->setNatureIdentificationSPSCargo($natureIdentificationSPSCargo)
            ->setIncludedSPSTradeLineItem($includedSPSTradeLineItem);
    }
    /**
     * Get NatureIdentificationSPSCargo value
     * @return \StructType\SpsCargoType|null
     */
    public function getNatureIdentificationSPSCargo()
    {
        return $this->NatureIdentificationSPSCargo;
    }
    /**
     * Set NatureIdentificationSPSCargo value
     * @param \StructType\SpsCargoType $natureIdentificationSPSCargo
     * @return \StructType\IncludedSPSConsignmentItem
     */
    public function setNatureIdentificationSPSCargo(\StructType\SpsCargoType $natureIdentificationSPSCargo = null)
    {
        $this->NatureIdentificationSPSCargo = $natureIdentificationSPSCargo;
        return $this;
    }
    /**
     * Get IncludedSPSTradeLineItem value
     * @return \StructType\IncludedSPSTradeLineItem[]|null
     */
    public function getIncludedSPSTradeLineItem()
    {
        return $this->IncludedSPSTradeLineItem;
    }
    /**
     * This method is responsible for validating the values passed to the setIncludedSPSTradeLineItem method
     * This method is willingly generated in order to preserve the one-line inline validation within the setIncludedSPSTradeLineItem method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateIncludedSPSTradeLineItemForArrayConstraintsFromSetIncludedSPSTradeLineItem(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $includedSPSConsignmentItemIncludedSPSTradeLineItemItem) {
            // validation for constraint: itemType
            if (!$includedSPSConsignmentItemIncludedSPSTradeLineItemItem instanceof \StructType\IncludedSPSTradeLineItem) {
                $invalidValues[] = is_object($includedSPSConsignmentItemIncludedSPSTradeLineItemItem) ? get_class($includedSPSConsignmentItemIncludedSPSTradeLineItemItem) : sprintf('%s(%s)', gettype($includedSPSConsignmentItemIncludedSPSTradeLineItemItem), var_export($includedSPSConsignmentItemIncludedSPSTradeLineItemItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The IncludedSPSTradeLineItem property can only contain items of type \StructType\IncludedSPSTradeLineItem, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set IncludedSPSTradeLineItem value
     * @throws \InvalidArgumentException
     * @param \StructType\IncludedSPSTradeLineItem[] $includedSPSTradeLineItem
     * @return \StructType\IncludedSPSConsignmentItem
     */
    public function setIncludedSPSTradeLineItem(array $includedSPSTradeLineItem = array())
    {
        // validation for constraint: array
        if ('' !== ($includedSPSTradeLineItemArrayErrorMessage = self::validateIncludedSPSTradeLineItemForArrayConstraintsFromSetIncludedSPSTradeLineItem($includedSPSTradeLineItem))) {
            throw new \InvalidArgumentException($includedSPSTradeLineItemArrayErrorMessage, __LINE__);
        }
        $this->IncludedSPSTradeLineItem = $includedSPSTradeLineItem;
        return $this;
    }
    /**
     * Add item to IncludedSPSTradeLineItem value
     * @throws \InvalidArgumentException
     * @param \StructType\IncludedSPSTradeLineItem $item
     * @return \StructType\IncludedSPSConsignmentItem
     */
    public function addToIncludedSPSTradeLineItem(\StructType\IncludedSPSTradeLineItem $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\IncludedSPSTradeLineItem) {
            throw new \InvalidArgumentException(sprintf('The IncludedSPSTradeLineItem property can only contain items of type \StructType\IncludedSPSTradeLineItem, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->IncludedSPSTradeLineItem[] = $item;
        return $this;
    }
}
