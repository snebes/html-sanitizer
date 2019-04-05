<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\NodeVisitor;

use DOMNode;
use SN\HtmlSanitizer\Model\Cursor;
use SN\HtmlSanitizer\Node\TagNode;
use SN\HtmlSanitizer\Node\TagNodeInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @final
 */
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
     * @param array  $options
     */
    public function __construct(string $qName, array $options = [])
    {
        $this->qName = $qName;
        $this->config = $this->configureOptions($options);
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
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     *
     * @return bool
     */
    public function supports(DOMNode $domNode, Cursor $cursor): bool
    {
        return \in_array($domNode->nodeName, $this->getSupportedNodeNames(), true);
    }

    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     *
     * @return TagNodeInterface
     */
    public function createNode(DOMNode $domNode, Cursor $cursor): TagNodeInterface
    {
        $childless = $this->config['childless'] ?? false;

        $node = new TagNode($cursor->node, $this->qName, [], $childless);
        $this->setAttributes($domNode, $node);

        return $node;
    }

    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function enterNode(DOMNode $domNode, Cursor $cursor)
    {
        $node = $this->createNode($domNode, $cursor);
        $cursor->node->addChild($node);

        $childless = $this->config['childless'] ?? false;

        if (true !== $childless) {
            $cursor->node = $node;
        }
    }

    /**
     * @param DOMNode $domNode
     * @param Cursor  $cursor
     */
    public function leaveNode(DOMNode $domNode, Cursor $cursor)
    {
        $childless = $this->config['childless'] ?? false;

        if (true !== $childless) {
            $cursor->node = $cursor->node->getParent();
        }
    }

    /**
     * Set attributes from a DOM node to a sanitized node.
     *
     * @param DOMNode          $domNode
     * @param TagNodeInterface $node
     */
    protected function setAttributes(DOMNode $domNode, TagNodeInterface $node): void
    {
        // No attributes to worry about.
        if (!\count($domNode->attributes)) {
            return;
        }

        // No attributes allowed (empty array).
        $allowed = $this->config['allowed_attributes'];

        if (\is_array($allowed) && 0 === \count($allowed)) {
            return;
        }

        /** @var \DOMAttr $attribute */
        foreach ($domNode->attributes as $attribute) {
            $name = \mb_strtolower($attribute->name);

            if (
                (null === $allowed || \in_array($name, $allowed, true)) &&
                !\in_array($name, $this->config['blocked_attributes'], true)
            ) {
                if ('class' !== $name) {
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
     *
     * @return string
     */
    private function filterClasses(string $value): string
    {
        if (empty($value)) {
            return '';
        }

        // No class allowed (empty array).
        $allowed = $this->config['allowed_classes'];

        if (\is_array($allowed) && 0 === \count($allowed)) {
            return '';
        }

        // Check them.
        $valid = [];
        $classes = \preg_split('/[\s]+/', $value) ?: [];

        foreach ($classes as $class) {
            if (
                (null === $allowed || \in_array($class, $allowed, true)) &&
                !\in_array($class, $this->config['blocked_classes'], true)
            ) {
                $valid[] = $class;
            }
        }

        return \implode(' ', $valid);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    private function configureOptions(array $options): array
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'allowed_attributes' => null,
            'allowed_classes' => null,
            'blocked_attributes' => [],
            'blocked_classes' => [],
            'childless' => false,
            'convert_elements' => [],
        ]);
        $resolver->setAllowedTypes('allowed_attributes', ['null', 'array', 'string']);
        $resolver->setAllowedTypes('allowed_classes', ['null', 'array', 'string']);
        $resolver->setAllowedTypes('blocked_attributes', ['array', 'string']);
        $resolver->setAllowedTypes('blocked_classes', ['array', 'string']);
        $resolver->setAllowedTypes('childless', ['bool']);
        $resolver->setAllowedTypes('convert_elements', ['array', 'string']);

        $stringToArrayNormalizer = function (Options $options, $value) {
            if (\is_string($value)) {
                $value = \preg_split('/[\s]+/', $value, \PREG_SPLIT_NO_EMPTY) ?: [];
            }

            return $value;
        };

        $resolver->setNormalizer('allowed_attributes', $stringToArrayNormalizer);
        $resolver->setNormalizer('allowed_classes', $stringToArrayNormalizer);
        $resolver->setNormalizer('blocked_attributes', $stringToArrayNormalizer);
        $resolver->setNormalizer('blocked_classes', $stringToArrayNormalizer);
        $resolver->setNormalizer('convert_elements', $stringToArrayNormalizer);

        return $resolver->resolve($options);
    }
}
