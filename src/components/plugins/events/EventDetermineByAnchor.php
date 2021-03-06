<?php
namespace deflou\components\plugins\events;

use deflou\components\anchors\THasAnchorData;
use deflou\interfaces\anchors\IHasAnchorData;
use deflou\interfaces\applications\activities\IActivity;
use deflou\interfaces\servers\requests\IApplicationRequest;

/**
 * Class EventDetermineByAnchor
 *
 * @package deflou\components\plugins\events
 * @author jeyroik <jeyroik@gmail.com>
 */
class EventDetermineByAnchor extends EventDetermine implements IHasAnchorData
{
    use THasAnchorData;

    /**
     * @param IApplicationRequest $request
     * @return IActivity|null
     */
    protected function dispatch(IApplicationRequest $request): ?IActivity
    {
        $data = $request->getParameterValue($request::PARAM__DATA, []);
        $anchor = $this->getAnchor($data);

        return $anchor ? $anchor->getEvent() : null;
    }
}
