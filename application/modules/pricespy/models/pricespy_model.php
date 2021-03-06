<?php

class Pricespy_model extends CI_Model
{

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get module settings
     */
    public function getSettings() {
        $settings = $this->db
            ->select('settings')
            ->where('identif', 'pricespy')
            ->get('components')
            ->row_array();
        $settings = unserialize($settings[settings]);
        return $settings;
    }

    /**
     * Deleting user spys products from list
     * @param int $ids
     */
    public function delSpysbyIds($ids) {
        $this->db->where_in('productId', $ids);
        return $this->db->delete('mod_price_spy');
    }

    /**
     * Deleting user spys products from list
     * @param string $hash
     */
    public function delSpyByHash($hash) {
        return $this->db->delete('mod_price_spy', ['hash' => $hash]);
    }

    /**
     *
     * @param integer $varId
     * @return int
     */
    public function getProductById($varId) {
        return $this->db
            ->where('id', $varId)
            ->get('shop_product_variants')
            ->row();
    }

    /**
     * Insert new spy for user
     * @param int $id
     * @param int $varId
     * @param double $productPrice
     */
    public function setSpy($id, $varId, $productPrice) {
        return $this->db
            ->set('userId', $this->dx_auth->get_user_id())
            ->set('productId', $id)
            ->set('productVariantId', $varId)
            ->set('productPrice', $productPrice)
            ->set('oldProductPrice', $productPrice)
            ->set('hash', random_string('unique', 15))
            ->insert('mod_price_spy');
    }

}