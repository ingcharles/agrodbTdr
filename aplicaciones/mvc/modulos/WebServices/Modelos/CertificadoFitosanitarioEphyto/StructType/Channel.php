<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Channel StructType
 * @subpackage Structs
 */
class Channel extends AbstractStructBase
{
    /**
     * The Receive
     * Meta information extracted from the WSDL
     * - use: required
     * @var bool
     */
    public $Receive;
    /**
     * The Send
     * Meta information extracted from the WSDL
     * - use: required
     * @var bool
     */
    public $Send;
    /**
     * The Code
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Code;
    /**
     * The Name
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Name;
    /**
     * Constructor method for Channel
     * @uses Channel::setReceive()
     * @uses Channel::setSend()
     * @uses Channel::setCode()
     * @uses Channel::setName()
     * @param bool $receive
     * @param bool $send
     * @param string $code
     * @param string $name
     */
    public function __construct($receive = null, $send = null, $code = null, $name = null)
    {
        $this
            ->setReceive($receive)
            ->setSend($send)
            ->setCode($code)
            ->setName($name);
    }
    /**
     * Get Receive value
     * @return bool
     */
    public function getReceive()
    {
        return $this->Receive;
    }
    /**
     * Set Receive value
     * @param bool $receive
     * @return \StructType\Channel
     */
    public function setReceive($receive = null)
    {
        // validation for constraint: boolean
        if (!is_null($receive) && !is_bool($receive)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($receive, true), gettype($receive)), __LINE__);
        }
        $this->Receive = $receive;
        return $this;
    }
    /**
     * Get Send value
     * @return bool
     */
    public function getSend()
    {
        return $this->Send;
    }
    /**
     * Set Send value
     * @param bool $send
     * @return \StructType\Channel
     */
    public function setSend($send = null)
    {
        // validation for constraint: boolean
        if (!is_null($send) && !is_bool($send)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a bool, %s given', var_export($send, true), gettype($send)), __LINE__);
        }
        $this->Send = $send;
        return $this;
    }
    /**
     * Get Code value
     * @return string|null
     */
    public function getCode()
    {
        return $this->Code;
    }
    /**
     * Set Code value
     * @param string $code
     * @return \StructType\Channel
     */
    public function setCode($code = null)
    {
        // validation for constraint: string
        if (!is_null($code) && !is_string($code)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($code, true), gettype($code)), __LINE__);
        }
        $this->Code = $code;
        return $this;
    }
    /**
     * Get Name value
     * @return string|null
     */
    public function getName()
    {
        return $this->Name;
    }
    /**
     * Set Name value
     * @param string $name
     * @return \StructType\Channel
     */
    public function setName($name = null)
    {
        // validation for constraint: string
        if (!is_null($name) && !is_string($name)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($name, true), gettype($name)), __LINE__);
        }
        $this->Name = $name;
        return $this;
    }
}
