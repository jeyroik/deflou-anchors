<?php
namespace deflou\components\plugins\events;

use deflou\components\anchors\THasJsonRpcAnchor;
use deflou\interfaces\applications\IApplication;

/**
 * Class EventAppDetermineByAnchor
 *
 * @method notice($message, array $context)
 *
 * @package deflou\components\plugins\events
 * @author jeyroik <jeyroik@gmail.com>
 */
class EventAppDetermineByAnchor extends EventAppDetermine
{
    use THasJsonRpcAnchor;

    public const REQUEST__ANCHOR = 'anchor';

    /**
     * @return IApplication|null
     */
    protected function dispatch(): ?IApplication
    {
        $anchor = $this->getJsonRpcAnchor();

        if ($anchor) {
            $anchorEvent = $anchor->getEvent();
            return $anchorEvent ? $anchorEvent->getApplication() : null;
        }

        return null;
    }
}
