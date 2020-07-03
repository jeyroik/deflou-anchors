<?php
namespace deflou\interfaces\anchors;

use deflou\interfaces\applications\activities\IHasEvent;
use deflou\interfaces\triggers\IHasTrigger;
use extas\interfaces\IHasCreatedAt;
use extas\interfaces\IHasId;
use extas\interfaces\IHasType;
use extas\interfaces\IItem;
use extas\interfaces\players\IHasPlayer;

/**
 * Interface IAnchor
 *
 * Default types: general, player, trigger
 *
 * @package deflou\interfaces\anchors
 * @author jeyroik@gmail.com
 */
interface IAnchor extends IItem, IHasType, IHasCreatedAt, IHasEvent, IHasId, IHasPlayer, IHasTrigger
{
    public const SUBJECT = 'deflou.anchor';

    public const FIELD__CALLS_NUMBER = 'calls_number';
    public const FIELD__LAST_CALL_TIME = 'last_call_time';

    public const TYPE__GENERAL = 'general';
    public const TYPE__PLAYER = 'player';
    public const TYPE__TRIGGER = 'trigger';

    /**
     * @return int
     */
    public function getCallsNumber(): int;

    /**
     * @return int
     */
    public function getLastCallTime(): int;

    /**
     * @param int $callsNumber
     * @return IAnchor
     */
    public function setCallsNumber(int $callsNumber): IAnchor;

    /**
     * @return IAnchor
     */
    public function incCallsNumber(): IAnchor;

    /**
     * @param int $lastCallTime
     * @return IAnchor
     */
    public function setLastCallTime(int $lastCallTime): IAnchor;
}
