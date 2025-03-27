<?php


namespace App\Models\Traits\Common;

trait HasActiveColumn
{
    public function getActiveHtml(): ?string
    {
        if (!isset($this->active)) return null;
        
        return ajaxCheckboxDisplay($this->{$this->primaryKey}, $this->getTable(), 'active', $this->active);
    }
}
