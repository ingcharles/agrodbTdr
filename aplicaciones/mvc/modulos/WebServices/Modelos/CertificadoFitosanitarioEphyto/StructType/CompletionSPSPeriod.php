<?php

namespace StructType;

use \WsdlToPhp\PackageBase\AbstractStructBase;

/**
 * This class stands for completionSPSPeriod StructType
 * @subpackage Structs
 */
class CompletionSPSPeriod extends AbstractStructBase
{
    /**
     * The StartDateTime
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:StartDateTime
     * @var \StructType\StartDateTime
     */
    public $StartDateTime;
    /**
     * The EndDateTime
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:EndDateTime
     * @var \StructType\EndDateTime
     */
    public $EndDateTime;
    /**
     * The DurationMeasure
     * Meta information extracted from the WSDL
     * - minOccurs: 0
     * - ref: ns3:DurationMeasure
     * @var \StructType\MeasureType
     */
    public $DurationMeasure;
    /**
     * Constructor method for completionSPSPeriod
     * @uses CompletionSPSPeriod::setStartDateTime()
     * @uses CompletionSPSPeriod::setEndDateTime()
     * @uses CompletionSPSPeriod::setDurationMeasure()
     * @param \StructType\StartDateTime $startDateTime
     * @param \StructType\EndDateTime $endDateTime
     * @param \StructType\MeasureType $durationMeasure
     */
    public function __construct(\StructType\StartDateTime $startDateTime = null, \StructType\EndDateTime $endDateTime = null, \StructType\MeasureType $durationMeasure = null)
    {
        $this
            ->setStartDateTime($startDateTime)
            ->setEndDateTime($endDateTime)
            ->setDurationMeasure($durationMeasure);
    }
    /**
     * Get StartDateTime value
     * @return \StructType\StartDateTime|null
     */
    public function getStartDateTime()
    {
        return $this->StartDateTime;
    }
    /**
     * Set StartDateTime value
     * @param \StructType\StartDateTime $startDateTime
     * @return \StructType\CompletionSPSPeriod
     */
    public function setStartDateTime(\StructType\StartDateTime $startDateTime = null)
    {
        $this->StartDateTime = $startDateTime;
        return $this;
    }
    /**
     * Get EndDateTime value
     * @return \StructType\EndDateTime|null
     */
    public function getEndDateTime()
    {
        return $this->EndDateTime;
    }
    /**
     * Set EndDateTime value
     * @param \StructType\EndDateTime $endDateTime
     * @return \StructType\CompletionSPSPeriod
     */
    public function setEndDateTime(\StructType\EndDateTime $endDateTime = null)
    {
        $this->EndDateTime = $endDateTime;
        return $this;
    }
    /**
     * Get DurationMeasure value
     * @return \StructType\MeasureType|null
     */
    public function getDurationMeasure()
    {
        return $this->DurationMeasure;
    }
    /**
     * Set DurationMeasure value
     * @param \StructType\MeasureType $durationMeasure
     * @return \StructType\CompletionSPSPeriod
     */
    public function setDurationMeasure(\StructType\MeasureType $durationMeasure = null)
    {
        $this->DurationMeasure = $durationMeasure;
        return $this;
    }
}
