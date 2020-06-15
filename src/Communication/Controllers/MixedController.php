<?php

namespace  KacperSenderPlugin\Communication\Controllers;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;

class MixedController extends AbstractController { 

    public function getCorrectAbstractConcreteProductIds($params, $isListener = false) {
        $type = null;
        $id = [];

        foreach($params as $p) {
            if(isset($p['fk_product_abstract'])) {
                //concrete product
                $type = "products";
                array_push($id, $p['fk_product_abstract']);
                // $id = $name['fk_product_abstract'];
            } elseif(isset($p['id_product_abstract'])) {
                //abstract product
                $type = "products";  
                array_push($id, $p['id_product_abstract']);
            }
        }

        return array(
            'type' => $type,
            'id' => $id
        );
    }

    public function getCategoryTypeAndId($params) {
        $type = null;
        $id = [];
        $category_id = [];
        $cat = (isset($params['CategoryTransfer'][0])) ? $params['CategoryTransfer'] : null;

        //categories
        $type = "categories";  
        foreach($cat as $n) {
            array_push($id, $n['fk_product_abstract']);
            array_push($category_id, $n['fk_category']);
        }  

        return array(
            'type' => $type,
            'id' => $id,
            'category_id' => $category_id
        );
    }

    public function getProductCategories($getIdProductAbstract, $transfer)
    {
        $productCategories = $this->getFactory()->getProductCategoryFacede()->getCategoryTransferCollectionByIdProductAbstract($getIdProductAbstract, $transfer)->toArray();
        return $productCategories;
    }

    public function createArrayToSend($listenerName, $eventName, $abstract, $concrete, $categories, $productId) {
        $data = array(
            'date' => date('d.m.y G:i:s'),
            'listenerName' => $listenerName,
            'eventName' =>  $eventName,
            'abstract' => $abstract,
            'concrete' => $concrete,
            'categories' => $categories,
            'productId' => $productId
        );
        return $data;
    }
}