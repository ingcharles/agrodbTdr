<?php

namespace ArrayType;

use \WsdlToPhp\PackageBase\AbstractStructArrayBase;

/**
 * This class stands for ArrayOfEnvelopeHeader ArrayType
 * Meta information extracted from the WSDL
 * - type: tns:ArrayOfEnvelopeHeader
 * @subpackage Arrays
 */
class ArrayOfEnvelopeHeader extends AbstractStructArrayBase
{
    /**
     * The EnvelopeHeader
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - nillable: true
     * @var \StructType\EnvelopeHeader[]
     */
    public $EnvelopeHeader;
    /**
     * Constructor method for ArrayOfEnvelopeHeader
     * @uses ArrayOfEnvelopeHeader::setEnvelopeHeader()
     * @param \StructType\EnvelopeHeader[] $envelopeHeader
     */
    public function __construct(array $envelopeHeader = array())
    {
        $this
            ->setEnvelopeHeader($envelopeHeader);
    }
    /**
     * Get EnvelopeHeader value
     * An additional test has been added (isset) before returning the property value as
     * this property may have been unset before, due to the fact that this property is
     * removable from the request (nillable=true+minOccurs=0)
     * @return \StructType\EnvelopeHeader[]|null
     */
    public function getEnvelopeHeader()
    {
        return isset($this->EnvelopeHeader) ? $this->EnvelopeHeader : null;
    }
    /**
     * This method is responsible for validating the values passed to the setEnvelopeHeader method
     * This method is willingly generated in order to preserve the one-line inline validation within the setEnvelopeHeader method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateEnvelopeHeaderForArrayConstraintsFromSetEnvelopeHeader(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $arrayOfEnvelopeHeaderEnvelopeHeaderItem) {
            // validation for constraint: itemType
            if (!$arrayOfEnvelopeHeaderEnvelopeHeaderItem instanceof \StructType\EnvelopeHeader) {
                $invalidValues[] = is_object($arrayOfEnvelopeHeaderEnvelopeHeaderItem) ? get_class($arrayOfEnvelopeHeaderEnvelopeHeaderItem) : sprintf('%s(%s)', gettype($arrayOfEnvelopeHeaderEnvelopeHeaderItem), var_export($arrayOfEnvelopeHeaderEnvelopeHeaderItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The EnvelopeHeader property can only contain items of type \StructType\EnvelopeHeader, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set EnvelopeHeader value
     * This property is removable from request (nillable=true+minOccurs=0), therefore
     * if the value assigned to this property is null, it is removed from this object
     * @throws \InvalidArgumentException
     * @param \StructType\EnvelopeHeader[] $envelopeHeader
     * @return \ArrayType\ArrayOfEnvelopeHeader
     */
    public function setEnvelopeHeader(array $envelopeHeader = array())
    {
        // validation for constraint: array
        if ('' !== ($envelopeHeaderArrayErrorMessage = self::validateEnvelopeHeaderForArrayConstraintsFromSetEnvelopeHeader($envelopeHeader))) {
            throw new \InvalidArgumentException($envelopeHeaderArrayErrorMessage, __LINE__);
        }
        if (is_null($envelopeHeader) || (is_array($envelopeHeader) && empty($envelopeHeader))) {
            unset($this->EnvelopeHeader);
        } else {
            $this->EnvelopeHeader = $envelopeHeader;
        }
        return $this;
    }
    /**
     * Add item to EnvelopeHeader value
     * @throws \InvalidArgumentException
     * @param \StructType\EnvelopeHeader $item
     * @return \ArrayType\ArrayOfEnvelopeHeader
     */
    public function addToEnvelopeHeader(\StructType\EnvelopeHeader $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\EnvelopeHeader) {
            throw new \InvalidArgumentException(sprintf('The EnvelopeHeader property can only contain items of type \StructType\EnvelopeHeader, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->EnvelopeHeader[] = $item;
        return $this;
    }
    /**
     * Returns the current element
     * @see AbstractStructArrayBase::current()
     * @return \StructType\EnvelopeHeader|null
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see AbstractStructArrayBase::item()
     * @param int $index
     * @return \StructType\EnvelopeHeader|null
     */
    public function item($index)
    {
        return parent::item($index);
    }
    /**
     * Returns the first element
     * @see AbstractStructArrayBase::first()
     * @return \StructType\EnvelopeHeader|null
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see AbstractStructArrayBase::last()
     * @return \StructType\EnvelopeHeader|null
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see AbstractStructArrayBase::offsetGet()
     * @param int $offset
     * @return \StructType\EnvelopeHeader|null
     */
    public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }
    /**
     * Returns the attribute name
     * @see AbstractStructArrayBase::getAttributeName()
     * @return string EnvelopeHeader
     */
    public function getAttributeName()
    {
        return 'EnvelopeHeader';
    }
}
