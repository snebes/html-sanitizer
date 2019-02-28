<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace SN\HtmlSanitizer\Extension;

use SN\HtmlSanitizer\NodeVisitor\NodeVisitorInterface;

/**
 * A sanitizer extension allows to easily add features to the sanitizer to handle specific tags.
 *
 * @author Steve Nebes <snebes@gmail.com>
 */
interface ExtensionInterface
{
    /**
     * Return this extension name, which will be used in the sanitizer configuration.
     */
    public function getName(): string;

    /**
     * Return a list of node visitors to register in the sanitizer following the format tagName => visitor.
     * For instance: 'strong' => new StrongVisitor($config).
     *
     * @param array $config The configuration given by the user of the library.
     *
     * @return NodeVisitorInterface[]
     */
    public function createNodeVisitors(array $config = []): array;
}
