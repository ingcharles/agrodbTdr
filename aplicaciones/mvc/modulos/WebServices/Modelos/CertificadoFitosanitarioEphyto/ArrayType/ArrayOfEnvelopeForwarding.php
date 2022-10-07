<?php

namespace ArrayType;

use \WsdlToPhp\PackageBase\AbstractStructArrayBase;

/**
 * This class stands for ArrayOfEnvelopeForwarding ArrayType
 * @subpackage Arrays
 */
class ArrayOfEnvelopeForwarding extends AbstractStructArrayBase
{
    /**
     * The EnvelopeForwarding
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - nillable: true
     * @var \StructType\EnvelopeFrowarding[]
     */
    public $EnvelopeForwarding;
    /**
     * Constructor method for ArrayOfEnvelopeForwarding
     * @uses ArrayOfEnvelopeForwarding::setEnvelopeForwarding()
     * @param \StructType\EnvelopeFrowarding[] $envelopeForwarding
     */
    public function __construct(array $envelopeForwarding = array())
    {
        $this
            ->setEnvelopeForwarding($envelopeForwarding);
    }
    /**
     * Get EnvelopeForwarding value
     * An additional test has been added (isset) before returning the property value as
     * this property may have been unset before, due to the fact that this property is
     * removable from the request (nillable=true+minOccurs=0)
     * @return \StructType\EnvelopeFrowarding[]|null
     */
    public function getEnvelopeForwarding()
    {
        return isset($this->EnvelopeForwarding) ? $this->EnvelopeForwarding : null;
    }
    /**
     * This method is responsible for validating the values passed to the setEnvelopeForwarding method
     * This method is willingly generated in order to preserve the one-line inline validation within the setEnvelopeForwarding method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateEnvelopeForwardingForArrayConstraintsFromSetEnvelopeForwarding(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $arrayOfEnvelopeForwardingEnvelopeForwardingItem) {
            // validation for constraint: itemType
            if (!$arrayOfEnvelopeForwardingEnvelopeForwardingItem instanceof \StructType\EnvelopeFrowarding) {
                $invalidValues[] = is_object($arrayOfEnvelopeForwardingEnvelopeForwardingItem) ? get_class($arrayOfEnvelopeForwardingEnvelopeForwardingItem) : sprintf('%s(%s)', gettype($arrayOfEnvelopeForwardingEnvelopeForwardingItem), var_export($arrayOfEnvelopeForwardingEnvelopeForwardingItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The EnvelopeForwarding property can only contain items of type \StructType\EnvelopeFrowarding, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set EnvelopeForwarding value
     * This property is removable from request (nillable=true+minOccurs=0), therefore
     * if the value assigned to this property is null, it is removed from this object
     * @throws \InvalidArgumentException
     * @param \StructType\EnvelopeFrowarding[] $envelopeForwarding
     * @return \ArrayType\ArrayOfEnvelopeForwarding
     */
    public function setEnvelopeForwarding(array $envelopeForwarding = array())
    {
        // validation for constraint: array
        if ('' !== ($envelopeForwardingArrayErrorMessage = self::validateEnvelopeForwardingForArrayConstraintsFromSetEnvelopeForwarding($envelopeForwarding))) {
            throw new \InvalidArgumentException($envelopeForwardingArrayErrorMessage, __LINE__);
        }
        if (is_null($envelopeForwarding) || (is_array($envelopeForwarding) && empty($envelopeForwarding))) {
            unset($this->EnvelopeForwarding);
        } else {
            $this->EnvelopeForwarding = $envelopeForwarding;
        }
        return $this;
    }
    /**
     * Add item to EnvelopeForwarding value
     * @throws \InvalidArgumentException
     * @param \StructType\EnvelopeFrowarding $item
     * @return \ArrayType\ArrayOfEnvelopeForwarding
     */
    public function addToEnvelopeForwarding(\StructType\EnvelopeFrowarding $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\EnvelopeFrowarding) {
            throw new \InvalidArgumentException(sprintf('The EnvelopeForwarding property can only contain items of type \StructType\EnvelopeFrowarding, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->EnvelopeForwarding[] = $item;
        return $this;
    }
    /**
     * Returns the current element
     * @see AbstractStructArrayBase::current()
     * @return \StructType\EnvelopeFrowarding|null
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see AbstractStructArrayBase::item()
     * @param int $index
     * @return \StructType\EnvelopeFrowarding|null
     */
    public function item($index)
    {
        return parent::item($index);
    }
    /**
     * Returns the first element
     * @see AbstractStructArrayBase::first()
     * @return \StructType\EnvelopeFrowarding|null
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see AbstractStructArrayBase::last()
     * @return \StructType\EnvelopeFrowarding|null
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see AbstractStructArrayBase::offsetGet()
     * @param int $offset
     * @return \StructType\EnvelopeFrowarding|null
     */
    public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }
    /**
     * Returns the attribute name
     * @see AbstractStructArrayBase::getAttributeName()
     * @return string EnvelopeForwarding
     */
    public function getAttributeName()
    {
        return 'EnvelopeForwarding';
    }
}
