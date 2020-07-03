<?php
namespace deflou\components\anchors;

use deflou\interfaces\anchors\IAnchor;
use extas\components\exceptions\MissedOrUnknown;
use extas\components\jsonrpc\THasJsonRpcRequest;
use extas\interfaces\repositories\IRepository;

/**
 * Trait THasJsonRpcAnchor
 *
 * @method IRepository anchors()
 * @method notice($message, array $context)
 *
 * @package deflou\components\anchors
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasJsonRpcAnchor
{
    use THasJsonRpcRequest;

    /**
     * @return IAnchor|null
     */
    public function getJsonRpcAnchor(): ?IAnchor
    {
        /**
         * @var $anchor IAnchor
         */
        $data = $this->getJsonRpcRequest()->getData();
        $anchorId = $data['anchor'] ?? '';
        $anchor = $this->anchors()->one([IAnchor::FIELD__ID => $anchorId]);

        if (!$anchor) {
            $this->notice((new MissedOrUnknown('anchor "' . $anchorId . '"'))->getMessage(), $data);
            return null;
        }

        return $anchor;
    }
}
