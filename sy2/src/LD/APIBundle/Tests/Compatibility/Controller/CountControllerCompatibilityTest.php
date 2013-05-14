<?php

namespace LD\APIBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * API Tests
 */
class CountControllerObjectTest extends WebTestCase
{
    private $apikey = null;
    private $activeUrl = false;

    public function setUp()
    {
        if (null == static::$kernel) {
            static::$kernel = static::createKernel();
            static::$kernel->boot();
        }
        $apikeyfile = static::$kernel->getRootDir() . '/config/apikey';
        $this->apikey = file_get_contents($apikeyfile);
    }

    /**
     * Test json as query parameter
     */
    public function testCount()
    {
        $client = static::createClient();
        $graphs = array_keys($client->getContainer()->getParameter('graphs'));

        $objects = array(
            'documents',
            // 'item',
            'organisations',
        );
        echo "\n** count items skipped **\n";
        $params = array(
            'country',
            // 'keyword',
            'region',
            'theme',
        );
        echo "** count keywords skipped **\n";

        foreach ($graphs as $graph) {
            if ($graph) {
                foreach ($objects as $object) {
                    foreach ($params as $param) {
                        $this->activeUrl = sprintf(
                            '/%s/count/%s/%s?format=json',
                            $graph, $object, $param
                        );
                        $client->getContainer()->get('logger')->debug('Fetching: ' . $this->activeUrl);
                        $client->request('GET', $this->activeUrl);
                        $response1 = json_decode(
                            $client->getResponse()->getContent(), true
                        );

                        $remoteurl = sprintf(
                            'http://api.ids.ac.uk/openapi/%s/count/%s/%s',
                            $graph, $object, $param
                        );
                        $client->getContainer()->get('logger')->debug('Remote URL: ' . $remoteurl);
                        $response2 = $this->fetchData($remoteurl);
                        $diff = $this->arrayDiffAssocRecursive($response1, $response2);
                        $this->assertTrue(
                            count($diff) == 0,
                            sprintf(
                                "%s does not match %s\n%s\n",
                                $this->activeUrl, $remoteurl, json_encode($diff, JSON_PRETTY_PRINT)
                            )
                        );
                    }
                }
            }
        }
    }

    protected function fetchData($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Accept: application/json',
                'Token-Guid: ' . $this->apikey,
            )
        );
        $data = curl_exec($curl);
        echo "\n\n$data\n\n";

        return json_decode($data, true);
    }


    protected function arrayDiffAssocRecursive($array1, $array2)
    {
        $difference = array();
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) || !is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->arrayDiffAssocRecursive($value, $array2[$key]);
                    if (!empty($new_diff)) $difference[$key] = $new_diff;
                }
            } else if (!array_key_exists($key, $array2) || $array2[$key] !== $value) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }
}
