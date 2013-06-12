<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/htdocs/lodspeakr/components/services/instances/html.template */
function haanga_e2e23358a25369ddcb67466a4d029ee17a09ce65($vars, $return=FALSE, $blocks=array())
{
    extract($vars);
    if ($return == TRUE) {
        ob_start();
    }
    echo Haanga::Load('../../includes/header.inc', $vars, TRUE, $blocks).'
    <div class="container-fuild">
      <h1>Instances of class '.htmlspecialchars($lodspk['args']['arg0']).'</h1>
        <ul>
    ';
    foreach ((array) $models->main as  $row) {
        echo '
        <li><a href="'.htmlspecialchars($row->resource->value).'">'.htmlspecialchars($row->resource->curie).'</a></li>
    ';
    }
    echo '
    </ul>
   </div>
  </body>
</html>
';
    if ($return == TRUE) {
        return ob_get_clean();
    }
}