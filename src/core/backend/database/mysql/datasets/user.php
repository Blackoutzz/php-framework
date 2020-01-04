<?php
namespace core\backend\database\mysql\datasets;
use core\backend\database\mysql\dataset;
use core\backend\database\mysql\model;
use core\program;

class user extends dataset
{

    protected $name = "";

    protected $email = "";

    protected $password = "";

    protected $group = 3;

    protected $state = 1;

    protected $last_update;

    public  function save($pid = 0)
    {
        if($this->exist())
        {
            return $this->execute_prepared_update_query("UPDATE `users` SET name=? , password=? , email=? , group=? , state=? WHERE id=?","sssiii",array($this->name,$this->password,$this->email,$this->group,$this->state,$this->id),$pid);
        } else {
            if($this->execute_prepared_insert_query("INSERT INTO `users` (`name`,`password`,`email`,`group`,`state`) VALUES (?,?,?,?,?)","sssii",array($this->name,$this->password,$this->email,$this->group,$this->state),$pid))
            {
                return true;
            }
            return false;
        }
    }

    public function get_name()
    {
        return $this->name;
    }

    public function set_name($pname)
    {
        $this->name = $this->get_sanitized_string($pname);
    }

    public  function get_password()
    {
        return $this->password;
    }

    public  function set_password($ppassword)
    {
        $this->password = program::$cryptography->hash($ppassword);
    }

    public  function get_email()
    {
        return $this->email;
    }

    public  function set_email($pemail)
    {
        $this->email = $this->get_sanitized_string($pemail);
    }

    public  function get_group()
    {
        return model::get_user_group_by_id($this->group);
    }

    public  function set_group($puser_group)
    {
        if(model::is_user_group($puser_group))
        {
            $this->group = $puser_group->get_id();
        } else {
            if($puser_group != NULL && is_integer($puser_group))
            {
                $new_user_group = model::get_user_group_by_id($puser_group);
                if($new_user_group != NULL && model::is_user_group($new_user_group))
                {
                    $this->group = $new_user_group->get_id();
                }
            }
        }
    }

    public  function get_state()
    {
        return model::get_user_state_by_id($this->state);
    }

    public  function set_state($puser_state)
    {
        if(model::is_user_status($puser_state))
        {
            $this->state = $puser_state->get_id();
            $this->save();
        } else {
            if(is_integer($puser_state))
            {
                if($new_user_state = model::get_user_state_by_id($puser_state))
                {
                    $this->state = $new_user_state->get_id();
                    $this->save();
                }
            }
            if(is_string($puser_state))
            {
                if($new_user_state = model::get_user_state_by_name($puser_state))
                {
                    $this->state = $new_user_state->get_id();
                    $this->save();
                }
            }
        }
    }

    public  function get_permissions()
    {
        return model::get_user_permission_by_user($this);
    }

    public  function add_permission($ppermission,$pgranted)
    {
        $pnew_permission = array("index"=>NULL,"user"=>$this->index,"permission"=>$ppermission->get_id(),"granted"=>$pgranted);
        $new_permission = new user_permission($pnew_permission);
        return $new_permission->save();
    }

    public  function remove_permission($ppermission)
    {
        $user_permission = model::get_user_permission($this,$ppermission);
        if(model::is_user_permission($user_permission) && $user_permission != NULL)
        {
            $user_permission->set_granted(false);
            return $user_permission->save();
        }
        return false;
    }

    public  function get_controller_views()
    {
        return model::get_user_controller_views_by_user($this);
    }

    public  function get_actions($plimit = 50)
    {
        return model::get_user_actions_by_user($this);
    }

    public function get_options()
    {
        $options = array();
        if($data = $this->get_prepared_request("SELECT * FROM `user_options` where user=?","i",array($this->id)))
        {
            foreach($data as $pdata)
            {
                $options[] = new user_option($pdata);
            }
        }
        return $options;
    }

    public function set_option($poption,$pvalue)
    {
        $value = (string) $pvalue;
        if(is_string($poption))
        {
            $options = model::get_options_by_name($poption);
            if(count($options) >= 1)
            {
                // Option exist
                $option = $options[0];
                $user_options = model::get_user_options_by_user_and_option($this,$option);
                if(count($user_options) >= 1)
                {
                    // Old user option
                    $user_option = $user_options[0];
                    $user_option->set_value($value);
                    return $user_option->save();
                } else {
                    // New user option
                    $user_option = new user_option(array("user"=>$this->id,"option"=>$option->id,"value"=>$value));
                    return $user_option->save();
                }
            } else {
                // New Option
                $option = new option(array("name"=>$poption));
                if($option->save())
                {
                    $user_option = new user_option(array("user"=>$this->id,"option"=>$option->id,"value"=>$value));
                    return $user_option->save();
                }
            }
        }
        return false;
    }

    public function get_option($pname)
    {
        return model::get_user_option_by_user_and_option($this,$pname);
    }

    public  function do_action($paction)
    {
        //
    }

    public function get_gravatar($style="")
    {
        if(isset($this->email) && $this->email != "")
        {
            $grav_url = "https://secure.gravatar.com/avatar/".md5($this->email).".png";
            return"<img src='{$grav_url}' alt='{$this->username}' style='{$style}' />";
        }
    }

    public function get_last_update()
    {
        return $this->last_update;
    }

    // Client

	public function can($ppermission)
    {
        try
        {
            $group_can = model::get_user_group_permission_by_user_group_and_permission($this->group,$ppermission);
            if(model::is_user_group_permission($group_can))
            {
                if($group_can->get_granted())
                {
                    return true;
                }
            }
            $user_can = model::get_user_permission_by_user_and_permission($this->id,$ppermission);
            if(model::is_user_permission($user_can))
            {
                if($user_can->get_granted())
                {
                    return true;
                }
            }
            throw new Exception("{$this->name} cannot ".$ppermission);
        }
        catch (Exception $e) 
        {
            return false;
        }
	}

    public function has_access()
    {
        try
        {
            $user_access = model::get_user_controller_view_by_user_and_controller_view($this->id,$this->get_controller_view());
            $group_access = model::get_user_group_controller_view_by_user_group_and_controller_view($this->group,$this->get_controller_view());
            if(model::is_user_controller_view($user_access))
            {
                if($user_access->get_granted())
                {
                    return true;
                }
            }
            if(model::is_user_group_controller_view($group_access))
            {
                if($group_access->get_granted())
                {
                    return true;
                }
            }
            $controller_name = $this->get_controller_name();
            $view_name = $this->get_view_name();
            throw new exception("Permission denied to access '{$controller_name}/{$view_name}'.");
        }
        catch (exception $e)
        {
            return false;
        }
    }

}
