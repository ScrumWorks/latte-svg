<?php

namespace DadaAmater\LatteSvg;


class MacroSetting extends \Milo\EmbeddedSvg\MacroSetting
{
	/** @var string */
	public $wwwPathMask;

	public static function createFromArray2(array $setting): self
	{
		$me = new self;
		foreach ($setting as $property => $value) {
			$me->{$property} = $value;
		}
		return $me;
	}
}
