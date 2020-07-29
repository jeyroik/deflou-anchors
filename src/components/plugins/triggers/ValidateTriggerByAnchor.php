<?php
namespace deflou\components\plugins\triggers;

use deflou\components\anchors\THasAnchorData;
use deflou\interfaces\stages\IStageIsValidTrigger;
use deflou\interfaces\triggers\events\IApplicationEvent;
use deflou\interfaces\triggers\ITrigger;
use extas\components\plugins\Plugin;

/**
 * Class ValidateTriggerByAnchor
 *
 * @package deflou\components\plugins\triggers
 * @author jeyroik <jeyroik@gmail.com>
 */
class ValidateTriggerByAnchor extends Plugin implements IStageIsValidTrigger
{
    use THasAnchorData;

    /**
     * @param IApplicationEvent $applicationEvent
     * @param ITrigger $trigger
     * @return bool
     */
    public function __invoke(IApplicationEvent $applicationEvent, ITrigger $trigger): bool
    {
        $artifacts = $applicationEvent->getParameterValue($applicationEvent::PARAM__ARTIFACTS, []);
        $anchor = $this->getAnchor($artifacts);

        if (!$anchor) {
            return false;
        }

        if ($anchor->getType() == $anchor::TYPE__TRIGGER) {
            return $anchor->getTriggerName() == $trigger->getName();
        } elseif ($anchor->getType() == $anchor::TYPE__PLAYER) {
            return $anchor->getPlayerName() == $trigger->getPlayerName();
        }

        return $anchor->getEventName() == $trigger->getEventName();
    }
}
