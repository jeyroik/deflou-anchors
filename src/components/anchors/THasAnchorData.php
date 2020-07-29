<?php
namespace deflou\components\anchors;

use deflou\interfaces\anchors\IAnchor;
use deflou\interfaces\anchors\IHasAnchorData;
use extas\components\exceptions\MissedOrUnknown;
use extas\interfaces\repositories\IRepository;

/**
 * Trait THasAnchorData
 *
 * @method IRepository anchors()
 * @method notice($message, array $context)
 *
 * @package deflou\components\anchors
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasAnchorData
{
    /**
     * @param array $data
     * @return IAnchor|null
     */
    public function getAnchor(array $data): ?IAnchor
    {
        $anchorId = $data[IHasAnchorData::FIELD__ANCHOR] ?? '';
        $anchor = $this->anchors()->one([IAnchor::FIELD__ID => $anchorId]);

        if (!$anchor) {
            $this->notice((new MissedOrUnknown('anchor "' . $anchorId . '"'))->getMessage(), $data);
            return null;
        }

        return $anchor;
    }
}
