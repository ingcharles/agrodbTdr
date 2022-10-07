<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Nppo StructType
 * @subpackage Structs
 */
class Nppo extends AbstractStructBase
{
    /**
     * The Country
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Country;
    /**
     * The Receive
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Receive;
    /**
     * The Send
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Send;
    /**
     * The AllowedDocument
     * Meta information extracted from the WSDL
     * - form: qualified
     * - maxOccurs: unbounded
     * - minOccurs: 0
     * @var \StructType\AllowedDocument[]
     */
    public $AllowedDocument;
    /**
     * The Signature
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \StructType\SigningCertificate
     */
    public $Signature;
    /**
     * The ChannelRules
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var \ArrayType\ArrayOfChannelRules
     */
    public $ChannelRules;
    /**
     * Constructor method for Nppo
     * @uses Nppo::setCountry()
     * @uses Nppo::setReceive()
     * @uses Nppo::setSend()
     * @uses Nppo::setAllowedDocument()
     * @uses Nppo::setSignature()
     * @uses Nppo::setChannelRules()
     * @param string $country
     * @param string $receive
     * @param string $send
     * @param \StructType\AllowedDocument[] $allowedDocument
     * @param \StructType\SigningCertificate $signature
     * @param \ArrayType\ArrayOfChannelRules $channelRules
     */
    public function __construct($country = null, $receive = null, $send = null, array $allowedDocument = array(), \StructType\SigningCertificate $signature = null, \ArrayType\ArrayOfChannelRules $channelRules = null)
    {
        $this
            ->setCountry($country)
            ->setReceive($receive)
            ->setSend($send)
            ->setAllowedDocument($allowedDocument)
            ->setSignature($signature)
            ->setChannelRules($channelRules);
    }
    /**
     * Get Country value
     * @return string|null
     */
    public function getCountry()
    {
        return $this->Country;
    }
    /**
     * Set Country value
     * @param string $country
     * @return \StructType\Nppo
     */
    public function setCountry($country = null)
    {
        // validation for constraint: string
        if (!is_null($country) && !is_string($country)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($country, true), gettype($country)), __LINE__);
        }
        $this->Country = $country;
        return $this;
    }
    /**
     * Get Receive value
     * @return string|null
     */
    public function getReceive()
    {
        return $this->Receive;
    }
    /**
     * Set Receive value
     * @param string $receive
     * @return \StructType\Nppo
     */
    public function setReceive($receive = null)
    {
        // validation for constraint: string
        if (!is_null($receive) && !is_string($receive)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($receive, true), gettype($receive)), __LINE__);
        }
        $this->Receive = $receive;
        return $this;
    }
    /**
     * Get Send value
     * @return string|null
     */
    public function getSend()
    {
        return $this->Send;
    }
    /**
     * Set Send value
     * @param string $send
     * @return \StructType\Nppo
     */
    public function setSend($send = null)
    {
        // validation for constraint: string
        if (!is_null($send) && !is_string($send)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($send, true), gettype($send)), __LINE__);
        }
        $this->Send = $send;
        return $this;
    }
    /**
     * Get AllowedDocument value
     * @return \StructType\AllowedDocument[]|null
     */
    public function getAllowedDocument()
    {
        return $this->AllowedDocument;
    }
    /**
     * This method is responsible for validating the values passed to the setAllowedDocument method
     * This method is willingly generated in order to preserve the one-line inline validation within the setAllowedDocument method
     * @param array $values
     * @return string A non-empty message if the values does not match the validation rules
     */
    public static function validateAllowedDocumentForArrayConstraintsFromSetAllowedDocument(array $values = array())
    {
        $message = '';
        $invalidValues = [];
        foreach ($values as $nppoAllowedDocumentItem) {
            // validation for constraint: itemType
            if (!$nppoAllowedDocumentItem instanceof \StructType\AllowedDocument) {
                $invalidValues[] = is_object($nppoAllowedDocumentItem) ? get_class($nppoAllowedDocumentItem) : sprintf('%s(%s)', gettype($nppoAllowedDocumentItem), var_export($nppoAllowedDocumentItem, true));
            }
        }
        if (!empty($invalidValues)) {
            $message = sprintf('The AllowedDocument property can only contain items of type \StructType\AllowedDocument, %s given', is_object($invalidValues) ? get_class($invalidValues) : (is_array($invalidValues) ? implode(', ', $invalidValues) : gettype($invalidValues)));
        }
        unset($invalidValues);
        return $message;
    }
    /**
     * Set AllowedDocument value
     * @throws \InvalidArgumentException
     * @param \StructType\AllowedDocument[] $allowedDocument
     * @return \StructType\Nppo
     */
    public function setAllowedDocument(array $allowedDocument = array())
    {
        // validation for constraint: array
        if ('' !== ($allowedDocumentArrayErrorMessage = self::validateAllowedDocumentForArrayConstraintsFromSetAllowedDocument($allowedDocument))) {
            throw new \InvalidArgumentException($allowedDocumentArrayErrorMessage, __LINE__);
        }
        $this->AllowedDocument = $allowedDocument;
        return $this;
    }
    /**
     * Add item to AllowedDocument value
     * @throws \InvalidArgumentException
     * @param \StructType\AllowedDocument $item
     * @return \StructType\Nppo
     */
    public function addToAllowedDocument(\StructType\AllowedDocument $item)
    {
        // validation for constraint: itemType
        if (!$item instanceof \StructType\AllowedDocument) {
            throw new \InvalidArgumentException(sprintf('The AllowedDocument property can only contain items of type \StructType\AllowedDocument, %s given', is_object($item) ? get_class($item) : (is_array($item) ? implode(', ', $item) : gettype($item))), __LINE__);
        }
        $this->AllowedDocument[] = $item;
        return $this;
    }
    /**
     * Get Signature value
     * @return \StructType\SigningCertificate|null
     */
    public function getSignature()
    {
        return $this->Signature;
    }
    /**
     * Set Signature value
     * @param \StructType\SigningCertificate $signature
     * @return \StructType\Nppo
     */
    public function setSignature(\StructType\SigningCertificate $signature = null)
    {
        $this->Signature = $signature;
        return $this;
    }
    /**
     * Get ChannelRules value
     * @return \ArrayType\ArrayOfChannelRules|null
     */
    public function getChannelRules()
    {
        return $this->ChannelRules;
    }
    /**
     * Set ChannelRules value
     * @param \ArrayType\ArrayOfChannelRules $channelRules
     * @return \StructType\Nppo
     */
    public function setChannelRules(\ArrayType\ArrayOfChannelRules $channelRules = null)
    {
        $this->ChannelRules = $channelRules;
        return $this;
    }
}
