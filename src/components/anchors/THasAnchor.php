<?php
namespace deflou\components\anchors;

use deflou\interfaces\anchors\IAnchor;
use deflou\interfaces\anchors\IHasAnchor;
use extas\interfaces\repositories\IRepository;

/**
 * Trait THasAnchor
 *
 * @property $config
 * @method IRepository anchors()
 *
 * @package deflou\components\anchors
 * @author jeyroik <jeyroik@gmail.com>
 */
trait THasAnchor
{
    /**
     * @return string
     */
    public function getAnchorId(): string
    {
        return $this->config[IHasAnchor::FIELD__ANCHOR_ID] ?? '';
    }

    /**
     * @return IAnchor|null
     */
    public function getAnchor(): ?IAnchor
    {
        return $this->anchors()->one([IAnchor::FIELD__ID => $this->getAnchorId()]);
    }

    /**
     * @param string $id
     * @return $this
     */
    public function setAnchorId(string $id)
    {
        $this->config[IHasAnchor::FIELD__ANCHOR_ID] = $id;

        return $this;
    }
}
