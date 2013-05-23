<?php

class POCController extends BaseController {

	public function showQuery()
	{
		$query = Input::get('query', false);
		$results = false;
		$endpoint = Config::get('sparql.endpoint');

		if ($query) {
			$url = sprintf(
				'%s?query=%s',
				$endpoint,
				urlencode($query)
			);
			try {
				$results = file_get_contents($url);
			} catch (\Exception $e) {
				$results = $e->getMessage();
			}
			// $results = file_get_contents($url);
		}

		return View::make(
			'query',
			 array(
			 	'query' => $query,
			 	'endpoint' => $endpoint,
			 	'results' => htmlspecialchars($results),
		 	)
		);
	}

}
/*
http://api.ids.ac.uk/openapi/eldis/search/documents/?keyword=af* 
*/

/*
prefix FOAF: <http://xmlns.com/foaf/0.1/>
prefix DCTERMS: <http://purl.org/dc/terms/>
prefix RDFS: <http://www.w3.org/2000/01/rdf-schema#>
prefix RDF: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
select ?a ?b ?c where {?a RDF:type FOAF:organization .
?a ?b ?c
} limit 50
*/

/*
select count(*) where {?a ?b ?c}

///////////////////////////////////////////////////////////////////////////

http://linked-development.org/eldis/

select  ?b ?c  
 where {<http://linked-development.org/eldis/output/A64840/> ?b ?c
} limit 100

///////////////////////////////////////////////////////////////////////////
*/

/*

http://api.ids.ac.uk/openapi/eldis/search/organisations/?country=india&theme=climate%20change

200 OK
Vary: Authenticate, Accept
Allow: GET, HEAD, OPTIONS
X-Throttle: status=SUCCESS; next=12.71 sec

{
    "metadata": {
        "start_offset": 0, 
        "total_results": 5
    }, 
    "results": [
        {
            "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/organisations/A63005/full/watershed-organisation-trust/", 
            "object_id": "A63005", 
            "object_type": "Organisation", 
            "title": "Watershed Organisation Trust"
        }, 
        {
            "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/organisations/A60604/full/institute-of-green-economy/", 
            "object_id": "A60604", 
            "object_type": "Organisation", 
            "title": "Institute of Green Economy"
        }, 
        {
            "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/organisations/A60202/full/undp-india/", 
            "object_id": "A60202", 
            "object_type": "Organisation", 
            "title": "UNDP India"
        }, 
        {
            "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/organisations/A60169/full/centre-for-social-markets-india/", 
            "object_id": "A60169", 
            "object_type": "Organisation", 
            "title": "Centre for Social Markets, India"
        }, 
        {
            "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/organisations/A59414/full/asian-cities-climate-change-resilience-network-indore-initiative/", 
            "object_id": "A59414", 
            "object_type": "Organisation", 
            "title": "Asian Cities Climate Change Resilience Network: Indore Initiative"
        }
    ]
}
*/