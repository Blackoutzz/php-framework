<?php
namespace core\backend\network\http;

/**
 * http_header short summary.
 *
 * http_header description.
 *
 * @version 1.0
 * @author la_ma
 */

class header
{
    protected $title;

    protected $value;

    public function __toString()
    {
        return "{$this->title}: {$this->value}";
    }

}

