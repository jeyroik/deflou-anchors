<?php
namespace deflou\components\plugins\events;

use deflou\components\anchors\THasJsonRpcAnchor;
use deflou\interfaces\applications\activities\IActivity;
use deflou\interfaces\applications\IApplication;

/**
 * Class EventDetermineByAnchor
 *
 * @method notice($message, array $context)
 *
 * @package deflou\components\plugins\events
 * @author jeyroik <jeyroik@gmail.com>
 */
class EventDetermineByAnchor extends EventDetermine
{
    use THasJsonRpcAnchor;

    public const REQUEST__ANCHOR = 'anchor';

    /**
     * @param IApplication $eventApp
     * @return IActivity|null
     */
    protected function dispatch(IApplication $eventApp): ?IActivity
    {
        $anchor = $this->getJsonRpcAnchor();

        return $anchor ? $anchor->getEvent() : null;
    }
}
