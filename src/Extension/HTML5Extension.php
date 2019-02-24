<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace HtmlSanitizer\Extension;

use HtmlSanitizer\NodeVisitor\NodeVisitorInterface;
use HtmlSanitizer\NodeVisitor\TagNodeVisitor;

/**
 * HTML5 Extension
 */
class HTML5Extension implements ExtensionInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'html5';
    }

    /**
     * @param array $config
     * @return NodeVisitorInterface[]
     */
    public function createNodeVisitors(array $config = []): array
    {
        return [
            'div' => new TagNodeVisitor('div', $config['tags']['div'] ?? []),
        ];
    }
}
