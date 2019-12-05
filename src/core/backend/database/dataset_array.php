<?php
namespace core\backend\database;
use core\backend\database\dataset;
use core\common\components\stack;
use core\common\sorting_order;

/**
 * Dataset Array
 * 
 * Morphable Array of data
 * 
 * @Version 1.0
 * @Author  Mickael Nadeau
 * @Twitter @Mick4Secure
 * @Github  @Blackoutzz
 * @Website https://Blackoutzz.me
 **/

class dataset_array extends stack
{

    public function order_by($pvariable_name = "name",$psorting_order = table_model_array_sorting_order::ascending_order)
    {
        if(!is_string($pvariable_name)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $method_name = "get_".$pvariable_name;
                if(method_exists($array_item,$method_name))
                {
                    $value = $array_item->$method_name();
                    $new_array[$value] = $array_item;
                }
            }
        }
        if(sorting_order::ascending == $psorting_order) ksort($new_array);
        elseif(sorting_order::descending == $psorting_order) krsort($new_array);
        else return $this->array;

        $this->array = array_values($new_array);
        return $this->array;
    }

    public function get_ordered_by($pvariable_name = "name",$psorting_order = table_model_array_sorting_order::ascending_order)
    {
        if(!is_string($pvariable_name)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $method_name = "get_".$pvariable_name;
                if(method_exists($array_item,$method_name))
                {
                    $value = $array_item->$method_name();
                    $new_array[$value] = $array_item;
                }
            }
        }
        if(sorting_order::ascending == $psorting_order) ksort($new_array);
        elseif(sorting_order::descending == $psorting_order) krsort($new_array);
        else return $this->array();
        return $new_array;
    }

    public function where($pvariable_name = "name",$pvalue)
    {
        if(!is_string($pvariable_name)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $method_name = "get_".$pvariable_name;
                if(method_exists($array_item,$method_name))
                {
                    $value = $array_item->$method_name();
                    if($value === $pvalue) $new_array[$value] = $array_item;
                }
            }
        }
        $this->array = $new_array;
        return $this->array;
    }

    public function get_where($pvariable_name = "name",$pvalue)
    {
        if(!is_string($pvariable_name)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $method_name = "get_".$pvariable_name;
                if(method_exists($array_item,$method_name))
                {
                    $value = $array_item->$method_name();
                    if($value === $pvalue) $new_array[$value] = $array_item;
                }
            }
        }
        return $new_array;
    }

    public function where_array($pvariables)
    {
        if(!is_array($pvariables)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $equal = false;
                foreach($pvariables as $variable_name => $variable_value)
                {
                    $method_name = "get_".$variable_name;
                    if(method_exists($array_item,$method_name))
                    {
                        $value = $array_item->$method_name();
                        if($value === $variable_value)
                        {
                            $equal = true;
                        } else {
                            $equal = false;
                        }
                    } else {
                        $equal = false;
                    }
                }
                if($equal === true) $new_array[] = $array_item;
            }
        }
        $this->array = $new_array;
        return $this->array;
    }

    public function get_where_array($pvariables)
    {
        if(!is_array($pvariables)) return $this->array;
        $new_array = array();
        foreach($this->array as $array_item)
        {
            if($array_item instanceof dataset)
            {
                $equal = false;
                foreach($pvariables as $variable_name => $variable_value)
                {
                    $method_name = "get_".$variable_name;
                    if(method_exists($array_item,$method_name))
                    {
                        $value = $array_item->$method_name();
                        if($value === $variable_value)
                        {
                            $equal = true;
                        } else {
                            $equal = false;
                        }
                    } else {
                        $equal = false;
                    }
                }
                if($equal === true) $new_array[] = $array_item;
            }
        }
        return $new_array;
    }

}
