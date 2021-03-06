<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace SN\HtmlSanitizer\Node;

use SN\HtmlSanitizer\Sanitizer\StringSanitizerTrait;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @internal
 */
class TextNode extends AbstractNode
{
    use IsChildlessTrait;
    use StringSanitizerTrait;

    /**
     * @var string
     */
    private $text;

    /**
     * Default values.
     *
     * @param NodeInterface $parent
     * @param string        $text
     */
    public function __construct(NodeInterface $parent, string $text)
    {
        parent::__construct($parent);

        $this->text = $text;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->encodeHtmlEntities($this->text);
    }
}
