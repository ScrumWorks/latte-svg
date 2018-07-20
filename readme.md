# Example init
```latte
<h1>
    Publications
    {svgFallback icon-group}
</h1>
```

Result HTML code IN IE may look like:
```html
<h1>
    Publications
    <svg xmlns="..." class="..." ...>
        ... content of symbol-defs.svg file ...
    </svg>
</h1>
```

# Example of icon
```latte
{svg bin, some-class, icon-group}
```

Result HTML code IN IE may look like:
```html
<svg class="some-class">
    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-bin"></use>
</svg>
```

Result in Chrome, FF, Opera may look like:
```html
<svg class="some-class">
    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="/images/svg/icon-group/symbol-defs.svg?v=1531909866#icon-bin"></use>
</svg>
```

# Purpose

This is a single purpose helper library with a macro definition for [Latte](https://latte.nette.org/), the PHP templating engine.
It loads SVG source file and embed it into HTML code in compile time.

Motivation for this is possibility to stylize SVG by CSS then. It is not (easily)
possible with SVG linked as an image like `<img src="icons/help.svg">`.


# Installation

Require library by [Composer](https://getcomposer.org/):
```
composer require dada-amater/latte-svg
```

Register extension in your `config.neon` and configure it:
```neon
extensions:
    latteSvg: DadaAmater\LatteSvg\Extension

latteSvg:
    baseDir: %wwwDir%
    wwwPathMask: 'images/svg/{group}/symbol-defs.svg'
```

# Caveats & Limitations

Because `embeddedSvg` is a macro, it is compiled into PHP only once and then is cached.
So, when you change the macro configuration, probably in NEON, you have to purge
Latte cache.
