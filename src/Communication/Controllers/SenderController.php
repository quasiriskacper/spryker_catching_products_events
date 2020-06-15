<?php

namespace  KacperSenderPlugin\Communication\Controllers;

use KacperSenderPlugin\Dependency\KacperSenderInterference;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use KacperSenderPlugin\Communication\KacperSenderCommunicationFactory;
use KacperSenderPlugin\Communication\Controllers\MixedController;


class SenderController extends AbstractController { 

    public function getDataFromApi(array $params , $url_to_api) {
        return $this->sendDataToApi($params, $url_to_api);
    }
    
    public function sendDataToApi(array $params, $url_to_api) { 
        $mixed = new MixedController();
        // $id = $mixed->getCorrectAbstractConcreteProductIds($params)['id'];
        $id = (isset($params['id'])) ? $params['id'] : null;
        $type = (isset($params['type'])) ? $params['type'] : null;
        
        $type_query = "POST";
        if(isset($params['eventName']) && $params['eventName'] == 'Category.after.delete') {
            $type_query = "DELETE";
        }

        $product['product']['abstract'] = $params['params']['abstract'];
        $product['product']['concrete'] = $params['params']['concrete'];
        $product['product']['categories'] = $params['params']['categories'];

        // send to quasiris api 
        $url = $url_to_api.'/'.$type.'/'.$id;
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request($type_query, $url, [
                // 'body' => json_encode($params),
                'form_params' => $product['product'],
                'headers' => [
                    'Content-Type' => 'application/json',
                    'accept'     => '*/*'
                ]
            ]);
            $stream = $response->getBody();
        } catch(ServerException $e) {
            $this->sendToMyApi($url_to_api, [
                'status' => 'Error',
                'name' => 'After send to quasiris',
                'error_data' => json_encode($e)
            ]);
        }

        //send to my api
        $data_to_send = [
            'status' => 'SUCCESS',
            'params' => $params,
            'eventName' => $params['eventName'],
            // 'product' =>  $params['findProductAbstractById'],
            // 'quasiris' => $stream->getContents(), 
        ];
        
        if($type === 'categories') { $data_to_send['category_id'] = $id; } else { $data_to_send['product_id'] = $id; }
        try {
            $this->sendToMyApi($url_to_api , $data_to_send);
        } catch(ServerException $e) {
            $this->sendToMyApi($url_to_api, [
                'status' => 'Error',
                'error_data' => json_encode($e)
            ]);
        }
        

        // return $stream->getContents();
    }

    private function sendToMyApi($url, $arr) {
        $client = new \GuzzleHttp\Client();
        $r = $client->request( 'POST',$url, [
            'body' => json_encode($arr),
        ]);
        return $r->getBody();
    }
}