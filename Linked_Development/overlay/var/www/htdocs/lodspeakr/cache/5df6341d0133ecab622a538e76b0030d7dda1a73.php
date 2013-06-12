<?php
$HAANGA_VERSION  = '1.0.4';
/* Generated from /var/www/htdocs/lodspeakr/components/includes/header.inc */
function haanga_5df6341d0133ecab622a538e76b0030d7dda1a73($vars, $return=FALSE, $blocks=array())
{
    extract($vars);
    if ($return == TRUE) {
        ob_start();
    }
    echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>LODSPeaKr Basic Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="'.htmlspecialchars($lodspk['home']).'css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen" />
    <link rel="alternate" type="application/rdf+xml" title="RDF/XML Version" href="'.htmlspecialchars($lodspk['local']['value']).'.rdf" />
    <link rel="alternate" type="text/turtle" title="Turtle Version" href="'.htmlspecialchars($lodspk['local']['value']).'.ttl" />
    <link rel="alternate" type="text/plain" title="N-Triples Version" href="'.htmlspecialchars($lodspk['local']['value']).'.nt" />
    <link rel="alternate" type="application/json" title="RDFJSON Version" href="'.htmlspecialchars($lodspk['local']['value']).'.json" />
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
      .wait{
        background-image:url(\''.htmlspecialchars($lodspk['home']).'img/wait.gif\');
        background-repeat:no-repeat;
        padding-right:20px;
        background-position: right;
      }
    </style>
    <link href="'.htmlspecialchars($lodspk['home']).'css/bootstrap-responsive.min.css" rel="stylesheet" type="text/css" media="screen" />
    <script type="text/javascript" src="'.htmlspecialchars($lodspk['home']).'js/jquery.js"></script>
    <script type="text/javascript" src="'.htmlspecialchars($lodspk['home']).'js/bootstrap.min.js"></script>
    <script type="text/javascript" src="'.htmlspecialchars($lodspk['home']).'js/bootstrap-typeahead.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        $(\'.typeahead\').typeahead({
            source: function (typeahead, query) {
              $(\'.typeahead\').addClass(\'wait\');[]
              return $.get(\''.htmlspecialchars($lodspk['home']).'search/\'+encodeURIComponent(query), { }, function (data) {
                  $(\'.typeahead\').removeClass(\'wait\');[]
                  return typeahead.process(data);
              }, \'json\');
            },
            onselect: function (obj) {
              $(\'.typeahead\').attr(\'disabled\', true);
              window.location = obj.uri;
            }
        });
    });
    </script>
  </head>
  <body>
 <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="'.htmlspecialchars($lodspk['home']).'">'.htmlspecialchars($lodspk['title']).'</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li><a href="'.htmlspecialchars($lodspk['home']).'">Home</a></li>
              <li><a href="'.htmlspecialchars($lodspk['home']).'classes">Classes</a></li>
              <li><a href="'.htmlspecialchars($lodspk['home']).'namedGraphs">Named Graphs</a></li>
            </ul>
            <form class="navbar-search pull-left" action="">
              <input type="text" data-provide="typeahead" class="typeahead search-query span2" placeholder="Search"/>
            </form>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>
';
    if ($return == TRUE) {
        return ob_get_clean();
    }
}