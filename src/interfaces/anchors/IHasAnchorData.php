<?php
namespace deflou\interfaces\anchors;

/**
 * Interface IHasAnchorData
 *
 * @package deflou\interfaces\anchors
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IHasAnchorData
{
    public const FIELD__ANCHOR = 'anchor';

    /**
     * @return IAnchor|null
     */
    public function getAnchor(array $data): ?IAnchor;
}
