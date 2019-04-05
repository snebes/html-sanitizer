<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace SN\HtmlSanitizer\Node;

/**
 * Used by nodes which can have children.
 *
 * @author Steve Nebes <snebes@gmail.com>
 */
trait HasChildrenTrait
{
    /**
     * @var NodeInterface[]
     */
    private $children = [];

    public function addChild(NodeInterface $child)
    {
        $this->children[] = $child;
    }

    protected function renderChildren(): string
    {
        $rendered = '';
        foreach ($this->children as $child) {
            $rendered .= $child->render();
        }

        return $rendered;
    }
}
