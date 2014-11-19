<?php

class Edge_CollectFromStore_Model_Carrier_CollectFromStore
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'collectfromstore';
    protected $_isFixed = true;

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if (!$this->getConfigData('stores')) {
            return false;
        }

        $result = Mage::getModel('shipping/rate_result');

        $stores = explode("\n", $this->getConfigData('stores'));
        foreach ($stores as $store){
            $methodCode = strtolower(preg_replace('/\s*/', '', $store));

            $method = Mage::getModel('shipping/rate_result_method');
            $method->setCarrier('collectfromstore')
                   ->setCarrierTitle($this->getConfigData('title'))
                   ->setMethod($methodCode)
                   ->setMethodTitle($store)
                   ->setPrice($this->getConfigData('price'))
                   ->setCost(0);
           $result->append($method);
        }

        return $result;
    }

    public function getAllowedMethods()
    {
        return array('collectfromstore' => $this->getConfigData('name'));
    }
}