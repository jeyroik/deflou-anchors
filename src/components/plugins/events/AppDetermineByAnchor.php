<?php
namespace deflou\components\plugins\events;

use deflou\components\anchors\THasAnchorData;
use deflou\interfaces\anchors\IHasAnchorData;
use deflou\interfaces\applications\IApplication;
use deflou\interfaces\servers\requests\IApplicationRequest;

/**
 * Class AppDetermineByAnchor
 *
 * @package deflou\components\plugins\events
 * @author jeyroik <jeyroik@gmail.com>
 */
class AppDetermineByAnchor extends ApplicationDetermine implements IHasAnchorData
{
    use THasAnchorData;

    /**
     * @param IApplicationRequest $request
     * @return IApplication|null
     */
    protected function dispatch(IApplicationRequest $request): ?IApplication
    {
        $data = $request->getParameterValue($request::PARAM__DATA, []);
        $anchor = $this->getAnchor($data);

        if ($anchor) {
            $anchorEvent = $anchor->getEvent();
            return $anchorEvent ? $anchorEvent->getApplication() : null;
        }

        return null;
    }
}
