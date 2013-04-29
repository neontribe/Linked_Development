<?php

namespace LD\DevBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Default home page
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        $docroot = $this->get('kernel')->getRootDir() . '/../web';
        $cmt = 'Never';
        $dmt = 'Never';

        $cif = $docroot . '/coverage/index.html';
        $dif = $docroot . '/docs/index.html';

        if (file_exists($cif)) {
            $cmt = date('Y/m/d H:s', filemtime($cif));
        }

        if (file_exists($dif)) {
            $dmt = date('Y/m/d H:s', filemtime($dif));
        }

        return array(
            'coveragemtime' => $cmt,
            'docsmtime' => $dmt,
        );
    }
}
