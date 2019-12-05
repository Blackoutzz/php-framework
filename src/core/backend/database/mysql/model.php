<?php
namespace core\backend\database\mysql;
use core\backend\database\model as database_model;
use core\backend\database\mysql\connection;
use core\backend\database\dataset;
use core\backend\database\dataset_array;
use core\backend\components\databases\mysql;
use core\program;

use core\backend\database\mysql\datasets\action;
use core\backend\database\mysql\datasets\app_options;
use core\backend\database\mysql\datasets\controller_view;
use core\backend\database\mysql\datasets\controller;
use core\backend\database\mysql\datasets\menu_button_option;
use core\backend\database\mysql\datasets\menu_button;
use core\backend\database\mysql\datasets\menu_category_option;
use core\backend\database\mysql\datasets\menu_category;
use core\backend\database\mysql\datasets\option;
use core\backend\database\mysql\datasets\permission_controller_view;
use core\backend\database\mysql\datasets\permission;
use core\backend\database\mysql\datasets\plugin_option;
use core\backend\database\mysql\datasets\plugin;
use core\backend\database\mysql\datasets\user_action;
use core\backend\database\mysql\datasets\user_controller_view;
use core\backend\database\mysql\datasets\user_group_controller_view;
use core\backend\database\mysql\datasets\user_group_option;
use core\backend\database\mysql\datasets\user_group_permission;
use core\backend\database\mysql\datasets\user_group;
use core\backend\database\mysql\datasets\user_option;
use core\backend\database\mysql\datasets\user_permission;
use core\backend\database\mysql\datasets\user_state;
use core\backend\database\mysql\datasets\user;
use core\backend\database\mysql\datasets\view;

