<?php


namespace App\Http\Controllers\Web\Install\Traits\Update;

use App\Helpers\DotenvEditor;

trait EnvTrait
{
	/**
	 * Update the current version to last version
	 *
	 * @param $last
	 * @throws \App\Exceptions\Custom\CustomException
	 */
	private function setCurrentVersion($last): void
	{
		DotenvEditor::setKey('APP_VERSION', $last);
		DotenvEditor::save();
	}
}
