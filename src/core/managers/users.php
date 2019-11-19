<?php
namespace core\managers;
use core\manager;
use core\backend\components\mvc\user;

class users extends manager
{

    public function __construct($pcomponent = array())
    {
        if($pcomponent instanceof user)
        {
            if($pcomponent instanceof user)
            {
                $id = $pcomponent->get_id();
                $this->array[$id] = $pcomponent;
            }
        } 
        elseif(is_array($pcomponent) && count($pcomponent) >=1)
        {
            foreach($pcomponent as $component)
            {
                if($pcomponent instanceof user)
                {
                    $id = $component->get_id();
                    $this->array[$id] = $component;
                }
            }
        }
    }

}
