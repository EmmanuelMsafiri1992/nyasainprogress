<?php


namespace App\Http\Middleware\Install;

trait CheckInstallation
{
	/**
	 * Check if the website has already been installed
	 *
	 * @return bool
	 * @throws \App\Exceptions\Custom\CustomException
	 */
	protected function isInstalled(): bool
	{
		if ($this->installationIsComplete()) {
			createTheInstalledFile(true);
			$this->clearInstallationSession();
		}
		
		// Check if the app is installed
		return appIsInstalled();
	}
	
	/**
	 * @return bool
	 * @throws \App\Exceptions\Custom\CustomException
	 */
	protected function isNotInstalled(): bool
	{
		return !$this->isInstalled();
	}
	
	/**
	 * Check if installation is processing
	 *
	 * @return bool
	 */
	protected function installationIsInProgress(): bool
	{
		return (
			!empty(session('databaseImported'))
			|| !empty(session('cronJobs'))
			|| !empty(session('installFinished'))
		);
	}
	
	/**
	 * @return bool
	 */
	protected function installationIsNotInProgress(): bool
	{
		return !$this->installationIsInProgress();
	}
	
	// PRIVATE
	
	/**
	 * Check if the installation is complete
	 * If the session contains "installFinished" which is equal to 1, this means that the website has just been installed.
	 *
	 * @return bool
	 */
	private function installationIsComplete(): bool
	{
		return (session('installFinished') == 1);
	}
	
	/**
	 * Clear the installation session
	 * Remove the "installFinished" key from the session
	 *
	 * @return void
	 */
	private function clearInstallationSession(): void
	{
		session()->forget('installFinished');
		session()->flush();
	}
}
