<?php
namespace deflou\components\anchors;

use deflou\components\applications\activities\THasEvent;
use deflou\components\triggers\THasTrigger;
use deflou\interfaces\anchors\IAnchor;
use extas\components\Item;
use extas\components\players\THasPlayer;
use extas\components\THasCreatedAt;
use extas\components\THasId;
use extas\components\THasType;

/**
 * Class Anchor
 * 
 * @package deflou\components\anchors
 * @author jeyroik@gmail.com
 */
class Anchor extends Item implements IAnchor
{
    use THasId;
    use THasType;
    use THasCreatedAt;
    use THasEvent;
    use THasPlayer;
    use THasTrigger;

    /**
     * @return int
     */
    public function getCallsNumber(): int
    {
        return $this->config[static::FIELD__CALLS_NUMBER] ?? 0;
    }

    /**
     * @return int
     */
    public function getLastCallTime(): int
    {
        return $this->config[static::FIELD__LAST_CALL_TIME] ?? 0;
    }

    /**
     * @param int $callsNumber
     * @return IAnchor
     */
    public function setCallsNumber(int $callsNumber): IAnchor
    {
        $this->config[static::FIELD__CALLS_NUMBER] = $callsNumber;
        
        return $this;
    }

    /**
     * @param int $lastCallTime
     * @return IAnchor
     */
    public function setLastCallTime(int $lastCallTime): IAnchor
    {
        $this->config[static::FIELD__LAST_CALL_TIME] = $lastCallTime;
        
        return $this;
    }

    /**
     * @return IAnchor
     */
    public function incCallsNumber(): IAnchor
    {
        return $this->setCallsNumber($this->getCallsNumber()+1);
    }

    /**
     * @return string
     */
    protected function getSubjectForExtension(): string
    {
        return static::SUBJECT;
    }
}
