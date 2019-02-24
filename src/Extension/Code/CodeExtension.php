<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace HtmlSanitizer\Extension\Code;

use HtmlSanitizer\Extension\ExtensionInterface;

/**
 * @author Steve Nebes <snebes@gmail.com>
 *
 * @final
 */
class CodeExtension implements ExtensionInterface
{
    public function getName(): string
    {
        return 'code';
    }

    public function createNodeVisitors(array $config = []): array
    {
        return [
            'code' => new NodeVisitor\CodeNodeVisitor($config['tags']['code'] ?? []),
            'pre' => new NodeVisitor\PreNodeVisitor($config['tags']['pre'] ?? []),
        ];
    }
}
