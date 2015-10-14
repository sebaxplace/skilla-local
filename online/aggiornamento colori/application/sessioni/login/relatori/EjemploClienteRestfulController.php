<?php

namespace Relatori\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Http\Client as HttpClient;

/**
 * Description of ServidorWsController
 *
 * @author Andres
 */
class EjemploClienteRestfulController extends AbstractActionController {

    public function indexAction() {
        
        $client = new HttpClient();
        
        $client->setAdapter('Zend\Http\Client\Adapter\Curl');
        
        $method = $this->params()->fromQuery('method', 'get');
        $client->setUri('http://posterlab.skilla.com/relatori/rest/ejemplo-servidor-restful');

        switch ($method) {
            case 'get-list' :
                $client->setMethod('GET');
                break;
            case 'get' :
                $client->setMethod('GET');
                $client->setParameterGET(array('id' => 4));
                break;
            case 'create' :
                $client->setMethod('POST');
                $client->setParameterPOST(array('data' => 'Programacion Java SE'));
                break;
            case 'update' :

                $data = array('data' => 'Curso Spring Framework');
                $adapter = $client->getAdapter();

                $adapter->connect('posterlab.skilla.com', 80);

                $uri = $client->getUri() . '/2';

                // Enviamos con Method PUT, con el parametro $data
                $adapter->write('PUT', new \Zend\Uri\Uri($uri), 1.1, array(), http_build_query($data));

                $responsecurl = $adapter->read();
                list($headers, $content) = explode("\r\n\r\n", $responsecurl, 2);
                $response = $this->getResponse();

                $response->getHeaders()->addHeaderLine('content-type', 'application/json; charset=utf-8');
                $response->setContent($content);

                return $response;
            case 'delete' :
                $adapter = $client->getAdapter();

                $adapter->connect('posterlab.skilla.com', 80);
                $uri = $client->getUri() . '/1'; //enviamos param id = 1
                // Enviamos con Method DELETE
                $adapter->write('DELETE', new \Zend\Uri\Uri($uri), 1.1, array());

                $responsecurl = $adapter->read();
                list($headers, $content) = explode("\r\n\r\n", $responsecurl, 2);
                $response = $this->getResponse();

                $response->getHeaders()->addHeaderLine('content-type', 'application/json; charset=utf-8');
                $response->setContent($content);

                return $response;
            default :
                $client->setMethod('GET');
                break;
        }

        //enviamos get/get-list/create
        $response = $client->send();
        if (!$response->isSuccess()) {
            // reportamos la falla
            $message = $response->getStatusCode() . ': ' . $response->getReasonPhrase();

            $response = $this->getResponse();
            $response->setContent($message);
            return $response;
        }
        $body = $response->getBody();

        $response = $this->getResponse();
        $response->setContent($body);

        return $response;
    }

}

