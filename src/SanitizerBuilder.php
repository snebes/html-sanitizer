<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace HtmlSanitizer;

use HtmlSanitizer\Extension\ExtensionInterface;
use HtmlSanitizer\NodeVisitor\ScriptNodeVisitor;
use HtmlSanitizer\NodeVisitor\StyleNodeVisitor;
use HtmlSanitizer\Parser\ParserInterface;
use Psr\Log\LoggerInterface;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @final
 */
class SanitizerBuilder
{
    /**
     * @var ExtensionInterface[]
     */
    private $extensions = [];

    /**
     * @var ParserInterface|null
     */
    private $parser;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    public function registerExtension(ExtensionInterface $extension)
    {
        $this->extensions[$extension->getName()] = $extension;
    }

    public function setParser(?ParserInterface $parser)
    {
        $this->parser = $parser;
    }

    public function setLogger(?LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function build(array $config): Sanitizer
    {
        $nodeVisitors = [];

        foreach ($config['extensions'] ?? [] as $extensionName) {
            if (!isset($this->extensions[$extensionName])) {
                throw new \InvalidArgumentException(sprintf(
                    'You have requested a non-existent sanitizer extension "%s" (available extensions: %s)',
                    $extensionName,
                    \implode(', ', \array_keys($this->extensions))
                ));
            }

            foreach ($this->extensions[$extensionName]->createNodeVisitors($config) as $tagName => $visitor) {
                $nodeVisitors[$tagName] = $visitor;
            }
        }

        // Always required visitors
        $nodeVisitors['script'] = new ScriptNodeVisitor();
        $nodeVisitors['style'] = new StyleNodeVisitor();

        return new Sanitizer(new DomVisitor($nodeVisitors), $config['max_input_length'] ?? 20000, $this->parser, $this->logger);
    }
}
