<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for idType StructType
 * @subpackage Structs
 */
class IdType extends AbstractStructBase
{
    /**
     * The _
     * @var string
     */
    public $_;
    /**
     * The schemeName
     * @var string
     */
    public $schemeName;
    /**
     * Constructor method for idType
     * @uses IdType::set_()
     * @uses IdType::setSchemeName()
     * @param string $_
     * @param string $schemeName
     */
    public function __construct($_ = null, $schemeName = null)
    {
        $this
            ->set_($_)
            ->setSchemeName($schemeName);
    }
    /**
     * Get _ value
     * @return string|null
     */
    public function get_()
    {
        return $this->_;
    }
    /**
     * Set _ value
     * @param string $_
     * @return \StructType\IdType
     */
    public function set_($_ = null)
    {
        // validation for constraint: string
        if (!is_null($_) && !is_string($_)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($_, true), gettype($_)), __LINE__);
        }
        $this->_ = $_;
        return $this;
    }
    /**
     * Get schemeName value
     * @return string|null
     */
    public function getSchemeName()
    {
        return $this->schemeName;
    }
    /**
     * Set schemeName value
     * @param string $schemeName
     * @return \StructType\IdType
     */
    public function setSchemeName($schemeName = null)
    {
        // validation for constraint: string
        if (!is_null($schemeName) && !is_string($schemeName)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($schemeName, true), gettype($schemeName)), __LINE__);
        }
        $this->schemeName = $schemeName;
        return $this;
    }
}
