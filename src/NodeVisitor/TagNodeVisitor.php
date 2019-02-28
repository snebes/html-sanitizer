<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\NodeVisitor;

use SN\HtmlSanitizer\Model\Cursor;
use SN\HtmlSanitizer\Node\TagNode;
use SN\HtmlSanitizer\Node\TagNodeInterface;

class TagNodeVisitor implements NodeVisitorInterface
{
    /**
     * @var string
     */
    protected $qName;

    /**
     * @var array
     */
    protected $config;

    /**
     * Default values.
     *
     * @param string $qName
     * @param array  $config
     */
    public function __construct(string $qName, array $config = [])
    {
        $this->qName = $qName;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getDomNodeName(): string
    {
        return $this->qName;
    }

    /**
     * @return array
     */
    public function getSupportedNodeNames(): array
    {
        $supported = [$this->getDomNodeName()];
        $additional = $this->config['convert_elements'] ?? [];

        if (\is_array($additional)) {
            $supported = \array_merge($supported, $additional);
        }

        return $supported;
    }

    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     * @return bool
     */
    public function supports(\DOMNode $domNode, Cursor $cursor): bool
    {
        return \in_array($domNode->nodeName, $this->getSupportedNodeNames(), true);
    }

    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     * @return TagNodeInterface
     */
    public function createNode(\DOMNode $domNode, Cursor $cursor): TagNodeInterface
    {
        $node = new TagNode($cursor->node, $this->qName);
        $this->setAttributes($domNode, $node);

        return $node;
    }

    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     */
    public function enterNode(\DOMNode $domNode, Cursor $cursor)
    {
        $node = $this->createNode($domNode, $cursor);
        $cursor->node->addChild($node);

        $childless = $this->config['childless'] ?? false;

        if (true !== $childless) {
            $cursor->node = $node;
        }
    }

    /**
     * @param \DOMNode $domNode
     * @param Cursor   $cursor
     */
    public function leaveNode(\DOMNode $domNode, Cursor $cursor)
    {
        $childless = $this->config['childless'] ?? false;

        if (true !== $childless) {
            $cursor->node = $cursor->node->getParent();
        }
    }

    /**
     * Read the value of a DOMNode attribute.
     *
     * @param \DOMNode $domNode
     * @param string   $name
     *
     * @return null|string
     */
    protected function getAttribute(\DOMNode $domNode, string $name): ?string
    {
        if (!\count($domNode->attributes)) {
            return null;
        }

        /** @var \DOMAttr $attribute */
        foreach ($domNode->attributes as $attribute) {
            if ($attribute->name === $name) {
                return $attribute->value;
            }
        }

        return null;
    }

    /**
     * Set attributes from a DOM node to a sanitized node.
     *
     * @param \DOMNode      $domNode
     * @param TagNodeInterface $node
     */
    protected function setAttributes(\DOMNode $domNode, TagNodeInterface $node): void
    {
        // No attributes to worry about.
        if (!\count($domNode->attributes)) {
            return;
        }

        // No attributes allowed (empty array).
        $allowedAttributes = $this->config['allowed_attributes'] ?? null;

        if (\is_array($allowedAttributes) && 0 === \count($allowedAttributes)) {
            return;
        }

        // Make forbidden attributes an array.
        $forbiddenAttributes = $this->config['forbidden_attributes'] ?? [];

        if (!\is_array($forbiddenAttributes)) {
            $forbiddenAttributes = [];
        }

        /** @var \DOMAttr $attribute */
        foreach ($domNode->attributes as $attribute) {
            $name = \mb_strtolower($attribute->name);

            if (
                null === $allowedAttributes ||
                \in_array($name, $allowedAttributes, true) &&
                !\in_array($name, $forbiddenAttributes, true)
            ) {
                if ($name !== 'class') {
                    $node->setAttribute($name, $attribute->value);
                } else {
                    $value = $this->filterClasses($attribute->value);

                    if (!empty($value)) {
                        $node->setAttribute($name, $value);
                    }
                }
            }
        }
    }

    /**
     * @param string $value
     * @return string
     */
    private function filterClasses(string $value): string
    {
        if (empty($value)) {
            return '';
        }

        // No attributes allowed (empty array).
        $allowedClasses = $this->config['allowed_classes'] ?? null;

        if (\is_array($allowedClasses) && 0 === \count($allowedClasses)) {
            return '';
        }

        // Make forbidden attributes an array.
        $forbiddenClasses = $this->config['forbidden_classes'] ?? [];

        if (!\is_array($forbiddenClasses)) {
            $forbiddenClasses = [];
        }

        // Check them.
        $valid = [];
        $classes = \preg_split('/[\s]+/', $value);

        foreach ($classes as $class) {
            if (
                null === $allowedClasses ||
                \in_array($class, $allowedClasses, true) &&
                !\in_array($class, $forbiddenClasses, true)
            ) {
                $valid[] = $class;
            }
        }

        return \implode(' ', $valid);
    }
}
