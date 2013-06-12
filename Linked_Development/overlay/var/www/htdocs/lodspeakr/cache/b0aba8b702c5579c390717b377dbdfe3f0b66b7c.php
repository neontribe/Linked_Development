<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/htdocs/lodspeakr/components/services/namedGraphs/html.template */
function haanga_b0aba8b702c5579c390717b377dbdfe3f0b66b7c($vars, $return=FALSE, $blocks=array())
{
    extract($vars);
    if ($return == TRUE) {
        ob_start();
    }
    echo Haanga::Load('../../includes/header.inc', $vars, TRUE, $blocks).'
  <div class="container-fluid">
      <h1>Named graphs available</h1>
  ';
    if (empty($first->main->g) === FALSE) {
        echo '
      <ul>
    ';
        foreach ((array) $models->main as  $row) {
            echo '
        <li>'.htmlspecialchars($row->g->value).'</li>
    ';
        }
        echo '
      </ul>
  ';
    } else {
        echo '
    <div class="alert alert-info">
      <p>No named graphs found</p>
    </div>
  ';
    }
    echo '
    </div>
  </body>
</html>
';
    if ($return == TRUE) {
        return ob_get_clean();
    }
}