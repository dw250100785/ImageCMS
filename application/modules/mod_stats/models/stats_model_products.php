<?php

/**
 * Description of ProductsBase
 *
 * @author kolia
 */
class Stats_model_products extends CI_Model {

    protected $locale;
    protected $brands;

    public function __construct() {
        parent::__construct();
        $this->locale = \MY_Controller::getCurrentLocale();
    }


    /**
     * Getting data for selecting brands
     * @return array each brand name and id
     */
    public function getAllBrands() {
        $result = $this->db
                ->select('shop_brands.id,name')
                ->from('shop_brands')
                ->join('shop_brands_i18n', 'shop_brands_i18n.id = shop_brands.id')
                ->where('locale', $this->locale)
                ->get();
        $brandsList = array();
        foreach ($result->result_array() as $row) {
            $brandsList[$row['id']] = $row['name'];
        }
        return $brandsList;
    }

    /**
     * Returns counts products in each brand
     * @param array $brands (optional) brandsIds
     * @param boolean $uniqueProducts (optional, default FALSE) if TRUE, then method 
     * will return count of unique products. FALSE will give all.
     * @return array (brandId, brandName, productsCount)
     */
    public function brands($brandIds = NULL, $uniqueProducts = FALSE) {
        // if brand ids specified, then leave only them
        $brands = $this->getAllBrands();
        if (is_array($brandIds)) {
            foreach ($brands as $id => $name) {
                if (!key_exists($id, $brandIds))
                    unset($brands[$id]);
            }
        }

        // getting each brand products count
        $brandsInfo = array();
        foreach ($brands as $id => $name) {
            $result = $this->db
                    ->select('stock')
                    ->from('shop_product_variants')
                    ->join('shop_products', 'shop_products.id = shop_product_variants.product_id')
                    ->where('brand_id', $id)
                    ->get();

            $countUnique = 0;
            $countAll = 0;
            foreach ($result->result_array() as $row) {
                $countAll += $row['stock'];
                $countUnique++;
            }

            $brandsInfo[] = array(
                'id' => $id,
                'name' => $name,
                'count' => $uniqueProducts === FALSE ? $countAll : $countUnique,
            );
        }
        return $brandsInfo;
    }

    /**
     * Returns id,parent_id,name and full_path_ids of categories
     * @return array 
     */
    public function getAllCategories() {
        // getting categories info from db
        $result = $this->db
                ->select('shop_category.id,parent_id,name,full_path_ids')
                ->from('shop_category')
                ->join('shop_category_i18n', 'shop_category.id = shop_category_i18n.id')
                ->where('locale', $this->locale)
                ->get();
        $categoriesRelInfo = array();
        $categories = array();
        foreach ($result->result_array() as $row) {
            $path = array();
            $path_ = unserialize($row['full_path_ids']);
            if (is_array($path_)) {
                $path = $path_;
            }
            $categories[] = array(
                'id' => $row['id'],
                'parent_id' => $row['parent_id'],
                'name' => $row['name'],
            );
        }

        return $categories;

    }

    /**
     * Returns count of products in category
     * @param int $categoryId
     * @param boolean $uniqueProducts (optional, default FALSE) if TRUE, then method 
     * will return count of unique products. FALSE will give all.
     * @return int 
     */
    public function getCategoryCount($categoryId, $uniqueProducts = FALSE) {
        $result = $this->db
                ->select('stock')
                ->from('shop_product_variants')
                ->join('shop_products', 'shop_products.id = shop_product_variants.product_id')
                ->where('category_id', $categoryId)
                ->get();

        if ($result === FALSE)
            return 0;

        $uniqueCount = 0;
        $categoryCount = 0;
        foreach ($result->result_array() as $row) {
            $categoryCount += $row['stock'];
            $uniqueCount++;
        }
        return $uniqueProducts === FALSE ? $categoryCount : $uniqueCount;
    }

    /**
     * Returns counts products in each category
     * @param array $categoryIds 
     * @return array (categoryId, categoryName, productsCount)
     */
    public function categories($categoryIds = array()) {
        if (!is_array($categoryIds)) {
            return FALSE;
        }

        $categories = $this->getAllCategories();

        // creating array of id and parent_id of category
        $categoriresRelations = array();
        foreach ($categories as $category) {
            $categoriresRelations[$category['id']] = $category['parent_id'];
        }

        // getting all root categories if $categoryIds in not specified
        if (count($categoryIds) == 0) { // count all categories
            foreach ($categoriresRelations as $id => $pid) {
                if ($pid == 0) {
                    $categoryIds[] = $id;
                }
            }
        }

        // getting counts of category (including subcategories)
        $categoriesCount = array();
        foreach ($categoryIds as $id => $pid) {
            $productsCount = 0;
            $hasSubcategories = TRUE;
            if (in_array($id, $categoryIds)) { // has childs
              
            } else { // last subcategory
                
            }
            $categoriesCount[$id] = 0;

            echo "<pre>";
            print_r($categoriresRelations);
            echo "</pre>";
            exit;
        }
    }

    protected function getProductsCount($categoryId, $currentCount) {
        return ($this->getCategoryCount($categoryId) + $currentCount);
    }

}

?>