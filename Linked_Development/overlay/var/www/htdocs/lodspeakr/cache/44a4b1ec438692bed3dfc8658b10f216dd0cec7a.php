<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/htdocs/lodspeakr/components/services/classes/html.template */
function haanga_44a4b1ec438692bed3dfc8658b10f216dd0cec7a($vars, $return=FALSE, $blocks=array())
{
    extract($vars);
    if ($return == TRUE) {
        ob_start();
    }
    echo Haanga::Load('../../includes/header.inc', $vars, TRUE, $blocks).'
    <div class="container-fluid">
    <h1>Classes available</h1>
	<ul>
    ';
    foreach ((array) $models->main as  $row) {
        echo '
        <li><a href="'.htmlspecialchars($lodspk['baseUrl']).'instances/'.htmlspecialchars($row->resource->curie).'">'.htmlspecialchars($row->resource->curie).'</a></li>
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