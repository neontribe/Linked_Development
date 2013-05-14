<?php

namespace LD\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Top level API controller
 *
 * @see http://api.ids.ac.uk/
 */
class GetController extends APIController
{

    /**
     * Retrieve a single object
     *
     * This can be a document, organisation, theme, country or  region
     *
     * @Route("/{graph}/get/assets/{id}", defaults={"format" = "short", "name" = "null"})
     * @Route("/{graph}/get/assets/{id}/{format}", defaults={"name" = "null"}, requirements={"format" = "short|full"})
     * @Route("/{graph}/get/assets/{id}/{format}/{name}", requirements={"format" = "short|full"})
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAssetAction($id, $format, $name)
    {
        $spqlsrvc = $this->get('sparql');
        $spqls = $this->container->getParameter('sparqls');
        $spql = str_replace('__ID__', $id, $spqls['get']['assets']);
        $data = $spqlsrvc->curl($spql);

        $_response = array();
/*

        {
    "results": {
        "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/documents/A12345/full/sharing-knowledge-for-community-development-and-transformation-a-handbook/",
        "object_id": "A12345",
        "object_type": "Document",
        "title": "Sharing knowledge for community development and transformation: a handbook"
    }

 {
    "results": {
        "author": [
            "K. Mchombu"
        ],
        "category_region_array": {
            "Region": [
                {
                    "archived": "false",
                    "deleted": "0",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/regions/C21/full/africa-south-of-sahara/",
                    "object_id": "C21",
                    "object_name": "Africa South of Sahara",
                    "object_type": "region"
                }
            ]
        },
        "category_region_ids": [
            "C21"
        ],
        "category_region_path": [
            "Africa South of Sahara"
        ],
        "category_subject_array": {
            "subject": [
                {
                    "archived": "false",
                    "level": "1",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/subjects/C1034/full/eadi/",
                    "object_id": "C1034",
                    "object_name": "EADI",
                    "object_type": "subject"
                },
                {
                    "archived": "false",
                    "level": "1",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/subjects/C1052/full/research-dissemination/",
                    "object_id": "C1052",
                    "object_name": "Research dissemination",
                    "object_type": "subject"
                }
            ]
        },
        "category_subject_ids": [
            "C1034",
            "C1052",
            "C4"
        ],
        "category_subject_path": [
            "EADI",
            "Research dissemination"
        ],
        "category_theme_array": {
            "theme": [
                {
                    "archived": "false",
                    "level": "2",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/themes/C833/full/mobile-and-telecentre-innovation/",
                    "object_id": "C833",
                    "object_name": "Mobile and telecentre innovation",
                    "object_type": "theme"
                },
                {
                    "archived": "false",
                    "level": "2",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/themes/C839/full/communication-manuals/",
                    "object_id": "C839",
                    "object_name": "Communication manuals",
                    "object_type": "theme"
                },
                {
                    "archived": "false",
                    "level": "2",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/themes/C844/full/participation-manuals/",
                    "object_id": "C844",
                    "object_name": "Participation Manuals",
                    "object_type": "theme"
                },
                {
                    "archived": "false",
                    "level": "2",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/themes/C848/full/training/",
                    "object_id": "C848",
                    "object_name": "Training",
                    "object_type": "theme"
                },
                {
                    "archived": "false",
                    "level": "1",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/themes/C883/full/participation/",
                    "object_id": "C883",
                    "object_name": "Participation",
                    "object_type": "theme"
                },
                {
                    "archived": "false",
                    "level": "2",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/themes/C937/full/communication-manuals/",
                    "object_id": "C937",
                    "object_name": "Communication manuals",
                    "object_type": "theme"
                }
            ]
        },
        "category_theme_ids": [
            "C833",
            "C839",
            "C844",
            "C848",
            "C883",
            "C937",
            "C2",
            "C782",
            "C836",
            "C934"
        ],
        "category_theme_path": [
            "ICTs for development",
            "ICTs for development.Mobile and telecentre innovation",
            "Manuals",
            "Manuals.Communication manuals",
            "Manuals.Participation Manuals",
            "Manuals.Training",
            "Participation",
            "Research to policy",
            "Research to policy.Communication manuals"
        ],
        "corporate_author": "",
        "country_focus": [
            ""
        ],
        "country_focus_ids": [
            ""
        ],
        "date_created": "2003-04-03 00:00:00",
        "date_updated": "2007-03-30 18:12:59",
        "description": "This is a handbook intended for rural community members who want to better share information. It is aimed specifically at those who want to start a Community Information Resource Centre (CIRC). Tha handbook begins by discussing the role of information in human development, specifically in small rural communities.<P>The rest of the book acts as a practical learning guide for rural community members working towards greater information provision and sharing in their communitties. It consists of a chapter that discusses the information sources of a Community Information Resource Centre (CIRC). It explores two sources of information (local and external) and their roles in the development process of the community. The process described involves community participation in defining the needed information content.<P>The next chapter examines different types of information services and stresses the importance of an ongoing dialogue with the community so that all interests and needs are represented. The final chapter discusses issues around the sustainability of the resource centre. This chapter examines several issues that are key to sustainable information services. These are participation and governance, management and coordination, training for those running the information resource centre and fundraising from local and external resource providers.<P>Appendices discuss skills such as cataloguing and selection of materials.<P>[Adapted from authors] <img src='http://api.ids.ac.uk/tracking/trackimg.cfm?beacon_guid=49751904-2986-4d80-be13-b4dbeb11ff46' width='1' height='1'>",
        "et_al": "false",
        "headline": "Manual for setting up community information resource centres",
        "keyword": [
            "Community Information Resource Centre (CIRC)",
            "ICT rural",
            "Information services",
            "Libraries",
            "Sharing knowledge 2",
            "Telecentres",
            "tools for ngos",
            "toolsforngos"
        ],
        "language_id": [
            "1"
        ],
        "language_name": "English",
        "licence_type": "Not Known",
        "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/documents/A12345/full/sharing-knowledge-for-community-development-and-transformation-a-handbook/",
        "name": "Sharing knowledge for community development and transformation: a handbook",
        "object_id": "A12345",
        "object_type": "Document",
        "publication_date": "2004-01-01 00:00:00",
        "publication_year": 2004,
        "publisher": "Oxfam",
        "publisher_array": {
            "Publisher": [
                {
                    "Country": "United Kingdom",
                    "metadata_url": "http://api.ids.ac.uk/openapi/eldis/get/organisations/A1417/full/oxfam/",
                    "object_id": "A1417",
                    "object_name": "Oxfam",
                    "object_type": "Organisation"
                }
            ]
        },
        "publisher_country": "United Kingdom",
        "publisher_id": [
            "A1417"
        ],
        "site": "eldis",
        "timestamp": "2013-05-08 20:19:35",
        "title": "Sharing knowledge for community development and transformation: a handbook",
        "urls": [
            "http://www.oxfam.ca/news-and-publications/publications-and-reports/sharing-knowledge-handbook-2/file"
        ],
        "website_url": "http://www.eldis.org/go/display&type=Document&id=12345"
    }
}
} */
        return $this->response(array($data, $_response));
    }

    /**
     * Retrieve a single object
     *
     * This can be a document, organisation, theme, country or  region
     *
     * @Route("/{graph}/get/{obj}/{parameter}/{format}/{query}")
     * @Route("/{graph}/get/{obj}/{parameter}/{format}",  defaults={"query" = "null"})
     * @Route("/{graph}/get/{obj}/{parameter}",  defaults={"format" = "short", "query" = "null"})
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAction($obj, $parameter, $format, $query)
    {
        $data = $this->getData($obj, $parameter);
        return $this->response($data);
    }

    /**
     * Retrieve all objects
     *
     * Currently the first 10 records are displayed (with a link to the next 10)
     *
     * @Route("/{graph}/get_all/{parameter}")
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getAllAction($parameter)
    {
        $data = $this->getData($parameter);

        return $this->response($data);
    }

    /**
     * Retrieve the objects in the level below (children) of searched for object
     *
     * This is only currently possible in Theme objects
     *
     * @Route("/{graph}/get_children/{obj}/{parameter}")
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getChildrenAction($obj, $parameter)
    {
        $data = $this->getData($obj, $parameter);
        return $this->response($data);
    }

    /**
     * Retrieve the objects in the level below (children) of searched for object
     *
     * This is only currently possible in Theme objects
     *
     * @Route("/fieldlist")
     * @Method({"GET", "HEAD", "OPTIONS"})
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function fieldListAction()
    {
        $data = $this->getData();
        return $this->response($data);
    }
}