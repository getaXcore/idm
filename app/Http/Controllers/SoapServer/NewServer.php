<?php
/**
 * Created by PhpStorm.
 * User: Taufan
 * Date: 26/02/2019
 * Time: 10:16
 */

namespace App\Http\Controllers\SoapServer;


use App\Http\Controllers\Controller;
use App\Http\Controllers\SoapService\NewService;
use App\Http\Controllers\SoapTypes\Detail;
use App\Http\Controllers\SoapTypes\ResDetail;
use App\Http\Controllers\SoapTypes\ResInquiry;
use App\Http\Controllers\SoapTypes\ResPayment;
use App\Http\Controllers\SoapTypes\ResReversal;
use Illuminate\Http\Response;
use Zend\Soap\AutoDiscover;
use Zend\Soap\Server;
use Zend\Soap\Wsdl;
use Zend\Soap\Wsdl\ComplexTypeStrategy\ArrayOfTypeSequence;

class NewServer extends Controller
{
    private $endpoint;

    public function __construct()
    {
        ini_set('soap.wsdl_cache_enable', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        ini_set('default_socket_timeout', 30);

        //$this->endpoint = self::currentUrlRoot();
        $this->endpoint = url('/soapi/v2/server');
    }
    public function server(){
        $response = new Response();
        try{
            $strategy = new ArrayOfTypeSequence();

            if (isset($_GET['wsdl'])) {
                $wsdl = new Wsdl('wsdl',$this->endpoint);
                $wsdl->addType(ResInquiry::class,'ResInquiry');
                $wsdl->addType(ResPayment::class,'ResPayment');
                $wsdl->addType(ResReversal::class,'ResReversal');
                $wsdl->addType(Detail::class,'Detail');

                $strategy->setContext($wsdl);
                $strategy->addComplexType(ResInquiry::class);
                $strategy->addComplexType(ResPayment::class);
                $strategy->addComplexType(ResReversal::class);
                $strategy->addComplexType(Detail::class);

                $discover = new AutoDiscover($strategy);
                $discover->setBindingStyle(array('style' => 'document'));
                $discover->setOperationBodyStyle(array('use' => 'literal'));
                $discover->setClass(NewService::class);
                $discover->setUri($this->endpoint);
                $discover->setServiceName('soapi');
                $xmlRes =  $discover->toXml();

                $response->setContent($xmlRes);
                $response->setStatusCode(200);
                $response->header('Content-Type','application/xml; charset=utf-8');

                return $response;
            }else{
                $params = array(
                    "uri" => $this->endpoint,
                    "encoding" => "UTF-8",
                    "soap_version" => SOAP_1_1,
                    "cache_wsdl" => WSDL_CACHE_NONE
                );
                $server = new Server(null,$params);
                $server->setClass(new NewService());
                $resp = $server->handle();

                $response->setContent($resp);
                $response->setStatusCode(200);
                $response->header('Content-Type','text/xml; charset=utf-8');

                return $response;
            }
        }catch (\Exception $exception){
            throw new \SoapFault('500',$exception->getMessage());
        }
    }
    public static function currentUrlRoot(){
        $url = url(app()->request->server()['REQUEST_URI']);
        $pos = strpos($url, '?');
        return $pos ? substr($url, 0, $pos) : $url;
    }


}