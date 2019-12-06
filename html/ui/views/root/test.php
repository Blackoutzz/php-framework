<pre>
<?php
    use core\backend\database\mysql\datasets\action;
    $test = new action(array("name"=>"test"));
    var_dump(unserialize(serialize($test)));
?>
</pre>