<?php
namespace core\common;
use \stdClass as php_stdclass;

class stdclass extends php_stdclass
{

    public function __serialize(): array
    {
        return get_object_vars($this);
    }
    
    public function __deserialize(array $data): void
    {
        if(is_array($data) && count($data) >= 1)
        {
            foreach($data as $name => $datum)
            {
                $this->$name = $datum;
            }
        }
    }

}
