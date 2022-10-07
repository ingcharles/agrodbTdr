<?php

namespace ArrayType;

use \WsdlToPhp\PackageBase\AbstractStructArrayBase;

/**
 * This class stands for ArrayOfEnvelope ArrayType
 * Meta information extracted from the WSDL
 * - type: tns:ArrayOfEnvelope
 * @subpackage Arrays
 */
class ArrayOfEnvelope extends AbstractStructArrayBase
{
    /**
     * The Envelope
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - nillable: true
     * @var \StructType\Envelope[]
     */
    public $Envelope;
    /**
     * Constructor method for ArrayOfEnvelope
     * @uses ArrayOfEnvelope::setEnvelope()
     * @param \StructType\Envelope[] $envelope
     */
    public function __construct(array $envelope = array())
    {
        $this
            ->setEnvelope($envelope);
    }
    /**
     * Get Envelope value
     * An additional test has been added (isset) before returning the property value as
     * this property may have been unset before, due to the fact that this property is
     * removable from the request (nillable=true+minOccurs=0)
     * @return \StructType\Envelope[]|null
     */
    public function getEnvelope()
    {
        return isset($this->Envelope) ? $this->Envelope : null;
    }
    /**
     * This method is responsible for validating the values passed to the setEnvelope method
     * This method is willingly generated in order to preserve the one-line inline validation within the setEnvelope method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateEnvelopeForArrayConstraintsFromSetEnvelope(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $arrayOfEnvelopeEnvelopeItem) {
            // validation for constraint: itemType
            if (!$arrayOfEnvelopeEnvelopeItem instanceof \StructType\Envelope) {
                $invalidValues[] = is_object($arrayOfEnvelopeEnvelopeItem) ? get_class($arrayOfEnvelopeEnvelopeItem) : sprintf('%s(%s)', gettype($arrayOfEnvelopeEnvelopeItem), var_export($arrayOfEnvelopeEnvelopeItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The Envelope property can only contain items of type \StructType\Envelope, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set Envelope value
     * This property is removable from request (nillable=true+minOccurs=0), therefore
     * if the value assigned to this property is null, it is removed from this object
     * @throws \InvalidArgumentException
     * @param \StructType\Envelope[] $envelope
     * @return \ArrayType\ArrayOfEnvelope
     */
    public function setEnvelope(array $envelope = array())
    {
        // validation for constraint: array
        if ('' !== ($envelopeArrayErrorMessage = self::validateEnvelopeForArrayConstraintsFromSetEnvelope($envelope))) {
            throw new \InvalidArgumentException($envelopeArrayErrorMessage, __LINE__);
        }
        if (is_null($envelope) || (is_array($envelope) && empty($envelope))) {
            unset($this->Envelope);
        } else {
            $this->Envelope = $envelope;
        }
        return $this;
    }
    /**
     * Add item to Envelope value
     * @throws \InvalidArgumentException
     * @param \StructType\Envelope $item
     * @return \ArrayType\ArrayOfEnvelope
     */
    public function addToEnvelope(\StructType\Envelope $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\Envelope) {
            throw new \InvalidArgumentException(sprintf('The Envelope property can only contain items of type \StructType\Envelope, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->Envelope[] = $item;
        return $this;
    }
    /**
     * Returns the current element
     * @see AbstractStructArrayBase::current()
     * @return \StructType\Envelope|null
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see AbstractStructArrayBase::item()
     * @param int $index
     * @return \StructType\Envelope|null
     */
    public function item($index)
    {
        return parent::item($index);
    }
    /**
     * Returns the first element
     * @see AbstractStructArrayBase::first()
     * @return \StructType\Envelope|null
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see AbstractStructArrayBase::last()
     * @return \StructType\Envelope|null
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see AbstractStructArrayBase::offsetGet()
     * @param int $offset
     * @return \StructType\Envelope|null
     */
    public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }
    /**
     * Returns the attribute name
     * @see AbstractStructArrayBase::getAttributeName()
     * @return string Envelope
     */
    public function getAttributeName()
    {
        return 'Envelope';
    }
}
