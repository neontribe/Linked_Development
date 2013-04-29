<?php

namespace LD\APIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Top level API controller
 *
 * @see http://api.ids.ac.uk/
 */
class APIController extends Controller
{
    /**
     * Get data for current route
     *
     * @return array
     */
    protected function getData()
    {
        $routeName = $this->container->get('request')->get('_route');
        $resource = sprintf(
            '%s/../Resources/fixtures/%s.%s',
            __DIR__, $routeName, 'json'
        );

        return json_decode(file_get_contents($resource), true);
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
        if (!$format) {
            $_format = $this->getRequest()->headers->get('Accepts', false);
            if ($_format) {
                $this->get('logger')->debug('2');
                // strip the application/ off the front and we may have xhtml+xml, clean out the '+'
                $format = str_replace(
                    'application/',
                    '',
                    str_replace('+', '', $_format)
                );
            }
        }
        if (!$format) {
            // fail over to json
            $format = 'json';
        }

        $func = 'encode' . ucfirst($format);

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
    protected function encodeJson($data)
    {
        $response = new Response($this->_encodeJson($data));
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
    protected function _encodeJson($data)
    {
        if ($this->get('kernel')->getEnvironment() == 'dev' && defined('JSON_PRETTY_PRINT')) {
            $json = json_encode($data, JSON_PRETTY_PRINT);
        } else {
            $json = json_encode($data);
        }

        return $json;
    }

    /**
     * Return a symfony2 response object encoded as HTML
     *
     * @param array $data The data to encode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function encodeHtml($data)
    {
        $response = $this->render(
            'LDAPIBundle:API:response.html.twig',
            array(
                'json' => $this->_encodeJson($data),
            )
        );
        $response->headers->set('Content-type', 'text/html');

        return $response;
    }

    /**
     * Wrapper for encodeXhtmlxml
     *
     * @param array $data The data to encode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function encodeXhtml($data)
    {
        return $this->encodeXhtmlxml($data);
    }

    /**
     * Return a symfony2 response object encoded as XHTML+XML
     *
     * @param array $data The data to encode
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function encodeXhtmlxml($data)
    {
        $response = $this->render(
            'LDAPIBundle:API:response.xhtml.twig',
            array(
                'json' => $this->_encodeJson($data),
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
    protected function encodeXml($data)
    {
        // TODO - this does not produce xml the same as http://api.ids.ac.uk/openapi/bridge/search/documents/?country=Angola&format=xml
        $serializer = $this->container->get('jms_serializer');
        $xml = $serializer->serialize($data, 'xml');

        $response = new Response($xml);
        $response->headers->set('Content-type', 'application/xml');

        return $response;
    }
}
