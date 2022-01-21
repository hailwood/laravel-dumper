<?php

namespace Glhd\LaravelDumper\Casters;

use Glhd\LaravelDumper\Support\Properties;
use Illuminate\Contracts\Foundation\Application;
use Symfony\Component\VarDumper\Cloner\AbstractCloner;
use Symfony\Component\VarDumper\Cloner\Stub;

abstract class Caster
{
	public static array $targets = [];
	
	protected static bool $enabled = true;
	
	public static function register(Application $app): void
	{
		$app->singleton(static::class);
		
		foreach (static::$targets as $target) {
			AbstractCloner::$defaultCasters[$target] = static function($target, array $properties, Stub $stub, bool $is_nested, int $filter = 0) {
				return static::$enabled
					? app(static::class)->cast($target, new Properties($properties), $stub, $is_nested, $filter)
					: $properties;
			};
		}
	}
	
	public static function disable(): void
	{
		static::$enabled = false;
	}
	
	public static function enable(): void
	{
		static::$enabled = true;
	}
	
	abstract public function cast($target, Properties $properties, Stub $stub, bool $is_nested, int $filter = 0): array;
}
