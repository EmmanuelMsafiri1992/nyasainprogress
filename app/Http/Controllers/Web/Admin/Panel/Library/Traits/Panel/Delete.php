<?php


namespace App\Http\Controllers\Web\Admin\Panel\Library\Traits\Panel;

trait Delete
{
    /*
    |--------------------------------------------------------------------------
    |                                   DELETE
    |--------------------------------------------------------------------------
    */

    /**
     * Delete a row from the database.
     *
	 * @param $id
	 * @return int
     */
    public function delete($id): int
    {
        return $this->model->destroy($id);
    }
}
