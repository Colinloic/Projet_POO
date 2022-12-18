<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FileExistExtension extends AbstractExtension
{

	private $kernelProjectDir;

	public function __construct(string $kernelProjectDir)
	{
		$this->kernelProjectDir = $kernelProjectDir;
	}

	/**
	 * Return the functions registered as twig extensions
	 *
	 * @return array
	 */
	public function getFunctions(): array
	{
		return array(
			'file_exists' => new TwigFunction('file_exist', array($this, 'file_exist')),
		);
	}

	public function file_exist(string $fileRelativePath): bool
	{
		return file_exists($this->kernelProjectDir.'/public/static/uploads/pokemons/'.$fileRelativePath);
	}
}