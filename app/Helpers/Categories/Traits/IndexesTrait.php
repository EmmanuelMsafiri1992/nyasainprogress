<?php


namespace App\Helpers\Categories\Traits;

use App\Helpers\DBTool;

trait IndexesTrait
{
	/**
	 * Create the Nested Set indexes
	 *
	 * @return void
	 * @throws \App\Exceptions\Custom\CustomException
	 */
	public function createNestedSetIndexes(): void
	{
		$this->checkTablesAndColumns();
		
		// Make the 'lft' & 'rgt' columns unique and index the 'depth' column
		
		// Check if a unique indexes key exist, and drop it.
		DBTool::dropIndexIfExists($this->nestedTable, 'lft');
		DBTool::dropIndexIfExists($this->nestedTable, 'rgt');
		DBTool::dropIndexIfExists($this->nestedTable, 'depth');
		
		// Create indexes
		DBTool::createIndexIfNotExists($this->nestedTable, 'lft'); // Should be unique
		DBTool::createIndexIfNotExists($this->nestedTable, 'rgt'); // Should be unique
		DBTool::createIndexIfNotExists($this->nestedTable, 'depth');
	}
}
