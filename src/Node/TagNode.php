<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\Node;

use SN\HtmlSanitizer\Sanitizer\StringSanitizerTrait;

/**
 * A Tag Node.
 */
class TagNode extends AbstractNode implements TagNodeInterface
{
    use HasChildrenTrait;
    use StringSanitizerTrait;

    /**
     * @var string
     */
    private $qName;

    /**
     * @var array<string, string|null>
     */
    private $attributes = [];

    /**
     * @var bool
     */
    private $isChildless = false;

    /**
     * Default values.
     *
     * @param NodeInterface $parent
     * @param string        $qName
     * @param array         $attributes
     * @param bool          $isChildless
     */
    public function __construct(NodeInterface $parent, string $qName, array $attributes = [], bool $isChildless = false)
    {
        parent::__construct($parent);

        $this->qName = $qName;
        $this->attributes = $attributes;
        $this->isChildless = $isChildless;
    }

    /**
     * @return string
     */
    public function getTagName(): string
    {
        return $this->qName;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getAttribute(string $name): ?string
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * @param string      $name
     * @param string|null $value
     */
    public function setAttribute(string $name, ?string $value)
    {
        // Always use only the first declaration (ease sanitization)
        if (!\array_key_exists($name, $this->attributes)) {
            $this->attributes[$name] = $value;
        }
    }

    /**
     * @return string
     */
    public function render(): string
    {
        $tag = $this->getTagName();

        if ($this->isChildless) {
            return '<'.$tag.$this->renderAttributes().' />';
        }

        return '<'.$tag.$this->renderAttributes().'>'.$this->renderChildren().'</'.$tag.'>';
    }

    /**
     * @return string
     */
    private function renderAttributes(): string
    {
        $rendered = [];
        foreach ($this->attributes as $name => $value) {
            if (null === $value) {
                // Tag should be removed as a sanitizer found suspect data inside
                continue;
            }

            $attr = $this->encodeHtmlEntities($name);
            if ('' !== $value) {
                // In quirks mode, IE8 does a poor job producing innerHTML values.
                // If JavaScript does:
                //      nodeA.innerHTML = nodeB.innerHTML;
                // and nodeB contains (or even if ` was encoded properly):
                //      <div attr="``foo=bar">
                // then IE8 will produce:
                //      <div attr=``foo=bar>
                // as the value of nodeB.innerHTML and assign it to nodeA.
                // IE8's HTML parser treats `` as a blank attribute value and foo=bar becomes a separate attribute.
                // Adding a space at the end of the attribute prevents this by forcing IE8 to put double
                // quotes around the attribute when computing nodeB.innerHTML.
                if (false !== \mb_strpos($value, '`')) {
                    $value .= ' ';
                }

                $attr .= '="'.$this->encodeHtmlEntities($value).'"';
            }

            $rendered[] = $attr;
        }

        return $rendered ? ' '.\implode(' ', $rendered) : '';
    }
}
