<?php

namespace ArrayType;

use \WsdlToPhp\PackageBase\AbstractStructArrayBase;

/**
 * This class stands for ArrayOfChannels ArrayType
 * @subpackage Arrays
 */
class ArrayOfChannels extends AbstractStructArrayBase
{
    /**
     * The Channel
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * - nillable: true
     * @var \StructType\Channel[]
     */
    public $Channel;
    /**
     * Constructor method for ArrayOfChannels
     * @uses ArrayOfChannels::setChannel()
     * @param \StructType\Channel[] $channel
     */
    public function __construct(array $channel = array())
    {
        $this
            ->setChannel($channel);
    }
    /**
     * Get Channel value
     * An additional test has been added (isset) before returning the property value as
     * this property may have been unset before, due to the fact that this property is
     * removable from the request (nillable=true+minOccurs=0)
     * @return \StructType\Channel[]|null
     */
    public function getChannel()
    {
        return isset($this->Channel) ? $this->Channel : null;
    }
    /**
     * This method is responsible for validating the values passed to the setChannel method
     * This method is willingly generated in order to preserve the one-line inline validation within the setChannel method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateChannelForArrayConstraintsFromSetChannel(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $arrayOfChannelsChannelItem) {
            // validation for constraint: itemType
            if (!$arrayOfChannelsChannelItem instanceof \StructType\Channel) {
                $invalidValues[] = is_object($arrayOfChannelsChannelItem) ? get_class($arrayOfChannelsChannelItem) : sprintf('%s(%s)', gettype($arrayOfChannelsChannelItem), var_export($arrayOfChannelsChannelItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The Channel property can only contain items of type \StructType\Channel, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set Channel value
     * This property is removable from request (nillable=true+minOccurs=0), therefore
     * if the value assigned to this property is null, it is removed from this object
     * @throws \InvalidArgumentException
     * @param \StructType\Channel[] $channel
     * @return \ArrayType\ArrayOfChannels
     */
    public function setChannel(array $channel = array())
    {
        // validation for constraint: array
        if ('' !== ($channelArrayErrorMessage = self::validateChannelForArrayConstraintsFromSetChannel($channel))) {
            throw new \InvalidArgumentException($channelArrayErrorMessage, __LINE__);
        }
        if (is_null($channel) || (is_array($channel) && empty($channel))) {
            unset($this->Channel);
        } else {
            $this->Channel = $channel;
        }
        return $this;
    }
    /**
     * Add item to Channel value
     * @throws \InvalidArgumentException
     * @param \StructType\Channel $item
     * @return \ArrayType\ArrayOfChannels
     */
    public function addToChannel(\StructType\Channel $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\Channel) {
            throw new \InvalidArgumentException(sprintf('The Channel property can only contain items of type \StructType\Channel, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->Channel[] = $item;
        return $this;
    }
    /**
     * Returns the current element
     * @see AbstractStructArrayBase::current()
     * @return \StructType\Channel|null
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see AbstractStructArrayBase::item()
     * @param int $index
     * @return \StructType\Channel|null
     */
    public function item($index)
    {
        return parent::item($index);
    }
    /**
     * Returns the first element
     * @see AbstractStructArrayBase::first()
     * @return \StructType\Channel|null
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see AbstractStructArrayBase::last()
     * @return \StructType\Channel|null
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see AbstractStructArrayBase::offsetGet()
     * @param int $offset
     * @return \StructType\Channel|null
     */
    public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }
    /**
     * Returns the attribute name
     * @see AbstractStructArrayBase::getAttributeName()
     * @return string Channel
     */
    public function getAttributeName()
    {
        return 'Channel';
    }
}
