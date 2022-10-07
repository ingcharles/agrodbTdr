<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for additionalInformationSPSNote StructType
 * @subpackage Structs
 */
class AdditionalInformationSPSNote extends AbstractStructBase
{
    /**
     * The Subject
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Subject;
    /**
     * The Content
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\TextType[]
     */
    public $Content;
    /**
     * Constructor method for additionalInformationSPSNote
     * @uses AdditionalInformationSPSNote::setSubject()
     * @uses AdditionalInformationSPSNote::setContent()
     * @param string $subject
     * @param \StructType\TextType[] $content
     */
    public function __construct($subject = null, array $content = array())
    {
        $this
            ->setSubject($subject)
            ->setContent($content);
    }
    /**
     * Get Subject value
     * @return string|null
     */
    public function getSubject()
    {
        return $this->Subject;
    }
    /**
     * Set Subject value
     * @param string $subject
     * @return \StructType\AdditionalInformationSPSNote
     */
    public function setSubject($subject = null)
    {
        // validation for constraint: string
        if (!is_null($subject) && !is_string($subject)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($subject, true), gettype($subject)), __LINE__);
        }
        $this->Subject = $subject;
        return $this;
    }
    /**
     * Get Content value
     * @return \StructType\TextType[]|null
     */
    public function getContent()
    {
        return $this->Content;
    }
    /**
     * This method is responsible for validating the values passed to the setContent method
     * This method is willingly generated in order to preserve the one-line inline validation within the setContent method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateContentForArrayConstraintsFromSetContent(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $additionalInformationSPSNoteContentItem) {
            // validation for constraint: itemType
            if (!$additionalInformationSPSNoteContentItem instanceof \StructType\TextType) {
                $invalidValues[] = is_object($additionalInformationSPSNoteContentItem) ? get_class($additionalInformationSPSNoteContentItem) : sprintf('%s(%s)', gettype($additionalInformationSPSNoteContentItem), var_export($additionalInformationSPSNoteContentItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The Content property can only contain items of type \StructType\TextType, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set Content value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType[] $content
     * @return \StructType\AdditionalInformationSPSNote
     */
    public function setContent(array $content = array())
    {
        // validation for constraint: array
        if ('' !== ($contentArrayErrorMessage = self::validateContentForArrayConstraintsFromSetContent($content))) {
            throw new \InvalidArgumentException($contentArrayErrorMessage, __LINE__);
        }
        $this->Content = $content;
        return $this;
    }
    /**
     * Add item to Content value
     * @throws \InvalidArgumentException
     * @param \StructType\TextType $item
     * @return \StructType\AdditionalInformationSPSNote
     */
    public function addToContent(\StructType\TextType $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\TextType) {
            throw new \InvalidArgumentException(sprintf('The Content property can only contain items of type \StructType\TextType, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->Content[] = $item;
        return $this;
    }
}
