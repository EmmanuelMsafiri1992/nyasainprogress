<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HtmlMinify
{
	/**
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		// Exception for Install & Upgrade Routes
		if (isFromInstallOrUpgradeProcess()) {
			return $next($request);
		}
		
		$response = $next($request);
		
		// Exceptions for Admin panel
		if (isAdminPanel() || empty($response->getContent())) {
			return $response;
		}
		
		// Don't minify the HTML if the option is not activated
		if (config('settings.optimization.minify_html_activation') == 0) {
			return $response;
		}
		
		// Get HTML
		$buffer = $response->getContent();
		
		// Minify
		$buffer = $this->minify($buffer);
		
		// Output the minified HTML
		return $response->setContent($buffer);
	}
	
	/**
	 * Minify the HTML buffer
	 *
	 * @param $buffer
	 * @return mixed
	 */
	private function minify($buffer)
	{
		$search = [
			'/\r/us',              // Remove new-lines
			'/\n/us',              // Remove new-lines
			'/<!--(.|\s)*?-->/us', // Remove HTML comments
			'/(\s)+/us',           // Shorten multiple whitespace sequences
		];
		
		$replace = [
			'',
			'',
			'',
			' ',
		];
		
		$minifiedBuffer = preg_replace($search, $replace, $buffer);
		if (!empty($minifiedBuffer)) {
			return $minifiedBuffer;
		}
		
		return $buffer;
	}
	
	/**
	 * Minify the HTML buffer (Old version)
	 *
	 * @param $buffer
	 * @return mixed
	 */
	private function minify2($buffer)
	{
		$search = [
			'/>[^\S ]+/us',        // Strip whitespaces after tags, except space
			'/[^\S ]+</us',        // Strip whitespaces before tags, except space
			'/(\s)+/us',           // Shorten multiple whitespace sequences
			'/<!--(.|\s)*?-->/us', // Remove HTML comments
		];
		
		$replace = [
			'>',
			'<',
			'\\1',
			'',
		];
		
		$minifiedBuffer = preg_replace($search, $replace, $buffer);
		if (!empty($minifiedBuffer)) {
			return $minifiedBuffer;
		}
		
		return $buffer;
	}
}
