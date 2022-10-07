<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for includedSPSNote StructType
 * @subpackage Structs
 */
class IncludedSPSNote extends AbstractStructBase
{
    /**
     * The Subject
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:Subject
     * @var string
     */
    public $Subject;
    /**
     * The Content
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:Content
     * @var \StructType\IncludedSPSNoteContent
     */
    public $Content;
    /**
     * Constructor method for includedSPSNote
     * @uses IncludedSPSNote::setSubject()
     * @uses IncludedSPSNote::setContent()
     * @param string $subject
     * @param \StructType\IncludedSPSNoteContent $content
     */
    public function __construct($subject = null, \StructType\IncludedSPSNoteContent $content = null)
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
     * @return \StructType\IncludedSPSNote
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
     * @return \StructType\IncludedSPSNoteContent|null
     */
    public function getContent()
    {
        return $this->Content;
    }
    /**
     * Set Content value
     * @param \StructType\IncludedSPSNoteContent $content
     * @return \StructType\IncludedSPSNote
     */
    public function setContent(\StructType\IncludedSPSNoteContent $content = null)
    {
        $this->Content = $content;
        return $this;
    }
}
