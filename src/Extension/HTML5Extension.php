<?php
/**
 * (c) Steve Nebes <snebes@gmail.com>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SN\HtmlSanitizer\Extension;

use SN\HtmlSanitizer\NodeVisitor\NodeVisitorInterface;
use SN\HtmlSanitizer\NodeVisitor\TagNodeVisitor;

//'allowed_attributes'   => null,
//                    'allowed_classes'      => null,
//                    'forbidden_attributes' => null,
//                    'forbidden_classes'    => null,
//                    'childless'            => false,
//                    'convert_elements'     => null,
//                ]),

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
            // Main root
            'html'       => new TagNodeVisitor('html', $config['tags']['html'] ?? []),

            // Document metadata
            'base'       => new TagNodeVisitor('base', $config['tags']['base'] ?? []),
            'head'       => new TagNodeVisitor('head', $config['tags']['head'] ?? []),
            'link'       => new TagNodeVisitor('link', $config['tags']['link'] ?? []),
            'meta'       => new TagNodeVisitor('meta', $config['tags']['meta'] ?? []),
            'style'      => new TagNodeVisitor('style', $config['tags']['style'] ?? []),
            'title'      => new TagNodeVisitor('title', $config['tags']['title'] ?? []),

            // Sectioning root
            'body'       => new TagNodeVisitor('body', $config['tags']['body'] ?? []),

            // Content sectioning
            'address'    => new TagNodeVisitor('address', $config['tags']['address'] ?? []),
            'article'    => new TagNodeVisitor('article', $config['tags']['article'] ?? []),
            'aside'      => new TagNodeVisitor('aside', $config['tags']['aside'] ?? []),
            'footer'     => new TagNodeVisitor('footer', $config['tags']['footer'] ?? []),
            'header'     => new TagNodeVisitor('header', $config['tags']['header'] ?? []),
            'h1'         => new TagNodeVisitor('h1', $config['tags']['h1'] ?? []),
            'h2'         => new TagNodeVisitor('h2', $config['tags']['h2'] ?? []),
            'h3'         => new TagNodeVisitor('h3', $config['tags']['h3'] ?? []),
            'h4'         => new TagNodeVisitor('h4', $config['tags']['h4'] ?? []),
            'h5'         => new TagNodeVisitor('h5', $config['tags']['h5'] ?? []),
            'h6'         => new TagNodeVisitor('h6', $config['tags']['h6'] ?? []),
            'hgroup'     => new TagNodeVisitor('hgroup', $config['tags']['hgroup'] ?? []),
            'main'       => new TagNodeVisitor('main', $config['tags']['main'] ?? []),
            'nav'        => new TagNodeVisitor('nav', $config['tags']['nav'] ?? []),
            'section'    => new TagNodeVisitor('section', $config['tags']['section'] ?? []),

            // Text content
            'blockquote' => new TagNodeVisitor('blockquote', $config['tags']['blockquote'] ?? []),
            'dd'         => new TagNodeVisitor('dd', $config['tags']['dd'] ?? []),
            'dir'        => new TagNodeVisitor('dir', $config['tags']['dir'] ?? []),
            'div'        => new TagNodeVisitor('div', $config['tags']['div'] ?? []),
            'dl'         => new TagNodeVisitor('dl', $config['tags']['dl'] ?? []),
            'dt'         => new TagNodeVisitor('dt', $config['tags']['dt'] ?? []),
            'figcaption' => new TagNodeVisitor('figcaption', $config['tags']['figcaption'] ?? []),
            'figure'     => new TagNodeVisitor('figure', $config['tags']['figure'] ?? []),
            'hr'         => new TagNodeVisitor('hr', $config['tags']['hr'] ?? ['childless' => true]),
            'li'         => new TagNodeVisitor('li', $config['tags']['li'] ?? []),
            'ol'         => new TagNodeVisitor('ol', $config['tags']['ol'] ?? []),
            'p'          => new TagNodeVisitor('p', $config['tags']['p'] ?? []),
            'pre'        => new TagNodeVisitor('pre', $config['tags']['pre'] ?? []),
            'ul'         => new TagNodeVisitor('ul', $config['tags']['ul'] ?? []),

            // Inline text semantics
            'a'          => new TagNodeVisitor('a', $config['tags']['a'] ?? []),
            'abbr'       => new TagNodeVisitor('abbr', $config['tags']['abbr'] ?? []),
            'b'          => new TagNodeVisitor('b', $config['tags']['b'] ?? []),
            'bdi'        => new TagNodeVisitor('bdi', $config['tags']['bdi'] ?? []),
            'bdo'        => new TagNodeVisitor('bdo', $config['tags']['bdo'] ?? []),
            'br'         => new TagNodeVisitor('br', $config['tags']['br'] ?? ['childless' => true]),
            'cite'       => new TagNodeVisitor('cite', $config['tags']['cite'] ?? []),
            'code'       => new TagNodeVisitor('code', $config['tags']['code'] ?? []),
            'data'       => new TagNodeVisitor('data', $config['tags']['data'] ?? []),
            'dfn'        => new TagNodeVisitor('dfn', $config['tags']['dfn'] ?? []),
            'em'         => new TagNodeVisitor('em', $config['tags']['em'] ?? []),
            'i'          => new TagNodeVisitor('i', $config['tags']['i'] ?? []),
            'kbd'        => new TagNodeVisitor('kbd', $config['tags']['kbd'] ?? []),
            'mark'       => new TagNodeVisitor('mark', $config['tags']['mark'] ?? []),
            'q'          => new TagNodeVisitor('q', $config['tags']['q'] ?? []),
            'rb'         => new TagNodeVisitor('rb', $config['tags']['rb'] ?? []),
            'rp'         => new TagNodeVisitor('rp', $config['tags']['rp'] ?? []),
            'rt'         => new TagNodeVisitor('rt', $config['tags']['rt'] ?? []),
            'rtc'        => new TagNodeVisitor('rtc', $config['tags']['rtc'] ?? []),
            'ruby'       => new TagNodeVisitor('ruby', $config['tags']['ruby'] ?? []),
            's'          => new TagNodeVisitor('s', $config['tags']['s'] ?? []),
            'samp'       => new TagNodeVisitor('samp', $config['tags']['samp'] ?? []),
            'small'      => new TagNodeVisitor('small', $config['tags']['small'] ?? []),
            'span'       => new TagNodeVisitor('span', $config['tags']['span'] ?? []),
            'strong'     => new TagNodeVisitor('strong', $config['tags']['strong'] ?? []),
            'sub'        => new TagNodeVisitor('sub', $config['tags']['sub'] ?? []),
            'sup'        => new TagNodeVisitor('sup', $config['tags']['sup'] ?? []),
            'time'       => new TagNodeVisitor('time', $config['tags']['time'] ?? []),
            'tt'         => new TagNodeVisitor('tt', $config['tags']['tt'] ?? []),
            'u'          => new TagNodeVisitor('u', $config['tags']['u'] ?? []),
            'var'        => new TagNodeVisitor('var', $config['tags']['var'] ?? []),
            'wbr'        => new TagNodeVisitor('wbr', $config['tags']['wbr'] ?? []),

            // Image and multimedia
            'area'       => new TagNodeVisitor('area', $config['tags']['area'] ?? []),
            'audio'      => new TagNodeVisitor('audio', $config['tags']['audio'] ?? []),
            'img'        => new TagNodeVisitor('img', $config['tags']['img'] ?? ['childless' => true]),
            'map'        => new TagNodeVisitor('map', $config['tags']['map'] ?? []),
            'track'      => new TagNodeVisitor('track', $config['tags']['track'] ?? []),
            'video'      => new TagNodeVisitor('video', $config['tags']['video'] ?? []),

            // Embedded content
            'applet'     => new TagNodeVisitor('applet', $config['tags']['applet'] ?? []),
            'embed'      => new TagNodeVisitor('embed', $config['tags']['embed'] ?? []),
            'iframe'     => new TagNodeVisitor('iframe', $config['tags']['iframe'] ?? []),
            'noembed'    => new TagNodeVisitor('noembed', $config['tags']['noembed'] ?? []),
            'object'     => new TagNodeVisitor('object', $config['tags']['object'] ?? []),
            'param'      => new TagNodeVisitor('param', $config['tags']['param'] ?? []),
            'picture'    => new TagNodeVisitor('picture', $config['tags']['picture'] ?? []),
            'source'     => new TagNodeVisitor('source', $config['tags']['source'] ?? []),

            // Scripting
            'canvas'     => new TagNodeVisitor('canvas', $config['tags']['canvas'] ?? []),
            'noscript'   => new TagNodeVisitor('noscript', $config['tags']['noscript'] ?? []),
            'script'     => new TagNodeVisitor('script', $config['tags']['script'] ?? []),

            // Demarcating edits
            'del'        => new TagNodeVisitor('del', $config['tags']['del'] ?? []),
            'ins'        => new TagNodeVisitor('ins', $config['tags']['ins'] ?? []),

            // Table content
            'caption'    => new TagNodeVisitor('caption', $config['tags']['caption'] ?? []),
            'col'        => new TagNodeVisitor('col', $config['tags']['col'] ?? []),
            'colgroup'   => new TagNodeVisitor('colgroup', $config['tags']['colgroup'] ?? []),
            'table'      => new TagNodeVisitor('table', $config['tags']['table'] ?? []),
            'tbody'      => new TagNodeVisitor('tbody', $config['tags']['tbody'] ?? []),
            'td'         => new TagNodeVisitor('td', $config['tags']['td'] ?? []),
            'tfoot'      => new TagNodeVisitor('tfoot', $config['tags']['tfoot'] ?? []),
            'th'         => new TagNodeVisitor('th', $config['tags']['th'] ?? []),
            'thead'      => new TagNodeVisitor('thead', $config['tags']['thead'] ?? []),
            'tr'         => new TagNodeVisitor('tr', $config['tags']['tr'] ?? []),

            // Forms
            'button'     => new TagNodeVisitor('button', $config['tags']['button'] ?? []),
            'datalist'   => new TagNodeVisitor('datalist', $config['tags']['datalist'] ?? []),
            'fieldset'   => new TagNodeVisitor('fieldset', $config['tags']['fieldset'] ?? []),
            'form'       => new TagNodeVisitor('form', $config['tags']['form'] ?? []),
            'input'      => new TagNodeVisitor('input', $config['tags']['input'] ?? []),
            'label'      => new TagNodeVisitor('label', $config['tags']['label'] ?? []),
            'legend'     => new TagNodeVisitor('legend', $config['tags']['legend'] ?? []),
            'meter'      => new TagNodeVisitor('meter', $config['tags']['meter'] ?? []),
            'optgroup'   => new TagNodeVisitor('optgroup', $config['tags']['optgroup'] ?? []),
            'option'     => new TagNodeVisitor('option', $config['tags']['option'] ?? []),
            'output'     => new TagNodeVisitor('output', $config['tags']['output'] ?? []),
            'progress'   => new TagNodeVisitor('progress', $config['tags']['progress'] ?? []),
            'select'     => new TagNodeVisitor('select', $config['tags']['select'] ?? []),
            'textarea'   => new TagNodeVisitor('textarea', $config['tags']['textarea'] ?? []),

            // Interactive elements
            'details'    => new TagNodeVisitor('details', $config['tags']['details'] ?? []),
            'dialog'     => new TagNodeVisitor('dialog', $config['tags']['dialog'] ?? []),
            'menu'       => new TagNodeVisitor('menu', $config['tags']['menu'] ?? []),
            'menuitem'   => new TagNodeVisitor('menuitem', $config['tags']['menuitem'] ?? []),
            'summary'    => new TagNodeVisitor('summary', $config['tags']['summary'] ?? []),

            // Web Components
            'content'    => new TagNodeVisitor('content', $config['tags']['content'] ?? []),
            'element'    => new TagNodeVisitor('element', $config['tags']['element'] ?? []),
            'shadow'     => new TagNodeVisitor('shadow', $config['tags']['shadow'] ?? []),
            'slot'       => new TagNodeVisitor('slot', $config['tags']['slot'] ?? []),
            'template'   => new TagNodeVisitor('template', $config['tags']['template'] ?? []),
        ];
    }
}
