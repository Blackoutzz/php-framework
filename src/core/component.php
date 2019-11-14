<?php
namespace core;
use core\common\regex;
use core\common\components\str;
use core\backend\components\database;

/**
 * Core Component
 *
 * Extends program to component
 *
 * @version 1.0
 * @author  mick@blackoutzz.me
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

abstract class component
{

    protected function get_user()
    {
        return program::$user;
    }

    protected function get_encoder()
    {
        return program::$cryptography;
    }

    protected function get_databases()
    {
        return program::$databases;
    }

    protected function has_database()
    {
        return (count(program::$databases));
    }

    protected function get_controller_view()
    {
        
        return program::$routing->get_controller_view();
    }

    protected function get_controller()
    {
        return program::$routing->get_controller();
    }

    protected function get_controller_name()
    {
        return program::$routing->get_controller()->get_name();
    }

    protected function get_view()
    {
        return program::$routing->get_view();
    }

    protected function get_view_name()
    {
        return program::$routing->get_view()->get_name();
    }

    protected function get_string($pstring)
    {
        return new str($pstring);
    }

    protected function is_slug($pslug)
    {
        return regex::is_slug($pslug);
    }

    protected function is_numeric($pnumeric)
    {
        return regex::is_numeric($pnumeric);
    }

    protected function get_base_namespace()
    {
        $namespace = __NAMESPACE__;
        $namespaces = explode('\\',$namespace);
        return $namespaces[0];
    }

    protected function get_namespaces()
    {
        $namespace = __NAMESPACE__;
        return explode('\\',$namespace);
    }

    protected function get_namespace()
    {
        return __NAMESPACE__;
    }

    protected function push()
    {
        program::push();
    }

    protected function pull()
    {
        return program::pull();
    }

}
