<?php

class Alert
{

    public function success($message)
    {
        echo "<div class=\"alert alert-success alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>" . $message . "</div>\n";
    }

    public function info($message)
    {
        echo "<div class=\"alert alert-info alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>" . $message . "</div>\n";
    }

    public function warning($message)
    {
        echo "<div class=\"alert alert-warning alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>" . $message . "</div>\n";
    }

    public function danger($message)
    {
        echo "<div class=\"alert alert-danger alert-dismissable\"><a class=\"close fa fa-close\" data-dismiss=\"alert\" aria-label=\"close\"></a>" . $message . "</div>\n";
    }

}

?>
