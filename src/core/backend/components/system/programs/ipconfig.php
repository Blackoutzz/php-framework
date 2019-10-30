<?php
namespace core\backend\components\system\programs;
use core\common\components\str;
use core\backend\components\system\console;
use core\backend\components\system\os;

/**
 * ipconfig short summary.
 * 
 * ipconfig description.
 * 
 * @version 1.0
 * @author  Mickael Nadeau
 * @twitter @Mick4Secure
 * @github  @Blackoutzz
 * @website https://Blackoutzz.me
 **/

class ipconfig extends console
{

    protected function on_windows()
    {
        $this->program = "ipconfig";
    }

    protected function on_macos()
    {
        $this->program = "ifconfig";
    }

    protected function on_unix()
    {
        $this->program = "ifconfig";
    }

    public function get_output()
    {
        $output = new str($this->execute());
        $adapters = array();
        $current_adapter = 0;
        if(os::is_windows())
        {
            foreach($output->get_lines() as $id => $line)
            {
                if($line === "") continue;
                if(preg_match('~Ethernet Adapter (.*)\:~i',$line,$adapter_name))
                {
                    $current_adapter = $adapter_name[1];
                    $adapters[$current_adapter] = array();
                } else {
                    if(preg_match("~^\s+([A-z\s\-0-9]+)[^:]+\:\s+(.*)$~im",$line,$param))
                    {
                        $adapters[$current_adapter][$param[1]] = $param[2];
                    }
                }
            }
        }
        return $adapters;
    }

    public function get_widget()
    {
        echo '<div class="card shadow mb-4">';
        echo '  <div class="card-header py-3">';
        echo '      <h6 class="m-0 font-weight-bold text-primary">IP Configuration</h6>';
        echo '  </div>';
        echo '  <div class="card-body">';
        foreach($this->get_output() as $name => $adapter)
        {
            echo '  <div>';
            echo '      <div><span class="badge badge-primary">'.$name.'</span></div>';
            echo '      <div><table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;"><tbody>';
            foreach($adapter as $name => $value)
            {
                echo '          <tr role="row" class="odd">';
                echo '              <td>'.$name.'</td>';
                echo '              <td>'.$value.'</td>';
                echo '          </tr>';
            }
            echo '      </tbody></table></div>';
            echo '  </div>';
        }
        echo '  </div>';
        echo '</div>';
    }

}
