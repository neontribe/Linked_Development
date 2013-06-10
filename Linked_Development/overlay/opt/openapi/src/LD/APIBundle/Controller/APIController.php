<?php
namespace LD\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Top level API controller.
 *
 * This controller provides common functions for controllers.
 *
 * @see http://api.ids.ac.uk/
 */
class APIController extends Controller
{
    /**
     * @Route("/")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return new Response('Linked data open API');
    }

    /**
     * A generic query builder, maker and processor
     *
     * THis should work for most queries.  It detects and defines the graph,
     * builds the sparql and sends it to virtuoso via the sparql service.
     * the Response is parsed and the data readied to be returned.
     *
     * @param string $graph        eldis|rdf|all
     * @param string $spql         The array defining the sparql query
     * @param string $factoryclass The name of the class to be used to process the response
     * @param string $querybuilder The name of query builder class.
     * @param string $format       The resonse format to encode to, short|full
     * @param string $type         The type of the data object being passed, EasyRdf/Sparql/Result, EasyRdf/Graph or EasyRdf/Resource
     *
     * @return Response
     */
    protected function chomp($graph, $spql, $factoryclass, $querybuilder, $format = 'short', $type = null)
    {
        // get the sparql service
        $spqlsrvc = $this->get('sparql');

        // get/set graph
        $graphs = $this->container->getParameter('graphs');
        if (isset($graphs[$graph])) {
            $_graph = $graphs[$graph];
        } else {
            $_graph = null;
        }

        $_builder = new $querybuilder();
        $_builder->setContainer($this->container);
        $spqlsrvc->setQueryBuilder($_builder);

        // execute the query
        $data = $spqlsrvc->query($spql, $_graph);

        $factory = new $factoryclass();
        $factory->setContainer($this->container);
        $factory->process($data, $type);
        $response = $factory->getResponse($format);

        return $response;
    }

    /**
     * Wrapper to the symfony logger
     *
     * @param string $msg The message to log
     * @param string $lvl The level to log at, Default: debug
     */
    protected function logger($msg, $lvl = 'debug')
    {
        $this->get('logger')->$lvl($msg);
    }

    /**
     * Detect format and create appropriate response
     *
     * @param array $data The data to encode
     *
     * @return Response
     */
    protected function response($data)
    {
        // In the existing API format can be specified by the accepts header
        // but that can be trumped by the format parameter
        $format = $this->getRequest()->query->get('format', false);
        if ($format) {
            $this->logger('Format detected via parameter: ' . $format);
        } else {
            $_format = $this->getRequest()->headers->get('Accept', false);
            if ($_format) {
                $format = $this->_getContentType($_format);
                $this->logger('Format detected via header: ' . $format);
            }
        }

        $func = '_encode' . ucfirst($format);

        if (!method_exists($this, $func)) {
            $response = $this->render('LDAPIBundle::unsupportedFormat.json.twig');
            $response->headers->set('Content-type', 'application/json');

            return $response;
        }

        return call_user_func(array($this, $func), $data);
    }

    /**
     * Return a symfony2 response object encoded as JSON
     *
     * @param array $data The data to encode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _encodeJson($data)
    {
        $response = new Response($this->_toJson($data));
        $response->headers->set('Content-type', 'application/json');

        return $response;
    }

    /**
     * Endode data to a JSON string
     *
     * @param array $data
     *
     * @return string
     */
    protected function _toJson($data)
    {
        $json = defined('JSON_PRETTY_PRINT') ? json_encode($data, JSON_PRETTY_PRINT) : json_encode($data);

        return $json;
    }

    /**
     * Return a symfony2 response object encoded as HTML
     *
     * @param array $data The data to encode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _encodeHtml($data)
    {
        $response = $this->render(
            'LDAPIBundle:API:response.html.twig',
            array(
                'json' => $this->_toJson($data),
            )
        );
        $response->headers->set('Content-type', 'text/html');

        return $response;
    }

    /**
     * Return a symfony2 response object encoded as XHTML+XML
     *
     * @param array $data The data to encode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _encodeXhtml($data)
    {
        $response = $this->render(
            'LDAPIBundle:API:response.xhtml.twig',
            array(
                'json' => $this->_toJson($data),
            )
        );
        $response->headers->set('Content-type', 'application/xhtml+xml');

        return $response;
    }

    /**
     * Return a symfony2 response object encoded as XML
     *
     * @param array $data The data to encode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function _encodeXml($data)
    {
        // TODO - this does not produce xml the same as http://api.ids.ac.uk/openapi/bridge/search/documents/?country=Angola&format=xml
        $serializer = $this->container->get('jms_serializer');
        $xml = $serializer->serialize($data, 'xml');

        $response = new Response($xml);
        $response->headers->set('Content-type', 'application/xml');

        return $response;
    }

    /**
     * Get Mimetype from Accepts header
     *
     * @param string $accepts The Accepts string
     *
     * @return string
     */
    protected function _getContentType($accepts)
    {
        $_accepts = explode(',', $accepts);
        $mimetypes = $this->container->getParameter('mimetypes');
        foreach ($_accepts as $type) {
            foreach ($mimetypes as $key => $mimelist) {
                if (in_array($type, $mimelist)) {

                    return $key;
                }
            }
        }

        return false;
    }
}
