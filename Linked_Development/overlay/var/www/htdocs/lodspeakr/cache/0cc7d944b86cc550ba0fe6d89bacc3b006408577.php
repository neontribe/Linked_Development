<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/htdocs/lodspeakr/components/types/rdfs:Resource/html.template */
function haanga_0cc7d944b86cc550ba0fe6d89bacc3b006408577($vars, $return=FALSE, $blocks=array())
{
    extract($vars);
    if ($return == TRUE) {
        ob_start();
    }
    echo Haanga::Load('../../includes/header.inc', $vars, TRUE, $blocks).'
    <div class="container-fluid">
    <h1>Default view</h1>

    <table class="table table-striped" about="'.htmlspecialchars($uri).'">
    <thead>
      <tr><th>Subject</th><th>Predicate</th><th>Object</th></tr>
    </thead>
    ';
    $isfirst_1  = TRUE;
    foreach ((array) $models->po as  $row) {
        echo '
     <tr>
<td>';
        if (empty($isfirst_1) === FALSE) {
            echo '<a href=\''.htmlspecialchars($lodspk['this']['value']).'\'>'.htmlspecialchars($lodspk['this']['curie']).'</a>';
        }
        echo '</td>
     <td><a href=\''.htmlspecialchars($row->p->value).'\'>'.htmlspecialchars($row->p->curie).'</a></td>
     
        <td>
        ';
        if ($row->o->uri == 1) {
            echo '
        <a rel=\''.htmlspecialchars($row->p->value).'\' href=\''.htmlspecialchars($row->o->value).'\'>'.htmlspecialchars($row->o->curie).'</a>
        ';
        } else {
            echo '
        <span property=\''.htmlspecialchars($row->p->value).'\'>'.htmlspecialchars($row->o->value).'</span>
        ';
        }
        echo '
        </td>

        </tr>
    ';
        $isfirst_1  = FALSE;
    }
    echo '

    ';
    $isfirst_1  = TRUE;
    foreach ((array) $models->sp as  $row) {
        echo '
      <tr>
        <td><a href=\''.htmlspecialchars($row->s->value).'\'>'.htmlspecialchars($row->s->curie).'</a></td>

        <td><a rev=\''.htmlspecialchars($row->s->value).'\' href=\''.htmlspecialchars($row->p->value).'\'>'.htmlspecialchars($row->p->curie).'</a></td>
<td>';
        if (empty($isfirst_1) === FALSE) {
            echo '<a href=\''.htmlspecialchars($lodspk['this']['value']).'\'>'.htmlspecialchars($lodspk['this']['curie']).'</a>';
        }
        echo '</td>
        </tr>
    ';
        $isfirst_1  = FALSE;
    }
    echo '
    <thead>
      <tr><th>Subject</th><th>Predicate</th><th>Object</th></tr>
    </thead>

    </table>
    </div>    
    
  </body>
</html>
';
    if ($return == TRUE) {
        return ob_get_clean();
    }
}