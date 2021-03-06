<?php

use CMSFactory\assetManager;
use CMSFactory\Events;

(defined('BASEPATH')) OR exit('No direct script access allowed');

/**
 * Image CMS
 *
 * Класс слежения за ценой
 * @property pricespy_model $pricespy_model
 */
class Pricespy extends MY_Controller
{

    public $product;

    public $isInSpy;

    public function __construct() {
        parent::__construct();
        $this->load->module('core');
        $this->load->model('pricespy_model');
        $lang = new MY_Lang();
        $lang->load('pricespy');
    }

    /**
     * send email to user
     * @param string $email
     * @param string $name
     * @param string $hash
     */
    private static function sendNotificationByEmail($email, $name, $hash) {
        $CI = &get_instance();
        $CI->load->library('email');

        $CI->email->from('noreplay@' . $CI->input->server('HTTP_HOST'));
        $CI->email->to($email);
        $CI->email->set_mailtype('html');
        $CI->email->subject(lang('Price changing', 'pricespy'));
        $CI->email->message(
            lang('Price on', 'pricespy') . $name . lang('for which you watch on site', 'pricespy') . site_url() . lang('changed', 'pricespy') . ".<br>
                <a href='" . site_url('pricespy') . "' title='" . lang('View watch list', 'pricespy') . "'>" . lang('View watch list', 'pricespy') . "</a><br>
                <a href='" . site_url("pricespy/$hash") . "' title='" . lang('Unsubscribe tracking', 'pricespy') . "'>" . lang('Unsubscribe tracking', 'pricespy') . '</a><br>'
        );
        $CI->email->send();
    }

    public static function adminAutoload() {
        parent::adminAutoload();

        Events::create()->onShopProductUpdate()->setListener('priceUpdate');
        Events::create()->onShopProductDelete()->setListener('priceDelete');
    }

    /**
     *
     */
    public function index() {
        if ($this->dx_auth->is_logged_in()) {
            assetManager::create()
                    ->registerScript('spy');
            $this->renderUserSpys();
        } else {
            $this->core->error_404();
        }
    }

    /**
     * deleting from spy if product deleted
     * @param array $product
     */
    public static function priceDelete($product) {
        if (!$product) {
            return;
        }

        $CI = &get_instance();

        $product = $product['model'];
        $ids = [];
        foreach ($product as $key => $p) {
            $ids[$key] = $p->id;
        }

        $CI->db->where_in('productId', $ids);
        $CI->db->delete('mod_price_spy');
    }

    /**
     * updating price
     * @param array $product
     */
    public static function priceUpdate($product) {
        if (!$product) {
            return;
        }

        $CI = &get_instance();
        $spys = $CI->db
            ->from('mod_price_spy')
            ->join('shop_product_variants', 'mod_price_spy.productVariantId=shop_product_variants.id')
            ->join('users', 'mod_price_spy.userId=users.id')
            ->join('shop_products_i18n', 'shop_products_i18n.id=mod_price_spy.productId')
            ->where('mod_price_spy.productId', $product['productId'])
            ->get()
            ->result();

        foreach ($spys as $spy) {
            if ($spy->price != $spy->productPrice) {

                $CI->db->set('productPrice', $spy->price);
                $CI->db->where('productVariantId', $spy->productVariantId);
                $CI->db->update('mod_price_spy');

                if ($spy->price < $spy->productPrice) {
                    self::sendNotificationByEmail($spy->email, $spy->name, $spy->hash);
                }
            }
        }
    }

    /**
     * set spy for product
     * @param int $id product ID
     * @param int $varId variant ID
     */
    public function spy($id, $varId) {
        $product = $this->pricespy_model->getProductById($varId);

        if ($this->pricespy_model->setSpy($id, $varId, $product->price)) {
            echo json_encode(
                ['answer' => 'sucesfull']
            );
        } else {
            echo json_encode(
                ['answer' => 'error']
            );
        }
    }

    /**
     *
     * @param string $hash
     */
    public function unSpy($hash) {
        if ($this->pricespy_model->delSpyByHash($hash)) {
            echo json_encode(
                [
                        'answer' => 'sucesfull',
                    ]
            );
        } else {
            echo json_encode(
                [
                        'answer' => 'error',
                    ]
            );
        }
    }

    public function init($model) {
        if ($this->dx_auth->is_logged_in()) {
            if (!$model instanceof SProducts) {
                foreach ($model as $key => $m) {
                    $id[$key] = $m->getid();
                    $varId[$key] = $m->firstVariant->getid();
                }
            } else {
                $id = $model->getid();
                $varId = $model->firstVariant->getid();
            }

            $products = $this->db
                ->where_in('productVariantId', $varId)
                ->where('userId', $this->dx_auth->get_user_id())
                ->get('mod_price_spy')
                ->result_array();

            foreach ($products as $p) {
                $this->isInSpy[$p['productVariantId']] = $p;
            }

            assetManager::create()
                    ->registerScript('spy');
        }
    }

    /**
     * render spy buttons
     * @param int $id product ID
     * @param int $varId variant ID
     */
    public function renderButton($id, $varId) {
        if ($this->dx_auth->is_logged_in()) {

            $data = [
                'Id' => $id,
                'varId' => $varId,
            ];

            if ($this->isInSpy[$varId] == '') {
                assetManager::create()
                        ->setData('data', $data)
                        ->setData('value', lang('Notify about price cut', 'pricespy'))
                        ->setData('class', 'btn')
                        ->render('button', true);
            } else {
                assetManager::create()
                        ->setData('data', $data)
                        ->setData('value', lang('Already in tracking', 'pricespy'))
                        ->setData('class', 'btn inSpy')
                        ->render('button', true);
            }
        }
    }

    /**
     * render spys for user
     */
    private function renderUserSpys() {
        $products = $this->db
            ->where('userId', $this->dx_auth->get_user_id())
            ->join('shop_product_variants', 'shop_product_variants.id=mod_price_spy.productVariantId')
            ->join('shop_products_i18n', 'shop_products_i18n.id=mod_price_spy.productId')
            ->join('shop_products', 'shop_products.id=mod_price_spy.productId')
            ->get('mod_price_spy')
            ->result_array();

        assetManager::create()
                ->setData('products', $products)
                ->render('spys');
    }

    public function _install() {
        $this->load->dbforge();
        ($this->dx_auth->is_admin()) OR exit;
        $fields = [
            'id' => [
                'type' => 'INT',
                'auto_increment' => TRUE],
            'userId' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE],
            'productId' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE],
            'productVariantId' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE],
            'productPrice' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE],
            'oldProductPrice' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE],
            'hash' => [
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE]
        ];

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('mod_price_spy');

        $this->db->where('name', 'pricespy');
        $this->db->update(
            'components',
            [
            'enabled' => 1,
            'autoload' => 1]
        );
    }

    public function _deinstall() {
        $this->load->dbforge();
        ($this->dx_auth->is_admin()) OR exit;
        $this->dbforge->drop_table('mod_price_spy');
    }

}

/* End of file pricespy.php */