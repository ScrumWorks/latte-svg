<?php

namespace DadaAmater\LatteSvg;

use Nette\DI\CompilerExtension;


class Extension extends CompilerExtension
{
	public function loadConfiguration()
	{
		$this->getContainerBuilder()
			->getDefinition('latte.latteFactory')
				->addSetup('?->onCompile[] = function ($engine) { '
					. SvgMacros::class . '::install($engine->getCompiler(), '
					. MacroSetting::class . '::createFromArray2(?)'
					. ');}',
					['@self', $this->getConfig()]
				);
	}
}
