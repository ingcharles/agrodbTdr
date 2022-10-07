<?php

namespace Agrodb\WebServices\Modelos\CertificadoFitosanitarioEphyto\StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for Envelope StructType
 * Meta information extracted from the WSDL
 * - type: tns:Envelope
 * @subpackage Structs
 */
class Envelope extends EnvelopeHeader
{
    /**
     * The Content
     * Meta information extracted from the WSDL
     * - form: qualified
     * - minOccurs: 0
     * @var string
     */
    public $Content;
    /**
     * Constructor method for Envelope
     * @uses Envelope::setContent()
     * @param string $content
     */
    public function __construct($content = null)
    {
        $this
            ->setContent($content);
    }
    /**
     * Get Content value
     * @return string|null
     */
    public function getContent()
    {
        return $this->Content;
    }
    /**
     * Set Content value
     * @param string $content
     * @return \StructType\Envelope
     */
    public function setContent($content = null)
    {
        // validation for constraint: string
        if (!is_null($content) && !is_string($content)) {
            throw new \InvalidArgumentException(sprintf('Invalid value %s, please provide a string, %s given', var_export($content, true), gettype($content)), __LINE__);
        }
        $this->Content = $content;
        return $this;
    }
}
