<?php

namespace DadaAmater\LatteSvg;

use Latte\MacroNode;
use Latte\PhpWriter;
use Latte\CompileException;
use Latte\Macros\MacroSet;
use Latte\Compiler;
use Milo;

class SvgMacros extends MacroSet
{
    /** @var Milo\EmbeddedSvg\Macro */
    private $macro;

    /** @var MacroSetting */
    private $settings;

    public function __construct(Compiler $compiler, MacroSetting $setting)
    {
        parent::__construct($compiler);

        $this->macro = new \Milo\EmbeddedSvg\Macro($compiler, $setting);
        $this->settings = $setting;
    }

    public static function install(Compiler $compiler, MacroSetting $setting)
    {
        $me = new static($compiler, $setting);
        $me->addMacro('svgFallback', [$me, 'svgFallback']);
        $me->addMacro('svg', [$me, 'svg']);
    }

    public function svgFallback(MacroNode $node, PhpWriter $writer)
    {

        $node->tokenizer->tokens[0][0] = $this->_getPath($node->tokenizer->tokens[0][0]);
        $ret = $this->macro->open($node, $writer);

        $ret = 'if (' . self::_ieCheckCondition() . ') {' . $ret .'}';
        return $ret;
    }

    public function svg(MacroNode $node, PhpWriter $writer)
    {
        $icon = str_replace(['"', '\''], '', $node->tokenizer->fetchWord());
        $class = str_replace(['"', '\''], '', $node->tokenizer->fetchWord());
        if ($class === false || $class === 'null') {
            $classString = null;
        }
        else {
            $classString = '. %escape( "' . $class . '")';
        }

        $group = str_replace(['"', '\''], '', $node->tokenizer->fetchWord());
        if ($group === false || $group === 'null') {
            $group = $this->settings->defaultGroup;
        }

        $iconPath = DIRECTORY_SEPARATOR . $this->_getPath($group);
        $filePath = $this->settings->baseDir . $iconPath;

        if (!is_file($filePath)) {
            throw new CompileException("Failed to load SVG content from '$filePath'.", 0);
        }

        $lastChange = filemtime($filePath);

        $iconUrl = $iconPath . '?v=' . $lastChange;

        return $writer->write('
            echo "<svg class=\"' . $this->settings->defaultIconClass . '";
            ' . ($classString !== null ? 'echo " "' . $classString : '') . ';
            echo "\"><use xmlns:xlink=\"http://www.w3.org/1999/xlink\" xlink:href=\"" . (' . self::_ieCheckCondition() . ' ? "" : "' . $iconUrl . '") . "#' . ($this->settings->iconNamePrefix !== null ? $this->settings->iconNamePrefix . '-' : '') . $icon . '\"></use></svg>"'
        );
    }

    private static function _ieCheckCondition(): string
    {
        return 'isset($_SERVER["HTTP_USER_AGENT"]) && preg_match(\'/MSIE/i\', $_SERVER["HTTP_USER_AGENT"])';
    }

    private function _getPath(string $group): string
    {
        return str_replace(['{group}'], [$group], $this->settings->wwwPathMask);
    }
}
