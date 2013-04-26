<?php

class LDController extends BaseController {

	public function showEcho($object = false, $parameter =  false, $query = false)
	{
		return json_encode(
			array(
			 	'object' => $object,
			 	'parameter' => $parameter,
			 	'query' => $query,
		 	),
			JSON_PRETTY_PRINT
		);
	}

}
