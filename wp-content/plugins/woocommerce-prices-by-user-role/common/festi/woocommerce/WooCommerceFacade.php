<?php

if (!interface_exists("IWooCommerce")) {
    require_once dirname(__FILE__).'/IWooCommerce.php';
}

class WooCommerceFacade implements IWooCommerce
{
    private static $_instance = null;

    public static function &getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        
        return self::$_instance;
    } // end &getInstance
    
    public function __construct()
    {
         if (isset(self::$_instance)) {
            $message = 'Instance already defined ';
            $message .= 'use WooCommerceFacade::getInstance';
            throw new Exception($message);
         }
    } // end __construct
    
    public function getAttributes($search = array())
    {
        $db = FestiObject::getDatabaseInstance();
        
        $sql = "SELECT 
                    *
                FROM 
                    ".$db->getPrefix()."woocommerce_attribute_taxonomies";
        
        return $db->select($sql, $search);
    } // end getAttributes
    
    public function addAttribute($values)
    {
        $db = FestiObject::getDatabaseInstance();
        
        $tableName = $db->getPrefix()."woocommerce_attribute_taxonomies";
    
        $id = $db->insert($tableName, $values);
        
        delete_transient('wc_attribute_taxonomies');
        
        return $id;
    } // end getAttributes
    
   /* public function addAttributeValues($attributeIdent, $attributesValues)
    {
        if (!is_array($attributesValues)) {
            $attributesValues = array($attributesValues);
        }
        
        $taxonomy  = wc_attribute_taxonomy_name($attributeIdent);
        
        $termsValues = array();
        foreach ($attributesValues as $value) {
            $this->_addAttributeValue($taxonomy, $value);
        }
        
        delete_transient('wc_attribute_taxonomies');
        
        return true;
    } // end addAttributeValues
    
    private function _addAttributeValue($taxonomy, $value)
    {
        $db = FestiObject::getDatabaseInstance();
        
        $values = array(
            'name'       => $value,
            'slug'       => $this->getAttributeIdent($value),
            'term_group' => 0
        );
        
        $tableName = $db->getPrefix()."terms";
        $idTerm = $db->insert($tableName, $values);
        
        //
        $values = array(
            'term_id'     => $idTerm,
            'taxonomy'    => $taxonomy,
            'description' => '',
            'parent'      => 0,
            'count'       => 0
        );
        
        $tableName = $db->getPrefix()."term_taxonomy";
        $db->insert($tableName, $values);
        
        return true; 
    } // end _addAttributeValue*/
    
    public function createAtributtesHelper()
    {
        if (!class_exists('WooCommerceAtributtesHelper')) {
            require_once dirname(__FILE__).'/WooCommerceAtributtesHelper.php';
        }
        
        return new WooCommerceAtributtesHelper();
    } // end createAtributtesHelper
    
    
    public function getNumberOfDecimals()
    {
        return get_option('woocommerce_price_num_decimals');
    } // end getNumberOfDecimals
    
    public function getWooCommerceInstance()
    {
        if (!function_exists("WC")) {
            throw new Exception("Not Found WooCommerce Instance", 1);
        }
        
        return WC();
    } // end getWooComerceInstance
    
    public static function getCurrencies()
    {
        return get_woocommerce_currencies();
    }
    
    public static function getCurrencySymbol($code) 
    {
        return get_woocommerce_currency_symbol($code);
    }
    
    public static function getBaseCurrencyCode()
    {
        return get_woocommerce_currency();
    }

    public static function getDefaultCurrencyCode()
    {
        return get_option('woocommerce_currency');
    }
    
    public static function displayMetaTextInputField($args)
    {
        woocommerce_wp_text_input($args);
    }
    
    public static function displayHiddenMetaTextInputField($args)
    {
        woocommerce_wp_hidden_input($args);
    }
    
    public function getProductAttributeValues($idProduct, $attrName)
    {
        $terms = wp_get_object_terms($idProduct, $attrName);
        
        if (!$terms || $terms instanceof WP_Error) {
            return array();
        }
        
        $result = array();
        foreach ($terms as $term) {
            $result[] = $term->name;
        }
        
        return $result;
    } // end getProductAttributeValues
    
    public function updateProductAttributeValues($idProduct, $attrName, $values)
    {
        wp_set_object_terms($idProduct, $values, $attrName);
    } // end updateProductAttributeValues
    
    public function setProductTypeToVariable($idProduct)
    {
        wp_set_object_terms($idProduct, 'variable', 'product_type', false);
    }
    
    public function getAttributeIdent($key)
    {
        return str_replace(" ", "_", strtolower($key));
    }
    
    public function getTaxonomyName($name)
    {
        return wc_sanitize_taxonomy_name($name);
    } // end getTaxonomyName
    
    public function updateProductAttributes($idProduct, $attributes)
    {
        update_post_meta($idProduct, '_product_attributes', $attributes);
    } // end updateProductAttributes
    
    public function isProductPurchasableAndInStock($product)
    {
        return $product->is_purchasable() && $product->is_in_stock();
    } // end isProductPurchasableAndInStock
    
    /**
     * Returns values object for woocommerce product.
     *
     * @param string $sku
     * @return WooCommerceProductValuesObject
     */
    public function loadProductValuesObjectBySKU($sku)
    {
        $existingPostQuery = array(
            'numberposts' => 1,
            'meta_key'    => '_sku',
            'post_type'   => 'product',
            'meta_query'  => array(
                array(
                    'key'     =>'_sku',
                    'value'   => $sku,
                    'compare' => '='
                )
            )
        );
    
        $posts = get_posts($existingPostQuery);
        if (!$posts) {
            return false;
        }
        
        return new WooCommerceProductValuesObject($posts[0]);
    } // end loadProductValuesObjectBySKU
    
    public static function getProductByID($id)
    {
        $factory = new WC_Product_Factory();
        
        $product = $factory->get_product($id);
        print_r($product);
        die("dasdasd77----");
    } // end getProductByID
    
    public function getProductsIDsForRangeWidgetFilter()
    {
        $priceKey = WooCommerceProductValuesObject::PRICE_KEY;

        $postIDsQuery = array(
            'numberposts'         => -1,
            'post_meta'           => $priceKey,
            'post_type'           => array('product', 'product_variation'),
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'fields'              => 'ids',
            'meta_query'          => array(
                array(
                    'key'     => '_visibility',
                    'value'   => array('catalog', 'visible'),
                    'compare' => 'IN'
                )
            ),
        );
        
        $queryObject = get_queried_object();
        
        if ($this->_hasCategoryByQueryObject($queryObject)) {
            $postIDsQuery['product_cat'] = $queryObject->slug;
        }
        
        $productsIDs = get_posts($postIDsQuery);
        
        $postParentIDsQuery = array(
            'numberposts' => -1,
            'post_meta'   => $priceKey,
            'post_type'   => array('product', 'product_variation'),
            'post_status' => 'publish',
            'post_parent__in' => $productsIDs,
            'fields' => 'ids', 
        );
        
        $parentProductsIDs = get_posts($postParentIDsQuery);
        
        $productsIDs = array_merge($productsIDs, $parentProductsIDs);
        return $productsIDs;
    }

    private function _hasCategoryByQueryObject($queryObject)
    {
        return !empty($queryObject->term_id);
    }
    
    public function getProductsByIDsForWidgetFilter($productIDs)
    {
        $products = array();
        
        if ($productIDs) {
                 
             $postQuery = array(
                'numberposts' => -1,    
                'post_type'   => array('product', 'product_variation'),
                'post_status' => 'publish',
                'include' => $productIDs,
            );
    
            $products = get_posts($postQuery);
        }
        return $products;
    }
    
    public function getProductsForWidgetFilter()
    {
        $priceKey = WooCommerceProductValuesObject::PRICE_KEY;

        $postQuery = array(
            'numberposts' => -1,
            'meta_key'    => $priceKey,
            'post_type'   => array('product', 'product_variation'),
            'post_status' => 'publish',
        );

        $products = get_posts($postQuery);

        return $products;                    
    }

    public function doResetAllProductsStatusToPrivate()
    {
        $params = array(
            'post_type' => 'product',
            'post_status' => 'private'
        );

        $privateProducts = WordpressFacade::createQueryInstance($params);

        if ($privateProducts->have_posts()) {
            $this->_doMakeAllPrivateProductsPublic($privateProducts);
            return true;
        }

        return false;
    } // end doResetAllProductsStatusToPrivate

    private function _doMakeAllPrivateProductsPublic($products)
    {
        while ($products->have_posts()) {
            $products->the_post();
            $productStatus['post_status'] = 'publish';
            $productStatus['ID'] = get_the_ID();

            wp_update_post($productStatus);
        }

        return true;
    } // end _doMakeAllPrivateProductsPublic
}