/**
 * Model
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class model extends database_model
{

    public function create_menu_button($pname,$pcontroller_view = 1,$pcategory = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pcategory)) $pcategory = $this->get_menu_category_by_name($pcategory);
                $category = $this->get_parsed_id($pcategory);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                if(is_string($pname))
                {
                    $pdata = array(
                        "name" => $pname,
                        "controller_view" => intval($controller_view),
                        "category" => intval($category)
                    );
                    $new_menu_button = new menu_button($pdata);
                    if($new_menu_button->save()) return $new_menu_button;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
        
    }

    public function get_menu_buttons($pmax = 250,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $buttons = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $buttons[] = new menu_button($pdata);
                    }
                    return $buttons;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_buttons_by_id($pid,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $buttons = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $buttons[] = new menu_button($pdata);
                    }
                    return $buttons;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_button_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new menu_button($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_buttons_by_name($pname,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $buttons = new dataset_array();
                $offset =  $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $buttons[] = new menu_button($pdata);
                    }
                    return $buttons;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_button_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new menu_button($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_buttons_by_controller_view($pcontroller_view,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $buttons = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE controller_view=? LIMIT ? OFFSET ?","iii",array($pcontroller_view,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $buttons[] = new menu_button($pdata);
                    }
                    return $buttons;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_button_by_controller_view($pcontroller_view)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE controller_view=? LIMIT 1","i",array($pcontroller_view)))
                {
                    foreach($data as $pdata)
                    {
                        return new menu_button($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_buttons_by_category($pcategory,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $buttons = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE category=? LIMIT ? OFFSET ?","iii",array($pcategory,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $buttons[] = new menu_button($pdata);
                    }
                    return $buttons;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_button_by_category($pcategory)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE category=? LIMIT 1","i",array($pcategory)))
                {
                    foreach($data as $pdata)
                    {
                        return new menu_button($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_buttons_by_controller_view_and_category($pcontroller_view,$pcategory,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $buttons = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE controller_view=? AND category=? LIMIT ? OFFSET ?","iiii",array($pcontroller_view,$pcategory,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $buttons[] = new menu_button($pdata);
                    }
                    return $buttons;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_button_by_controller_view_and_category($pcontroller_view,$pcategory)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_buttons` WHERE controller_view=? AND category=? LIMIT 1","ii",array($pcontroller_view,$pcategory)))
                {
                    foreach($data as $pdata)
                    {
                        return new menu_button($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_buttons_by_user_and_group_and_granted($puser,$pgroup,$pgranted = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                if(is_string($pgroup)) $pgroup = $this->get_user_group_by_name($pgroup);
                $user = $this->get_parsed_id($puser);
                $group = $this->get_parsed_id($pgroup);
                $granted = $this->get_parsed_boolean($pgranted);
                $buttons = new dataset_array();
                if($data = $this->get_connection()->get_prepared_select_query("SELECT DISTINCT * from menu_buttons as mb where controller_view in (select controller_view  from user_group_permissions as ugp inner join permission_controller_views as pcv on pcv.permission = ugp.permission where ugp.user_group=? and ugp.granted=?)
        OR controller_view in (select controller_view  from user_permissions as up inner join permission_controller_views as pcv on pcv.permission = up.permission where up.user=? and up.granted=?)
        OR controller_view in (select controller_view  from user_controller_views as ucv where ucv.user=? and ucv.granted=?)
        OR controller_view in (select controller_view  from user_group_controller_views as ugcv where ugcv.user_group=? and ugcv.granted=?) order by category","iiiiiiii",array($group,$granted,$user,$granted,$user,$granted,$group,$granted)))
                {
                    foreach($data as $pdata)
                    {
                        $buttons[] = new menu_button($pdata);
                    }
                    return $buttons;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_menu_buttons()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) as 'count' FROM `menu_buttons`"))
                {
                    foreach($data as $pdata)
                    {
                        return $pdata;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    public function create_menu_button_option($pbutton,$poption,$pvalue)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pbutton)) $pbutton = $this->get_menu_button_by_name($pbutton);
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $button = $this->get_parsed_id($pbutton);
                $option = $this->get_parsed_id($poption);
                $value = "{$pvalue}";
                if($option && $button && $value)
                {
                    $pdata = array(
                        "button" => $button,
                        "option" => $option,
                        "value" => $value
                    );
                    $new_menu_button_option = new menu_button_option($pdata);
                    if($new_menu_button_option->save()) return $new_menu_button_option;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_button_options($pmax = 250,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_button_options` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new menu_button_option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_button_options_by_button($pbutton,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pbutton)) $pbutton = $this->get_menu_button_by_name($pbutton);
                $button = $this->get_parsed_id($pbutton);
                $options = array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_button_options` WHERE button=? LIMIT ? OFFSET ?","iii",array($button,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new menu_button_option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_button_options_by_option($poption,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $option = $this->get_parsed_id($poption);
                $options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_button_options` WHERE option=? LIMIT ? OFFSET ?","iii",array($option,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new menu_button_option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_button_option_by_button_and_option($pbutton,$poption)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pbutton)) $pbutton = $this->get_menu_button_by_name($pbutton);
                $button = $this->get_parsed_id($pbutton);
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $option = $this->get_parsed_id($poption);
                if($option && $button)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_button_options` WHERE button=? AND option=? LIMIT 1","ii",array($button,$option)))
                    {
                        foreach($data as $pdata)
                        {
                            return new menu_button_option($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function count_menu_button_options()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) as 'count' FROM `menu_button_options`"))
                {
                    foreach($data as $pdata)
                    {
                        return $pdata;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    public function create_menu_category($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pname))
                {
                    $pdata = array(
                        "name" => $pname
                    );
                    $new_menu_category = new menu_category($pdata);
                    if($new_menu_category->save()) return $new_menu_category;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_categories($pmax = 250,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $categories = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_categories` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $categories[] = new menu_category($pdata);
                    }
                    return $categories;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_categories_by_id($pid,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $categories = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_categories` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $categories[] = new menu_category($pdata);
                    }
                    return $categories;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_category_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_categories` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new menu_category($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_categories_by_name($pname,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $categories = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_categories` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $categories[] = new menu_category($pdata);
                    }
                    return $categories;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_category_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_categories` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new menu_category($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function count_menu_categories()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) as 'count' FROM `menu_categories`"))
                {
                    foreach($data as $pdata)
                    {
                        return $pdata;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    public function create_menu_category_option($pcategory,$poption,$pvalue)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pcategory)) $pcategory = $this->get_menu_category_by_name($pcategory);
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $category = $this->get_parsed_id($pcategory);
                $option = $this->get_parsed_id($poption);
                $value = "{$pvalue}";
                if($option && $category && $value)
                {
                    $pdata = array(
                        "category" => $category,
                        "option" => $option,
                        "value" => $value
                    );
                    $new_menu_category_option = new menu_category_option($pdata);
                    if($new_menu_category_option->save()) return $new_menu_category_option;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_menu_category_options($pmax = 250,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_category_options` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new menu_category_option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_category_options_by_category($pcategory,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pcategory)) $pcategory = $this->get_menu_category_by_name($pcategory);
                $category = $this->get_parsed_id($pcategory);
                $options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_category_options` WHERE category=? LIMIT ? OFFSET ?","iii",array($category,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new menu_category_option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_category_options_by_option($poption,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $option = $this->get_parsed_id($poption);
                $options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_category_options` WHERE option=? LIMIT ? OFFSET ?","iii",array($option,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new menu_category_option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_menu_category_option_by_category_and_option($pcategory,$poption)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pcategory)) $pcategory = $this->get_menu_category_by_name($pcategory);
                $category = $this->get_parsed_id($pcategory);
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $option = $this->get_parsed_id($poption);
                if($option && $category)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `menu_category_options` WHERE category=? AND option=? LIMIT 1","ii",array($category,$option)))
                    {
                        foreach($data as $pdata)
                        {
                            return new menu_category_option($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function count_menu_category_options()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) as 'count' FROM `menu_category_options`"))
                {
                    foreach($data as $pdata)
                    {
                        return $pdata;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //Plugin
    public function create_plugin($pname,$pslug,$pversion = "beta",$penabled = 0)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pname) && is_string($pslug) && is_string($pversion))
                {
                    $pdata = array(
                        "name" => $pname,
                        "slug" => $pslug,
                        "version" => $pversion,
                        "enabled" => $penabled
                    );
                    $new_plugin = new plugin($pdata);
                    if($new_plugin->save()) return $new_plugin;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_plugins_by_name($pname,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $plugins = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `plugins` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $plugins[] = new plugin($pdata);
                    }
                }
                return $plugins;
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_plugin_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `plugins` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new plugin($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_plugins_by_slug($pslug,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $plugins = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `plugins` slug=? LIMIT ? OFFSET ?","sii",array($pslug,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $plugins[] = new plugin($pdata);
                    }
                }
                return $plugins;
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_plugin_by_slug($pslug)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `plugins` WHERE slug=? LIMIT 1","s",array($pslug)))
                {
                    foreach($data as $pdata)
                    {
                        return new plugin($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_plugins_by_version($pversion,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $plugins = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `plugins` WHERE version=? LIMIT ? OFFSET ?","sii",array($pversion,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $plugins[] = new plugin($pdata);
                    }
                }
                return $plugins;
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_plugins_by_enabled($pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $plugins = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `plugins` WHERE enabled=1 LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $plugins[] = new plugin($pdata);
                    }
                }
                return $plugins;
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_plugins_by_disabled($pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $plugins = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `plugins` WHERE enabled=0 LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $plugins[] = new plugin($pdata);
                    }
                }
                return $plugins;
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_plugins($pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $plugins = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `plugins` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $plugins[] = new plugin($pdata);
                    }
                }
                return $plugins;
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    //User
    public function create_user($pname,$ppassword,$pemail,$puser_group = 3)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group = $this->get_parsed_id($puser_group);
                if($user_group == 0 && is_string($puser_group)) $this->get_parsed_id($this->get_user_group_by_name($puser_group));
                if(is_string($pname) && is_string($ppassword) && is_string($pemail) && $user_group)
                {
                    $pdata = array(
                        "name" => $pname,
                        "password" => $ppassword,
                        "email" => $pemail,
                        "user_group" => $user_group,
                        "user_status" => 1
                    );
                    $new_user = new user($pdata);
                    if($new_user->save()) return $new_user;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new user($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_users_by_name($pname,$pmax = 12,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $users = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $users[] = new user($pdata);
                    }
                    return $users;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_users_by_id($pid,$pmax = 12,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $users = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $users[] = new user($pdata);
                    }
                }
                return $users;
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_users_by_user_group($puser_group,$pmax = 25,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $users = new dataset_array();
                $user_group = $this->get_parsed_id($puser_group);
                if($user_group != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` WHERE user_group=? LIMIT ? OFFSET ?","iii",array($user_group,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $users[] = new user($pdata);
                        }
                        return $users;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_by_email($pemail)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` WHERE email=? LIMIT 1","s",array($pemail)))
                {
                    foreach($data as $pdata)
                    {
                        return new user($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_users_by_email($pemail,$pmax = 25,$ppage =1)
    {
        try
        {
            if($this->is_connected())
            {
                $users = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` WHERE email=? LIMIT ? OFFSET ?","sii",array($pemail,$pmax,$offset))){
                    foreach($data as $pdata)
                    {
                        $users[] = new user($pdata);
                    }
                    return $users;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_users_by_user_status($puser_status,$pmax = 25,$ppage =1)
    {
        try
        {
            if($this->is_connected())
            {
                $users = new dataset_array();
                $user_status = $this->get_parsed_id($puser_status);
                if($user_status != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` WHERE user_status=? LIMIT ? OFFSET ?","iii",array($user_status,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $users[] = new user($pdata);
                        }
                        return $users;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_users($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $users = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `users` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $users[] = new user($pdata);
                    }
                    return $users;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_users()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `users`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User Group
    public function create_user_group($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pname))
                {
                    $pdata = array(
                        "name" => $pname
                    );
                    $new_user_group = new user_group($pdata);
                    if($new_user_group->save()) return $new_user_group;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_group_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_groups` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_group($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_groups_by_name($pname,$pmax = 15,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $groups = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_groups` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $groups[] = new user_group($pdata);
                    }
                    return $groups;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_groups` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_group($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_groups_by_id($pid,$pmax = 15,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $groups = new dataset_array();
                $offset = $this->get_query_offset($pmax = 1,$ppage = 1);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_groups` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $groups[] = new user_group($pdata);
                    }
                    return $groups;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_groups($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $groups = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_groups` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $groups[] = new user_group($pdata);
                    }
                    return $groups;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_groups()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_groups`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //Permission
    public function create_permission($pname,$pdescription)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pname) && is_string($pdescription))
                {
                    $pdata = array(
                        "name" => $pname,
                        "description" => $pdescription
                    );
                    $new_permission = new permission($pdata);
                    if($new_permission->save()) return $new_permission;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_permission_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `permissions` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new permission($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_permissions_by_name($pname,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $permissions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `permissions` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $permissions[] = new permission($pdata);
                    }
                    return $permissions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_permission_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `permissions` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new permission($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_permissions_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $permissions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `permissions` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset))){
                    foreach($data as $pdata)
                    {
                        $permissions[] = new permission($pdata);
                    }
                    return $permissions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_permissions($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $permissions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `permissions` LIMIT ? OFFSET ?","ii",array($pmax,$offset))){
                    foreach($data as $pdata)
                    {
                        $permissions[] = new permission($pdata);
                    }
                    return $permissions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_permissions()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `permissions`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    public function create_permission_controller_view($ppermission,$pcontroller_view,$pgranted = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($ppermission)) $ppermission = $this->get_permission_by_name($ppermission);
                $permission = $this->get_parsed_id($ppermission);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                $granted = $this->get_parsed_boolean($pgranted);

                $pdata = array(
                    "permission" => $permission,
                    "controller_view" => $controller_view,
                    "granted" => $granted
                );
                $new_permission_controller_view = new permission_controller_view($pdata);
                if($new_permission_controller_view->save()) return $new_permission_controller_view;
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_permission_controller_views($pmax = 100,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $permission_controller_views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `permission_controller_views` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $permission_controller_views[] = new permission_controller_view($pdata);
                    }
                    return $permission_controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_permission_controller_views_by_id($pid,$pmax = 100,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $permission_controller_views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `permission_controller_views` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $permission_controller_views[] = new permission_controller_view($pdata);
                    }
                    return $permission_controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_permission_controller_view_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `permission_controller_views` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new permission_controller_view($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_permission_controller_views_by_user_and_group_and_granted($puser,$pgroup,$pgranted = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                if(is_string($pgroup)) $pgroup = $this->get_user_group_by_name($pgroup);
                $user = $this->get_parsed_id($puser);
                $group = $this->get_parsed_id($pgroup);
                $granted = $this->get_parsed_boolean($pgranted);
                $permission_controller_views = new dataset_array();
                if($data = $this->get_connection()->get_prepared_select_query("SELECT DISTINCT * from `permission_controller_views` where controller_view in (select controller_view from user_group_permissions as ugp inner join permission_controller_views as pcv on pcv.permission = ugp.permission where ugp.user_group=? and ugp.granted=?)
        OR controller_view in (select controller_view from user_permissions as up inner join permission_controller_views as pcv on pcv.permission = up.permission where up.user=? and up.granted=?)
        order by permission","iiii",array($group,$granted,$user,$granted)))
                {
                    foreach($data as $pdata)
                    {
                        $permission_controller_views[] = new permission_controller_view($pdata);
                    }
                    return $permission_controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_permission_controller_views_by_controller_view_user_and_group_and_granted($pcontroller_view,$puser,$pgroup,$pgranted = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                if(is_string($pgroup)) $pgroup = $this->get_user_group_by_name($pgroup);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                $user = $this->get_parsed_id($puser);
                $group = $this->get_parsed_id($pgroup);
                $granted = $this->get_parsed_boolean($pgranted);
                $permission_controller_views = new dataset_array();
                if($data = $this->get_connection()->get_prepared_select_query("SELECT DISTINCT * from `permission_controller_views` where controller_view in (select controller_view from user_group_permissions as ugp inner join permission_controller_views as pcv on pcv.permission = ugp.permission where ugp.user_group=? and ugp.granted=? and pcv.controller_view=?)
        OR controller_view in (select controller_view from user_permissions as up inner join permission_controller_views as pcv on pcv.permission = up.permission where up.user=? and up.granted=? and pcv.controller_view=?)
        order by permission","iiiiii",array($group,$granted,$controller_view,$user,$granted,$controller_view)))
                {
                    foreach($data as $pdata)
                    {
                        $permission_controller_views[] = new permission_controller_view($pdata);
                    }
                    return $permission_controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_permission_controller_view_by_controller_view_user_and_group_and_granted($pcontroller_view,$puser,$pgroup,$pgranted = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                if(is_string($pgroup)) $pgroup = $this->get_user_group_by_name($pgroup);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                $user = $this->get_parsed_id($puser);
                $group = $this->get_parsed_id($pgroup);
                $granted = $this->get_parsed_boolean($pgranted);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT DISTINCT * from `permission_controller_views` where controller_view in (select controller_view from user_group_permissions as ugp inner join permission_controller_views as pcv on pcv.permission = ugp.permission where ugp.user_group=? and ugp.granted=? and pcv.controller_view=?)
        OR controller_view in (select controller_view from user_permissions as up inner join permission_controller_views as pcv on pcv.permission = up.permission where up.user=? and up.granted=? and pcv.controller_view=?)
        order by permission LIMIT 1","iiiiii",array($group,$granted,$controller_view,$user,$granted,$controller_view)))
                {
                    foreach($data as $pdata)
                    {
                        return new permission_controller_view($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    //Action
    public function create_action($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pname))
                {
                    $pdata = array(
                        "name" => $pname
                    );
                    $new_action = new action($pdata);
                    if($new_action->save()) return $new_action;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_action_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `actions` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new action($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_actions_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $actions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `actions` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $actions[] = new action($pdata);
                    }
                    return $actions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_action_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `actions` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new action($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_actions_by_name($pname,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $actions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `actions` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $actions[] = new action($pdata);
                    }
                    return $actions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_actions($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $actions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `actions` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $actions[] = new action($pdata);
                    }
                    return $actions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_actions()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `actions`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //View
    public function create_view($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pname))
                {
                    $pdata = array(
                        "name" => $pname
                    );
                    $new_view = new view($pdata);
                    if($new_view->save()) return $new_view;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_view_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `views` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new view($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_views_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `views` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $views[] = new view($pdata);
                    }
                    return $views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_view_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `views` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new view($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_views_by_name($pname,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `views` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $views[] = new view($pdata);
                    }
                    return $views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_views($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `views` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $views[] = new view($pdata);
                    }
                    return $views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_views()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `views`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //Option
    public function create_option($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pname))
                {
                    $pdata = array(
                        "name" => $pname
                    );
                    $new_option = new option($pdata);
                    if($new_option->save()) return $new_option;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_option_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `options` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new option($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_options_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `options` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_option_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `options` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new option($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_options_by_name($pname,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `options` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_options($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `options` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $options[] = new option($pdata);
                    }
                    return $options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_options()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `options`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //Controller
    public function create_controller($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pname))
                {
                    $pdata = array(
                        "name" => $pname
                    );
                    $new_controller = new controller($pdata);
                    if($new_controller->save()) return $new_controller;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_controller_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controllers` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new controller($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_controllers_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $controllers = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controllers` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $controllers[] = new controller($pdata);
                    }
                    return $controllers;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_controller_by_name($pname)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controllers` WHERE name=? LIMIT 1","s",array($pname)))
                {
                    foreach($data as $pdata)
                    {
                        return new controller($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_controllers_by_name($pname,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $controllers = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controllers` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $controllers[] = new controller($pdata);
                    }
                    return $controllers;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_controllers($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $controllers = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controllers` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $controllers[] = new controller($pdata);
                    }
                    return $controllers;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_controllers()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `controllers`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //Controller view
    public function create_controller_view($pcontroller,$pview)
    {
        try
        {
            if($this->is_connected())
            {
                $controller = $this->get_parsed_id($pcontroller);
                $view = $this->get_parsed_id($pview);
                if($controller == 0) $controller = $this->get_parsed_id($this->get_controller_by_name($pcontroller));
                if($view == 0) $view = $this->get_parsed_id($this->get_view_by_name($pview));
                if($controller && $view){
                    $pdata = array(
                        "controller" => $controller,
                        "view" => $view
                    );
                    $new_controller_view = new controller_view($pdata);
                    if($new_controller_view->save()) return $new_controller_view;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_controller_view_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controller_views` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new controller_view($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_controller_views_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $controller_views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controller_views` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $controller_views[] = new controller_view($pdata);
                    }
                    return $controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_controller_views_by_controller($pcontroller,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pcontroller)) $pcontroller = $this->get_controller_by_name($pcontroller);
                $controller = $this->get_parsed_id($pcontroller);
                $controller_views = new dataset_array();
                if($controller != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controller_views` WHERE controller=? LIMIT ? OFFSET ?","iii",array($controller,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $controller_views[] = new controller_view($pdata);
                        }
                        return $controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_controller_views_by_view($pview,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pview)) $pview = $this->get_view_by_name($pview);
                $view = $this->get_parsed_id($pview);
                $controller_views = new dataset_array();
                if($view != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controller_views` WHERE view=? LIMIT ? OFFSET ?","iii",array($view,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $controller_views[] = new controller_view($pdata);
                        }
                        return $controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_controller_view_by_controller_and_view($pcontroller,$pview)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pcontroller)) $pcontroller = $this->get_controller_by_name($pcontroller);
                if(is_string($pview)) $pview = $this->get_view_by_name($pview);
                $controller = $this->get_parsed_id($pcontroller);
                $view = $this->get_parsed_id($pview);
                if($view != NULL && $controller != NULL)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controller_views` WHERE view=? AND controller=? LIMIT 1","ii",array($view,$controller)))
                    {
                        foreach($data as $pdata)
                        {
                            return new controller_view($pdata);
                        }
                    }
                }
                return NULL;
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_controller_views_by_controller_and_view($pcontroller,$pview,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pcontroller)) $pcontroller = $this->get_controller_by_name($pcontroller);
                if(is_string($pview)) $pview = $this->get_view_by_name($pview);
                $controller = $this->get_parsed_id($pcontroller);
                $view = $this->get_parsed_id($pview);
                $controller_views = new dataset_array();
                if($view != NULL && $controller != NULL){
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controller_views` WHERE view=? AND controller=? LIMIT ? OFFSET ?","iiii",array($view,$controller,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $controller_views[] = new controller_view($pdata);
                        }
                        return $controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_controller_views($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $controller_views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `controller_views` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $controller_views[] = new controller_view($pdata);
                    }
                    return $controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_controller_views()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `controller_views`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User Action
    public function create_user_action($puser,$paction)
    {
        try
        {
            if($this->is_connected())
            {
                $user = $this->get_parsed_id($puser);
                $action = $this->get_parsed_id($paction);
                if($user == 0) $user = $this->get_parsed_id($this->get_user_by_name($puser));
                if($action == 0) $action = $this->get_parsed_id($this->get_action_by_name($paction));
                if($user && $action)
                {
                    $pdata = array(
                        "user" => $user,
                        "action" => $action
                    );
                    $new_user_action = new user_action($pdata);
                    if($new_user_action->save()) return $new_user_action;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_action_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_actions` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_action($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_actions_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_actions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_actions` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_actions[] = new user_action($pdata);
                    }
                    return $user_actions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_actions_by_user($puser,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                $user = $this->get_parsed_id($puser);
                $user_actions = new dataset_array();
                if($user != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_actions` WHERE user=? LIMIT ? OFFSET ?","iii",array($user,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_actions[] = new user_action($pdata);
                        }
                        return $user_actions;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_actions_by_action($paction,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($paction)) $paction = $this->get_action_by_name($paction);
                $action = $this->get_parsed_id($paction);
                $user_actions = new dataset_array();
                if($action != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_actions` WHERE action=? LIMIT ? OFFSET ?","iii",array($action,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_actions[] = new user_action($pdata);
                        }
                        return $user_actions;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_actions($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_actions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_actions` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_actions[] = new user_action($pdata);
                    }
                    return $user_actions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_actions()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_actions`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User Option
    public function create_user_option($puser,$poption,$pvalue = 0)
    {
        try
        {
            if($this->is_connected())
            {
                $user = $this->get_parsed_id($puser);
                $option = $this->get_parsed_id($poption);
                if($user == 0) $user = $this->get_parsed_id($this->get_user_by_name($puser));
                if($option == 0) $option = $this->get_parsed_id($this->get_option_by_name($poption));
                if($user && $option){
                    $pdata = array(
                        "user" => $user,
                        "option" => $option,
                        "value" => $pvalue
                    );
                    $new_user_option = new user_option($pdata);
                    if($new_user_option->save()) return $new_user_option;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_option_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_option($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_options_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_options[] = new user_option($pdata);
                    }
                    return $user_options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_options_by_user($puser,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                $user = $this->get_parsed_id($puser);
                $user_options = new dataset_array();
                if($user != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` WHERE user=? LIMIT ? OFFSET ?","iii",array($user,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_options[] = new user_option($pdata);
                        }
                        return $user_options;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_options_by_option($poption,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $option = $this->get_parsed_id($poption);
                $user_options = new dataset_array();
                if($option != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` WHERE `option`=? LIMIT ? OFFSET ?","iii",array($option,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_options[] = new user_option($pdata);
                        }
                        return $user_options;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_option_by_user_and_option($puser,$poption)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $user = $this->get_parsed_id($puser);
                $option = $this->get_parsed_id($poption);
                if($user != NULL && $option != NULL)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` WHERE user=? AND `option`=? LIMIT 1","ii",array($user,$option)))
                    {
                        foreach($data as $pdata)
                        {
                            return new user_option($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_options_by_user_and_option($puser,$poption,$pmax = 50 , $ppage =1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $user = $this->get_parsed_id($puser);
                $option = $this->get_parsed_id($poption);
                $user_options = new dataset_array();
                if($user != NULL && $option != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` WHERE user=? AND `option`=? LIMIT ? OFFSET ?","iiii",array($user,$option,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_options[] = new user_option($pdata);
                        }
                        return $user_options;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_option_by_option_and_value($poption,$pvalue)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $option = $this->get_parsed_id($poption);
                if($option != NULL)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` WHERE `option`=? AND `value`=? LIMIT 1","is",array($option,$pvalue)))
                    {
                        foreach($data as $pdata)
                        {
                            return new user_option($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_options_by_option_and_value($poption,$pvalue,$pmax = 50 , $ppage =1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $option = $this->get_parsed_id($poption);
                $user_options = new dataset_array();
                if($option != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` WHERE `option`=? AND `value`=? LIMIT ? OFFSET ?","isii",array($option,$pvalue,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_options[] = new user_option($pdata);
                        }
                        return $user_options;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_options($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_options` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_options[] = new user_option($pdata);
                    }
                    return $user_options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_options()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_options`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User Permissions
    public function create_user_permission($puser,$ppermission,$pgranted)
    {
        try
        {
            if($this->is_connected())
            {
                $user = $this->get_parsed_id($puser);
                $permission = $this->get_parsed_id($ppermission);
                if($user == 0) $user = $this->get_parsed_id($this->get_user_by_name($puser));
                if($permission == 0) $permission = $this->get_parsed_id($this->get_permission_by_name($ppermission));
                if($user && $permission)
                {
                    $pdata = array(
                        "user" => $user,
                        "permission" => $permission,
                        "granted" => $pgranted
                    );
                    $new_user_permission = new user_permission($pdata);
                    if($new_user_permission->save()) $new_user_permission;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_permission_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_permissions` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_permission($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_permissions_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_permissions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_permissions` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_permissions[] = new user_permission($pdata);
                    }
                    return $user_permissions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_permissions_by_user($puser,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                $user = $this->get_parsed_id($puser);
                $user_permissions = new dataset_array();
                if($user != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_permissions` WHERE user=? LIMIT ? OFFSET ?","iii",array($user,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_permissions[] = new user_permission($pdata);
                        }
                        return $user_permissions;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_permissions_by_permission($ppermission,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($ppermission)) $ppermission = $this->get_permission_by_name($ppermission);
                $permission = $this->get_parsed_id($ppermission);
                $user_permissions = new dataset_array();
                if($permission != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_permissions` WHERE permission=? LIMIT ? OFFSET ?","iii",array($permission,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_permissions[] = new user_permission($pdata);
                        }
                        return $user_permissions;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_permission_by_user_and_permission($puser,$ppermission,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                if(is_string($ppermission)) $ppermission = $this->get_permission_by_name($ppermission);
                $user = $this->get_parsed_id($puser);
                $permission = $this->get_parsed_id($ppermission);
                if($user != NULL && $permission != NULL)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_permissions` WHERE user=? AND permission=? LIMIT 1","ii",array($user,$permission)))
                    {
                        foreach($data as $pdata)
                        {
                            return new user_permission($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_permissions_by_user_and_permission($puser,$ppermission,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                if(is_string($ppermission)) $ppermission = $this->get_permission_by_name($ppermission);
                $user = $this->get_parsed_id($puser);
                $permission = $this->get_parsed_id($ppermission);
                $user_permissions = new dataset_array();
                if($user != NULL && $permission != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_permissions` WHERE user=? AND permission=? LIMIT ? OFFSET ?","iiii",array($user,$permission,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_permissions[] = new user_permission($pdata);
                        }
                        return $user_permissions;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_permissions($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_permissions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_permissions` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_permissions[] = new user_permission($pdata);
                    }
                    return $user_permissions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_permissions()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_permissions`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User States
    public function create_user_state($pstate)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($pstate))
                {
                    $pdata = array(
                        "name" => $pstate
                    );
                    $new_user_state = new user_state($pdata);
                    if($new_user_state->save()) return $new_user_state;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_state_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_states` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_state($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_states_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_states = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_states` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_states[] = new user_state($pdata);
                    }
                    return $user_states;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_states_by_name($pname,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_states = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_states` WHERE name=? LIMIT ? OFFSET ?","sii",array($pname,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_states[] = new user_state($pdata);
                    }
                    return $user_states;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_states($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_states = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_states` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_states[] = new user_state($pdata);
                    }
                    return $user_states;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_states()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_states`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User Controller View
    public function create_user_controller_view($puser,$pcontroller_view,$pgranted)
    {
        try
        {
            if($this->is_connected())
            {
                $user = $this->get_parsed_id($puser);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                if($user == 0) $user = $this->get_parsed_id($this->get_user_by_name($puser));
                if($controller_view == 0 && is_string($pcontroller_view)){
                    if(sregex::is_controller_view($pcontroller_view)){
                        $controller_n_view = explode("/",$pcontroller_view);
                        $controller_view = $this->get_parsed_id($this->get_controller_view_by_controller_and_view($controller_n_view[0],$controller_n_view[1]));
                    }
                }
                if($user && $controller_view)
                {
                    $pdata = array(
                        "user" => $user,
                        "controller_view" => $controller_view,
                        "granted" => $pgranted
                    );
                    $new_user_controller_view = new user_controller_view($pdata);
                    if($new_user_controller_view->save()) return $new_user_controller_view;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_controller_view_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_controller_views` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_controller_view($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_controller_views_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_controller_views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_controller_views` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_controller_views[] = new user_controller_view($pdata);
                    }
                    return $user_controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_controller_views_by_user($puser,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                $user = $this->get_parsed_id($puser);
                $user_controller_views = new dataset_array();
                if($user != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_controller_views` WHERE user=? LIMIT ? OFFSET ?","iii",array($user,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_controller_views[] = new user_controller_view($pdata);
                        }
                        return $user_controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_controller_views_by_controller_view($pcontroller_view,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $controller_view = $this->get_parsed_id($pcontroller_view);
                $user_controller_views = new dataset_array();
                if($controller_view != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_controller_views` WHERE controller_view=? LIMIT ? OFFSET ?","iii",array($controller_view,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_controller_views[] = new user_controller_view($pdata);
                        }
                        return $user_controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_controller_view_by_user_and_controller_view($puser,$pcontroller_view)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                $user = $this->get_parsed_id($puser);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                if($user != NULL && $controller_view != NULL)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_controller_views` WHERE user=? AND controller_view=? LIMIT 1","ii",array($user,$controller_view)))
                    {
                        foreach($data as $pdata)
                        {
                            return new user_controller_view($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_controller_views_by_user_and_controller_view($puser,$pcontroller_view,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser)) $puser = $this->get_user_by_name($puser);
                $user = $this->get_parsed_id($puser);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                $user_controller_views = new dataset_array();
                if($user != NULL && $controller_view != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_controller_views` WHERE user=? AND controller_view=? LIMIT ? OFFSET ?","iiii",array($user,$controller_view,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_controller_views[] = new user_controller_view($pdata);
                        }
                        return $user_controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_controller_views($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_controller_views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_controller_views` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_controller_views[] = new user_controller_view($pdata);
                    }
                    return $user_controller_views;
                }
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_controller_views()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_controller_views`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User Group options
    public function create_user_group_option($puser_group,$poption,$pvalue)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group = $this->get_parsed_id($puser_group);
                $option = $this->get_parsed_id($poption);
                if($user_group == 0) $user_group = $this->get_parsed_id($this->get_user_group_by_name($puser_group));
                if($option == 0) $option = $this->get_parsed_id($this->get_option_by_name($poption));
                if($user_group && $option)
                {
                    $pdata = array(
                        "user_group" => $user_group,
                        "option" => $option,
                        "value" => $pvalue
                    );
                    $new_user_action = new user_group_option($pdata);
                    if($new_user_action->save()) return $new_user_action;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_group_option_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_options` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_group_option($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_group_options_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group_options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_options` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_group_options[] = new user_group_option($pdata);
                    }
                    return $user_group_options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_options_by_user_group($puser_group,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                $user_group = $this->get_parsed_id($puser_group);
                $user_group_options = new dataset_array();
                if($user_group != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_options` WHERE user_group=? LIMIT ? OFFSET ?","iii",array($user_group,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_options[] = new user_group_option($pdata);
                        }
                        return $user_group_options;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_options_by_option($poption,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                $option = $this->get_parsed_id($poption);
                $user_group_options = new dataset_array();
                if($option != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_options` WHERE `option`=? LIMIT ? OFFSET ?","iii",array($option,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_options[] = new user_group_option($pdata);
                        }
                        return $user_group_options;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_option_by_user_group_and_option($puser_group,$poption)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                $user_group = $this->get_parsed_id($puser_group);
                $option = $this->get_parsed_id($poption);
                if($user_group != NULL && $option != NULL)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_options` WHERE user_group=? AND `option`=? LIMIT 1","ii",array($user_group,$option)))
                    {
                        foreach($data as $pdata)
                        {
                            return new user_group_option($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_options_by_user_group_and_option($puser_group,$poption,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($poption)) $poption = $this->get_option_by_name($poption);
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                $user_group = $this->get_parsed_id($puser_group);
                $option = $this->get_parsed_id($poption);
                $user_group_options = new dataset_array();
                if($user_group != NULL && $option != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_options` WHERE user_group=? AND `option`=? LIMIT ? OFFSET ?","iiii",array($user_group,$option,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_options[] = new user_group_option($pdata);
                        }
                        return $user_group_options;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_options($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group_options = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_options` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_group_options[] = new user_group_option($pdata);
                    }
                    return $user_group_options;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_group_options()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_group_options`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User Group Permissions
    public function create_user_group_permission($puser_group,$ppermission,$pgranted)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group = $this->get_parsed_id($puser_group);
                $permission = $this->get_parsed_id($ppermission);
                if($user_group == 0) $user_group = $this->get_parsed_id($this->get_user_group_by_name($puser_group));
                if($permission == 0) $permission = $this->get_parsed_id($this->get_permission_by_name($ppermission));
                if($user_group && $permission)
                {
                    $pdata = array(
                        "user_group" => $user_group,
                        "permission" => $permission,
                        "granted" => $pgranted
                    );
                    $new_user_group_permission = new user_group_permission($pdata);
                    if($new_user_group_permission->save()) return $new_user_group_permission;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_group_permission_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_permissions` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_group_permission($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_group_permissions_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group_permissions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_permissions` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_group_permissions[] = new user_group_permission($pdata);
                    }
                    return $user_group_permissions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_permissions_by_user_group($puser_group,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                $user_group = $this->get_parsed_id($puser_group);
                $user_group_permissions = new dataset_array();
                if($user_group != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_permissions` WHERE user_group=? LIMIT ? OFFSET ?","iii",array($user_group,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_permissions[] = new user_group_permission($pdata);
                        }
                        return $user_group_permissions;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_permissions_by_permission($ppermission,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($ppermission)) $ppermission = $this->get_permission_by_name($ppermission);
                $permission = $this->get_parsed_id($ppermission);
                $user_group_permissions = new dataset_array();
                if($permission != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_permissions` WHERE permission=? LIMIT ? OFFSET ?","iii",array($permission,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_permissions[] = new user_group_permission($pdata);
                        }
                        return $user_group_permissions;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_permission_by_user_group_and_permission($puser_group,$ppermission)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                if(is_string($ppermission)) $ppermission = $this->get_permission_by_name($ppermission);
                $user_group = $this->get_parsed_id($puser_group);
                $permission = $this->get_parsed_id($ppermission);
                if($user_group != NULL && $permission != NULL)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_permissions` WHERE user_group=? AND permission=? LIMIT 1","ii",array($user_group,$permission)))
                    {
                        foreach($data as $pdata)
                        {
                            return new user_group_permission($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_permissions_by_user_group_and_permission($puser_group,$ppermission,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                if(is_string($ppermission)) $ppermission = $this->get_permission_by_name($ppermission);
                $user_group = $this->get_parsed_id($puser_group);
                $permission = $this->get_parsed_id($ppermission);
                $user_group_permissions = new dataset_array();
                if($user_group != NULL && $permission != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_permissions` WHERE user_group=? AND permission=? LIMIT ? OFFSET ?","iiii",array($user_group,$permission,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_permissions[] = new user_group_permission($pdata);
                        }
                        return $user_group_permissions;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_permissions($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group_permissions = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_permissions` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_group_permissions[] = new user_group_permission($pdata);
                    }
                    return $user_group_permissions;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_group_permissions()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_group_permissions`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    //User Group Controller_views
    public function create_user_group_controller_view($puser_group,$pcontroller_view,$pgranted)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group = $this->get_parsed_id($puser_group);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                if($user_group == 0) $user_group = $this->get_parsed_id($this->get_user_group_by_name($puser_group));
                if($controller_view == 0 && is_string($pcontroller_view)){
                    if(sregex::is_controller_view($pcontroller_view)){
                        $controller_n_view = explode("/",$pcontroller_view);
                        $controller_view = $this->get_parsed_id($this->get_controller_view_by_controller_and_view($controller_n_view[0],$controller_n_view[1]));
                    }
                }
                if($user_group && $controller_view)
                {
                    $pdata = array(
                        "user_group" => $user_group,
                        "controller_view" => $controller_view,
                        "granted" => $pgranted
                    );
                    $new_user_group_controller_view = new user_group_controller_view($pdata);
                    if($new_user_group_controller_view->save()) $new_user_group_controller_view;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function get_user_group_controller_view_by_id($pid)
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_controller_views` WHERE id=? LIMIT 1","i",array($pid)))
                {
                    foreach($data as $pdata)
                    {
                        return new user_group_controller_view($pdata);
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_controller_views_by_id($pid,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group_controller_views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_controller_views` WHERE id=? LIMIT ? OFFSET ?","iii",array($pid,$pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_group_controller_views[] = new user_group_controller_view($pdata);
                    }
                    return $user_group_controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_controller_views_by_user_group($puser_group,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                $user_group = $this->get_parsed_id($puser_group);
                $user_group_controller_views = new dataset_array();
                if($user_group != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_controller_views` WHERE user_group=? LIMIT ? OFFSET ?","iii",array($user_group,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_controller_views[] = new user_group_controller_view($pdata);
                        }
                        return $user_group_controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_controller_views_by_controller_view($pcontroller_view,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $controller_view = $this->get_parsed_id($pcontroller_view);
                $user_group_controller_views = new dataset_array();
                if($controller_view != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_controller_views` WHERE controller_view=? LIMIT ? OFFSET ?","iii",array($controller_view,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_controller_views[] = new user_group_controller_view($pdata);
                        }
                        return $user_group_controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_controller_view_by_user_group_and_controller_view($puser_group,$pcontroller_view)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                $user_group = $this->get_parsed_id($puser_group);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                if($controller_view != NULL && $user_group != NULL)
                {
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_controller_views` WHERE user_group=? AND controller_view=? LIMIT 1","ii",array($user_group,$controller_view)))
                    {
                        foreach($data as $pdata)
                        {
                            return new user_group_controller_view($pdata);
                        }
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_controller_views_by_user_group_and_controller_view($puser_group,$pcontroller_view,$pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                if(is_string($puser_group)) $puser_group = $this->get_user_group_by_name($puser_group);
                $user_group = $this->get_parsed_id($puser_group);
                $controller_view = $this->get_parsed_id($pcontroller_view);
                $user_group_controller_views = new dataset_array();
                if($controller_view != NULL && $user_group != NULL)
                {
                    $offset = $this->get_query_offset($pmax,$ppage);
                    if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_controller_views` WHERE user_group=? AND controller_view=? LIMIT ? OFFSET ?","iiii",array($user_group,$controller_view,$pmax,$offset)))
                    {
                        foreach($data as $pdata)
                        {
                            $user_group_controller_views[] = new user_group_controller_view($pdata);
                        }
                        return $user_group_controller_views;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function get_user_group_controller_views($pmax = 50,$ppage = 1)
    {
        try
        {
            if($this->is_connected())
            {
                $user_group_controller_views = new dataset_array();
                $offset = $this->get_query_offset($pmax,$ppage);
                if($data = $this->get_connection()->get_prepared_select_query("SELECT * FROM `user_group_controller_views` LIMIT ? OFFSET ?","ii",array($pmax,$offset)))
                {
                    foreach($data as $pdata)
                    {
                        $user_group_controller_views[] = new user_group_controller_view($pdata);
                    }
                    return $user_group_controller_views;
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return new dataset_array();
        }
    }

    public function count_user_group_controller_views()
    {
        try
        {
            if($this->is_connected())
            {
                if($data = $this->get_connection()->get_select_query("SELECT count(*) FROM `user_group_controller_views`"))
                {
                    foreach($data as $value)
                    {
                        return $value;
                    }
                }
                throw new exception("Something went wrong with the query from the model");
            }
            throw new exception("Model couldn't connect to the database");
        } 
        catch(exception $e)
        {
            return 0;
        }
    }

    static function is_user($puser)
    {
        try
        {
            if($puser instanceof user)
            {
                return true;
            }
            throw new exception("Bad user dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_user_action($puser_action)
    {
        try
        {
            if($puser_action instanceof user_action)
            {
                return true;
            }
            throw new exception("Bad user action dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_user_option($puser_option)
    {
        try
        {
            if($puser_option instanceof user_option)
            {
                return true;
            }
            throw new exception("Bad user option dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_user_permission($puser_permission)
    {
        try
        {
            if($puser_permission instanceof user_permission)
            {
                return true;
            }
            throw new exception("Bad user permission dataset");
        } 
        catch(exception $e)
        {
            return false;
        }

    }

    static function is_user_controller_view($puser_controller_view)
    {
        try
        {
            if($puser_controller_view instanceof user_controller_view)
            {
                return true;
            }
            throw new exception("Bad user controller view dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_user_group($puser_group)
    {
        try
        {
            if($puser_group instanceof user_group)
            {
                return true;
            }
            throw new exception("Bad user group dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_user_group_option($puser_group_option)
    {
        try
        {
            if($puser_group_option instanceof user_group_option)
            {
                return true;
            }
            throw new exception("Bad user group option dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_user_group_permission($puser_group_permission)
    {
        try
        {
            if($puser_group_permission instanceof user_group_permission)
            {
                return true;
            } 
            throw new exception("Bad user group permission dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_user_group_controller_view($puser_group_controller_view)
    {
        try
        {
            if($puser_group_controller_view instanceof user_group_controller_view)
            {
                return true;
            } 
            throw new exception("Bad user group controller view dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_permission($ppermission)
    {
        try
        {
            if($ppermission instanceof permission)
            {
                return true;
            }
            throw new exception("Bad permission dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_action($paction)
    {
        try
        {
            if($paction instanceof action)
            {
                return true;
            }
            throw new exception("Bad action dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_view($pview)
    {
        try
        {
            if($pview instanceof view)
            {
                return true;
            }
            throw new exception("Bad view dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_option($poption)
    {
        try
        {
            if($poption instanceof option)
            {
                return true;
            }
            throw new exception("Bad option dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_controller($pcontroller)
    {
        try
        {
            if($pcontroller instanceof controller)
            {
                return true;
            }
            throw new exception("Bad controller dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_controller_view($pcontroller_view)
    {
        try
        {
            if($pcontroller_view instanceof controller_view)
            {
                return true;
            } 
            throw new exception("Bad controller view dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_user_state($puser_status)
    {
        try
        {
            if($puser_status instanceof user_state)
            {
                return true;
            }
            throw new exception("Bad user state dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_plugin($pplugin)
    {
        try
        {
            if($pplugin instanceof plugin)
            {
                return true;
            }
            throw new exception("Bad plugin dataset");
        } 
        catch(exception $e)
        {
            return false;
        } 
    }

    static function is_plugin_option($pplugin_option)
    {
        try
        {
            if($pplugin_option instanceof plugin_option)
            {
                return true;
            }
            throw new exception("Bad plugin option dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    static function is_app_option($papp_option)
    {
        try
        {
            if($papp_option instanceof app_option)
            {
                return true;
            }
            throw new exception("Bad app option dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function is_menu_category_option($pcategory_option)
    {
        try
        {
            if($pcategory_option instanceof menu_category_option) return true;
            else throw new exception("Bad menu category option dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function is_menu_button_option($pbutton_option)
    {
        try
        {
            if($pbutton_option instanceof menu_button_option) return true;
            else throw new exception("Bad menu button option dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function is_menu_button($pmenu_button)
    {
        try
        {
            if($pmenu_button instanceof menu_button) return true;
            else throw new exception("Bad menu button dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
    }

    public function is_menu_category($pmenu_category)
    {        
        try
        {
            if($pmenu_category instanceof menu_category) return true;
            else throw new exception("Bad menu category dataset");
        } 
        catch(exception $e)
        {
            return false;
        }
        
    }

}

