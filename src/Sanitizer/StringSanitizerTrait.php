<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\Sanitizer;

/**
 * @internal
 */
trait StringSanitizerTrait
{
    /**
     * @var array<string, string>
     */
    private static $replacements = [
        // Some DB engines will transform UTF8 full-width characters their classical version
        // if the data is saved in a non-UTF8 field
        '＜' => '&#xFF1C;',
        '＞' => '&#xFF1E;',
        '＋' => '&#xFF0B;',
        '＝' => '&#xFF1D;',
        '＠' => '&#xFF20;',
        '｀' => '&#xFF40;',
    ];

    /**
     * @param string $string
     * @return string
     */
    public function encodeHtmlEntities(string $string): string
    {
        $string = \htmlspecialchars($string, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $string = \str_replace(\array_keys(self::$replacements), \array_values(self::$replacements), $string);

        return $string;
    }
}
