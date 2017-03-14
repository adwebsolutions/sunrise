<?php
if (!class_exists("WpmlFacade")) {
    require_once PRICE_BY_ROLE_PLUGIN_DIR.'/common/festi/wpml/WpmlFacade.php';
}
class FestiWooCommerceVariationProduct extends AbstractFestiWooCommerceProduct
{
    public function removeAddToCartButton()
    {
    } // end removeAddToCartButton
    
    public function getProductId($product)
    {
        $idProduct = $product->variation_id;
        
        $wpmlFacade = WpmlFacade::getInstance();
        
        if (!$wpmlFacade->isInstalled()) {
            return $idProduct;
        }
        
        $idProductVariation = $wpmlFacade->getWooCommerceProductIDByPostID(
            $idProduct
        );
        
        if (!$idProductVariation) {
            return $idProduct;
        }
        
        $product = new WC_Product_Variation($idProductVariation);
        
        return $product->variation_id;
    } // end getProductId
    
    public function isAvaliableToDispalySavings($product)
    {
        return true;
    } // end isAvaliableToDispalySavings
}
