<?php

namespace wishlist\classes;

/**
 * Image CMS
 * Module Wishlist
 * @property \Wishlist_model $wishlist_model
 * @property \DX_Auth $dx_auth
 * @property \CI_URI $uri
 * @property \CI_DB_active_record $db
 * @property \CI_Input $input
 */
class BaseWishlist extends \wishlist\classes\ParentWishlist {

    public function __construct() {
        parent::__construct();
    }

    private function checkPerm() {
        $permAllow = TRUE;
        if (!$this->dx_auth->is_logged_in())
            $permAllow = FALSE;

        return $permAllow;
    }

    public function all() {
        $parent = parent::all();
        if ($parent) {
            return $this->dataModel;
        } else {
            return false;
        }
    }

    public function show($user_id, $list_id) {
        if (parent::show($user_id, $list_id)) {
            return $this->dataModel;
        } else {
            return false;
        }
    }

    public function user($user_id) {
        if (parent::user($user_id)) {
            return $this->dataModel;
        } else {
            return false;
        }
    }

    /**
     *
     * @param type $title
     * @param type $access
     * @param type $description
     * @param type $user_id
     * @param type $user_image
     * @param type $user_birthday
     */
    public function createWL($title, $access, $description, $user_id, $user_image, $user_birthday) {

        $this->db->set('title', $title);
        $this->db->set('access', $access);
        $this->db->set('description', $description);
        $this->db->set('user_id', $user_id);
        $this->db->set('user_image', $user_image);
        $this->db->set('user_birthday', $user_birthday);
        $this->db->insert('mod_wish_list');

        if (true)
            echo json_encode(array(
                'answer' => 'sucesfull',
            ));
        else
            echo json_encode(array(
                'answer' => 'error',
                'errors' => $this->errors,
            ));
    }

    /**
     * Edit WL
     */
//    public function editWL($wish_list_id) {
//        if (!$this->input->post())
//        if (parent::editWL($wish_list_id))
//            return TRUE;
//        else
//            return FALSE;
//    }

    /**
     * delete full WL
     */
    public function deleteWL($wish_list_id) {
        parent::deleteWL($wish_list_id);
        redirect('/wishlist');
    }

//    public function addItem($varId) {
//        if (parent::addItem($varId)) {
//            redirect($this->input->cookie('url'));
//        } else {
//            \CMSFactory\assetManager::create()
//                    ->registerScript('wishlist')
//                    ->setData('errors', $this->errors)
//                    ->render('errors');
//        }
//    }

    public function deleteItem($variant_id, $wish_list_id) {
        parent::deleteItem($variant_id, $wish_list_id);
        redirect('/wishlist');
    }

    public function editItem($id, $varId) {

    }

    public function moveItem($id, $varId) {

    }

    function editWLName($id, $newName) {

    }

    public function getWLbyHash($hash) {

    }

    public function renderWLByHash($hash) {

    }

    /**
     *
     * @param type $id array()
     */
    public function deleteItemByIds($id) {
        if (!$id)
            return;
    }

    public function autoload() {

    }

    public static function adminAutoload() {
        parent::adminAutoload();
    }

    public function _install() {
        parent::_install();
    }

    public function _deinstall() {
        parent::_deinstall();
    }

}