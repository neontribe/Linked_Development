<?php

//Import
if(isset($_GET['q']) && $_GET['q'] == 'import'){
  include_once('classes/Importer.php');
  $imp = new Importer();
  $imp->run();
  exit(0);
}

//Test if LODSPeaKr is configured
if(!file_exists('settings.inc.php')){
  echo 'Need to configure lodspeakr first. Please run "install.sh". Alternatively, you can <a href="import">import an existing application</a>';
  exit(0);
}

include_once('common.inc.php');
//Debug output

$conf['logfile'] = null;
if($conf['debug']){
  include_once('classes/Logging.php');
  if(isset($_GET['q']) && $_GET['q'] == 'logs'){
    Logging::init();
    exit(0);
  }else{
    $conf['logfile'] = Logging::createLogFile($_GET['q']);
    //error_reporting(E_ALL);
  }
}else{
  error_reporting(E_ERROR);
}

include_once('classes/HTTPStatus.php');
include_once('classes/Utils.php');
include_once('classes/Queries.php');
include_once('classes/Endpoint.php');
include_once('classes/Convert.php');
$results = array();
$firstResults = array();
$endpoints = array();
$endpoints['local'] = new Endpoint($conf['endpoint']['local'], $conf['endpointParams']['config']);

$acceptContentType = Utils::getBestContentType($_SERVER['HTTP_ACCEPT']);
$extension = Utils::getExtension($acceptContentType); 


//Check content type is supported by LODSPeaKr
if($acceptContentType == NULL){
  HTTPStatus::send406($uri);
}

//Export
if($conf['export'] && $_GET['q'] == 'export'){
  include_once('settings.inc.php');
  include_once('classes/Exporter.php');
  $exp = new Exporter();
  header('Content-Type: text/plain');
  $exp->run();
  exit(0);
}

//Redirect to root URL if necessary
$uri = $conf['basedir'].$_GET['q'];
$localUri = $uri;
if($uri == $conf['basedir']){
  header('Location: '.$conf['root']);
  exit(0);
}


//Configure external URIs if necessary
$localUri = $conf['basedir'].$_GET['q'];

$uri = Utils::getMirroredUri($localUri);


//Modules
foreach($conf['modules']['available'] as $i){
  $className = $i.'Module';
  $currentModule = $conf['modules']['directory'].$className.'.php';
  if(!is_file($currentModule)){
  	HTTPStatus::send500("<br/>Can't load or error in module <tt>".$currentModule."</tt>" );
  	exit(1);
  }
  require_once($currentModule);
  $module = new $className();
  $matching = $module->match($uri);
  if($matching != FALSE){
  	$module->execute($matching);
  	if($conf['logfile'] != null){
  	  fwrite($conf['logfile'], "]}");
  	  fclose($conf['logfile']);
  	}
  	exit(0);
  }
}

HTTPStatus::send404($uri);
?>
