<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Image CMS
 *
 * core.php
 */

class Core extends Controller {

	public $langs = array(); // Langs array
	public $def_lang = array(); // Default language array
	public $page_content = array(); // Page data
	public $cat_content = array(); // Category data
	public $settings = array(); // Site settings
	public $modules = array(); // Modules array
	public $action = '';
	public $by_pages = FALSE;
	public $cat_page = 0;
	public $tpl_data = array();
	public $core_data = array();

	public function __construct()
	{
        parent::Controller();
        //$this->output->enable_profiler(TRUE);
        $this->_load_languages();
	}

    public function index()
    {
        $page_found = FALSE;
        $without_cat = FALSE;
        $SLASH = '';
        $mod_segment = 1;
        $data_type = '';
        $com_links = array();

        $cat_path = substr($this->uri->uri_string(), 1); 

        ($hook = get_hook('core_init')) ? eval($hook) : NULL;

        // Load settings
        if (($this->settings = $this->cache->fetch('main_site_settings')) === FALSE)
        {
            $this->settings = $this->cms_base->get_settings();
            $this->cache->store('main_site_settings', $this->settings);
        }

        ($hook = get_hook('core_settings_loaded')) ? eval($hook) : NULL;

        // Show offline message
        if ($this->settings['site_offline'] == 'yes') 
        {
            ($hook = get_hook('core_goes_offline')) ? eval($hook) : NULL;

            show_error('Site is offline.');
        }

        // Set site main template
        $this->config->set_item('template', $this->settings['site_template']);

        // Load Template library
        ($hook = get_hook('core_load_template_engine')) ? eval($hook) : NULL;

        $this->load->library('template');

        $last_element = key($this->uri->uri_to_assoc(0));

        if (defined('ICMS_INIT') AND ICMS_INIT === TRUE)
        {
            $data_type = 'bridge';
        }

        // DETECT LANGUAGE
        if ($this->uri->total_segments() >= 1)
        {
            if(array_key_exists($this->uri->segment(1),$this->langs))
            {
                ($hook = get_hook('core_set_lang')) ? eval($hook) : NULL;

                $cat_path = substr($cat_path, strlen($this->uri->segment(1)));

                // Delete first slash
                if (substr($cat_path, 0, 1) == '/') $cat_path = substr($cat_path,1);

                $uri_lang = $this->uri->segment(1);

                //$this->template->add_array($this->lang->load('main', $this->langs[$uri_lang]['folder'],TRUE));
                $this->config->set_item('language', $this->langs[$uri_lang]['folder']);
                $this->lang->load('main', $this->langs[$uri_lang]['folder']);

                $this->config->set_item('cur_lang', $this->langs[$uri_lang]['id']);

                // Set language template
                // $this->template->template_dir = TEMPLATES_PATH.$this->langs[$uri_lang]['template'].'/';

                $this->template->set_config_value('tpl_path', TEMPLATES_PATH.$this->langs[$uri_lang]['template'].'/');

               ($hook = get_hook('core_changed_tpl_path')) ? eval($hook) : NULL; 

                $this->load_functions_file($this->langs[$uri_lang]['template']);

                // Add language identificator to base_url
                $this->config->set_item('base_url',base_url().$uri_lang);

                $mod_segment = 2;
            }
            else
            {
                $this->use_def_language();
            }
        }else
        {
                $this->use_def_language();
        }
        // End language detect

        // Load categories
        ($hook = get_hook('core_load_lib_category')) ? eval($hook) : NULL;

        $this->load->library('lib_category');
        $categories = $this->lib_category->build();

        $this->tpl_data['categories'] = $categories;
        $cats_unsorted = $this->lib_category->unsorted();

        // Load modules
        $query = $this->cms_base->get_modules();
        if ( $query->num_rows() > 0 )
        {
            $this->modules = $query->result_array();

            ($hook = get_hook('core_load_modules_urls')) ? eval($hook) : NULL; 

            foreach($this->modules as $k)
            {
                $com_links[ $k['name'] ] = '/'.$k['identif'];
            }

            $this->tpl_data['modules'] = $com_links; 
        }

        // Load auth library
        ($hook = get_hook('core_load_auth_lib')) ? eval($hook) : NULL;

        $this->load->library('DX_Auth');
  
        // Are we on main page?
        if ($cat_path == FALSE AND $data_type != 'bridge')
        {
             $data_type = 'main';

            ($hook = get_hook('core_set_type_main')) ? eval($hook) : NULL;
        }

        if (is_numeric($last_element) AND is_int($last_element))
        {
            if(substr($cat_path, -1) == '/') $cat_path = substr($cat_path, 0 , -1);

            // Delete page number from path
            $cat_path = substr($cat_path, 0, strripos($cat_path, '/'));
            $this->by_pages = TRUE;
            $this->cat_page = $last_element;

            ($hook = get_hook('core_enable_pagination')) ? eval($hook) : NULL;
        }

        if (substr($cat_path, -1) != '/') $SLASH = '/';

        foreach ($cats_unsorted as $cat)
        {
            if($cat['path_url'] == $cat_path.$SLASH)
            {
                $this->cat_content = $cat;
                $data_type = 'category';

                ($hook = get_hook('core_set_type_category')) ? eval($hook) : NULL;

                break;
            }
        }

        if ($data_type != 'main' AND $data_type != 'category' AND $data_type != 'bridge')
        {
                $cat_path_url = substr($cat_path, 0, strripos($cat_path, '/') + 1);
                                
                ($hook = get_hook('core_try_find_page')) ? eval($hook) : NULL;

                // Select page permissions and page data
                $this->db->select('content.*');
                $this->db->select('CONCAT_WS("", content.cat_url, content.url) as full_url');
                $this->db->select('content_permissions.data as roles', FALSE);
                $this->db->where('url', $last_element);
                $this->db->where('post_status', 'publish');
                $this->db->where('publish_date <=', time());
                $this->db->where('lang', $this->config->item('cur_lang'));
                $this->db->join('content_permissions','content_permissions.page_id = content.id', 'left');

                // Search page without category
                if ($cat_path == $last_element)
                {
                    $this->db->where('content.category', 0);
                    $without_cat = TRUE;
                }else{
                    $this->db->where('content.cat_url',$cat_path_url);
                }

                ($hook = get_hook('core_get_page_query')) ? eval($hook) : NULL;
                $query = $this->db->get('content',1);

                if ($query->num_rows() > 0)
                {
                    ($hook = get_hook('core_page_found')) ? eval($hook) : NULL;

                    if(substr($cat_path, -1) == '/') $cat_path = substr($cat_path, 0 , -1);
                    $cat_path = substr($cat_path, 0, strripos($cat_path, '/'));

                    $page_info = $query->row_array();
                    $page_info['roles'] = unserialize($page_info['roles']);

                    if($without_cat == FALSE)
                    {
                        // load page and category
                        foreach($cats_unsorted as $cat)
                        {
                            if( ($cat['path_url'] == $cat_path.$SLASH) AND ($cat['id'] == $page_info['category']) )
                            {
                                $page_found = TRUE;
                                $data_type = 'page';
                                $this->page_content = $page_info;
                                $this->cat_content = $cat;

                                ($hook = get_hook('core_set_page_data')) ? eval($hook) : NULL;

                                break;
                            }
                        }

                        if ($page_found == FALSE)
                        {
                            ($hook = get_hook('core_set_type_404')) ? eval($hook) : NULL;

                            // show 404 page
                            $data_type = '404';
                        }

                    }else{
                        // display page without category
                        $data_type = 'page';
                        $this->page_content = $page_info;

                        ($hook = get_hook('core_set_type_nocat')) ? eval($hook) : NULL;
                    }
                }else{
                    $data_type = '404';
                    ($hook = get_hook('core_type_404')) ? eval($hook) : NULL;
                }
        }

       ($hook = get_hook('core_assign_data_type')) ? eval($hook) : NULL; 

        $this->core_data = array(
                        'data_type' => $data_type, // Possible values: page/category/main/404
        );

        // Assign userdata
        if ($this->dx_auth->is_logged_in() == TRUE)
        {
            ($hook = get_hook('core_user_is_logged_in')) ? eval($hook) : NULL;

            $this->tpl_data['is_logged_in'] = TRUE;
            $this->tpl_data['username'] = $this->dx_auth->get_username();
        }

        // Assign template variables and load modules
        $this->_process_core_data();        

        // If module than exit from core and load module
        if ( $this->is_module($mod_segment) == TRUE ) return TRUE;
 
        switch ( $this->core_data['data_type'] )
        {
            case 'main': // Main page
                $this->_mainpage();
            break;

            case 'category': // Category
                $this->_display_category($this->cat_content);
            break;

            case 'page': // Page and category
                $this->check_page_access($this->page_content['roles']);
                $this->_display_page_and_cat($this->page_content,$this->cat_content);
            break;

            case '404': // Page not found
                $this->error_404();
            break;

            case 'bridge':
                log_message('debug', 'Bridge initialized.');
            break;

            ($hook = get_hook('core_datatype_switch')) ? eval($hook) : NULL;
        }
    }

    /**
	 * Display main page
	 */
	function _mainpage()
	{
		switch ($this->settings['main_type'])
		{
			// Load main page
            case 'page':
                $main_page_id = $this->settings['main_page_id']; 

                $this->db->where('lang', $this->config->item('cur_lang'));
                $this->db->where('id', $main_page_id);
                $this->db->or_where('lang_alias', $main_page_id);
                $query = $this->db->get('content', 1);

                if ($query->num_rows() > 0)
                {
                    $page = $query->row_array();
                }else{
                    $this->error( lang('main_page_error') );
                }

                // Set page template file
                if ($page['full_tpl'] == NULL)
                {
                    $page_tpl = 'page_full';
                }else{
                    $page_tpl = $page['full_tpl'];
                }

                if ($page['full_text'] == '')
                {
                    $page['full_text'] = $page['prev_text'];
                }

				$this->template->assign('content', $this->template->read($page_tpl, array('page' => $page)));
                
                $title = $page['meta_title'] == NULL ? $page['title'] : $page['meta_title'];
                //$this->set_meta_tags($title, $page['keywords'], $page['description']);

               ($hook = get_hook('core_set_main_page_meta')) ? eval($hook) : NULL; 

                $this->set_meta_tags($this->settings['site_title'], $this->settings['site_keywords'], $this->settings['site_description']);

                ($hook = get_hook('core_show_main_page')) ? eval($hook) : NULL;

				$this->template->show();
			break;

			// Category
			case 'category';
               ($hook = get_hook('core_show_main_cat')) ? eval($hook) : NULL; 

                $m_category = $this->lib_category->get_category( $this->settings['main_page_cat'] );
                $this->_display_category($m_category);
            break;
		}
	}

	/**
	 * Display page
	 */
	function _display_page_and_cat($page = array(), $category = array())
    {
        //$this->load->library('typography');
        ($hook = get_hook('core_disp_page_and_cat')) ? eval($hook) : NULL;


        if ($page['full_text'] == '')
        {
            $page['full_text'] = $page['prev_text'];
        }

        // Set page template file
        if ($page['full_tpl'] == NULL)
        {
            $page_tpl = $category['page_tpl'];
        }
        else
        {
            $page_tpl = $page['full_tpl'];
        }

        $page_tpl == '' ? $page_tpl = 'page_full' : TRUE;

        $this->template->add_array(array(
            'page' => $page,
            'category' => $category,
            ));

        $this->template->assign('content', $this->template->read( $page_tpl ));

		$this->set_meta_tags( $page['meta_title'] == NULL ? $page['title'] : $page['meta_title'] , $page['keywords'], $page['description']);

        $this->db->set('showed', 'showed + 1', FALSE);
        $this->db->where('id', $page['id']);
        $this->db->update('content');

        if (!$category['main_tpl'])
        {
            $this->template->show();
        }
        else
        {
            $this->template->display($category['main_tpl']);
        }
	}

    // Select or count pages in category
    public function _get_category_pages($category = array(), $row_count = 0, $offset = 0, $count = FALSE)
    {
        ($hook = get_hook('core_get_category_pages')) ? eval($hook) : NULL;

        $this->db->where('post_status', 'publish');
        $this->db->where('publish_date <=', time());
        $this->db->where('lang', $this->config->item('cur_lang'));
  
        if (count($category['fetch_pages']) > 0)
        {
            $category['fetch_pages'][] = $category['id'];
            $this->db->where_in('category', $category['fetch_pages']);
        }
        else
        {
            $this->db->where('category', $category['id']);
        }

        $this->db->select('content.*');
        $this->db->select('CONCAT_WS("", content.cat_url, content.url) as full_url', FALSE);
        $this->db->order_by($category['order_by'], $category['sort_order']);

        if ($count === FALSE)
        {
            if ($row_count > 0)
            {
                $query = $this->db->get('content', (int) $row_count, (int) $offset);
            }
            else
            {
                $query = $this->db->get('content');
            }
        }
        else
        {
            // Return total pages for pagination
            ($hook = get_hook('core_return_pages_count')) ? eval($hook) : NULL;

            $this->db->from('content');
            return $this->db->count_all_results();
        }

        ($hook = get_hook('core_return_category_pages')) ? eval($hook) : NULL;

        return $query->result_array();
    }

	/**
	 * Display category
	 */
	function _display_category($category = array())
    {
        ($hook = get_hook('core_disp_category')) ? eval($hook) : NULL;

        $category['fetch_pages'] = unserialize($category['fetch_pages']);

		$content = '';
        $offset = $this->uri->segment($this->uri->total_segments());

        $offset == FALSE ? $offset = 0 : TRUE;
        $row_count = $category['per_page'];
    
        $pages = $this->_get_category_pages($category, $row_count, $offset);

        // Count total pages for pagination
        $category['total_pages'] = $this->_get_category_pages($category, 0, 0, TRUE);
        
        if ($category['total_pages'] > $category['per_page'])
        {
            $this->load->library('Pagination');

            // $config['base_url']    = site_url($category['path_url']);
            $config['base_url']    = '/'.$category['path_url'];
            $config['total_rows']  = $category['total_pages'];
            $config['per_page']    = $category['per_page'];
            $config['uri_segment'] = $this->uri->total_segments();
            $config['first_link']  = lang('first_link');
            $config['last_link']   = lang('last_link');

            $config['cur_tag_open']  = '<span class="active">';
            $config['cur_tag_close'] = '</span>';

            $this->pagination->num_links = 5;

            ($hook = get_hook('core_dispcat_set_pagination')) ? eval($hook) : NULL;

            $this->pagination->initialize($config);
            $this->template->assign('pagination', $this->pagination->create_links());
        }
        // End pagination

        ($hook = get_hook('core_dispcat_set_category_data')) ? eval($hook) : NULL;
        $this->template->assign('category', $category);  

        $cnt = count($pages);

        if ($category['tpl'] == '')
        {
            $cat_tpl = 'category';
        }
        else
        {
            $cat_tpl = $category['tpl'];
        }

        if ($cnt > 0)
        {  
            // Locate category tpl file
            if (! file_exists( $this->template->template_dir. $cat_tpl .'.tpl' ))
            {
                ($hook = get_hook('core_dispcat_tpl_error')) ? eval($hook) : NULL;
                show_error('Can\'t locate category template file.');
            }

            ($hook = get_hook('core_dispcat_read_ptpl')) ? eval($hook) : NULL;

            $content = $this->template->read($cat_tpl, array('pages' => $pages));

        }else{
            ($hook = get_hook('core_dispcat_no_pages')) ? eval($hook) : NULL;
            $content = $this->template->read($cat_tpl, array('no_pages' => lang('no_pages_in_cat')));
        }


        $category['title'] == NULL ? $category['title'] = $category['name'] : TRUE;

        ($hook = get_hook('core_dispcat_set_meta')) ? eval($hook) : NULL;
        $this->set_meta_tags($category['title'], $category['keywords'], $category['description']);

        ($hook = get_hook('core_dispcat_set_content')) ? eval($hook) : NULL;
        $this->template->assign('content', $content);

        ($hook = get_hook('core_dispcat_show_content')) ? eval($hook) : NULL;

        if (!$category['main_tpl'])
        {
            $this->template->show();
        }
        else
        {
            $this->template->display($category['main_tpl']);
        }
	}

    /**
     * Load site languages
     */
    public function _load_languages()
    {
        // Load languages
        ($hook = get_hook('core_load_languages')) ? eval($hook) : NULL; 

        if (($langs = $this->cache->fetch('main_site_langs')) === FALSE)
        {
            $langs = $this->cms_base->get_langs();
            $this->cache->store('main_site_langs', $langs);
        }

        foreach ($langs as $lang)
        {
            $this->langs[$lang['identif']] = array(
                                                'id' => $lang['id'],
                                                'name' => $lang['lang_name'],
                                                'folder' => $lang['folder'],
                                                'template' => $lang['template']
                                                );

            if($lang['default'] == 1) $this->def_lang = array($lang);
        }
    }

	/**
	 * Load and run modules
	 */
	private function load_modules()
    {
        ($hook = get_hook('core_load_modules')) ? eval($hook) : NULL;

		foreach($this->modules as $module)
        {
            if ($module['autoload'] == 1)
            {            
                $mod_name = $module['name'];
                $this->load->module($mod_name);

                if ( method_exists($mod_name, 'autoload') === TRUE )
                {
                    $this->core_data['module'] = $mod_name;

                    ($hook = get_hook('core_load_module_autoload')) ? eval($hook) : NULL;
                    $this->$mod_name->autoload();
                }
            }
		}

        // Check url segments
        $this->_check_url();
	}

    /**
     * Deny access to modules install/deinstall/rules/etc/ methods
     */
	private function _check_url()
    {
        $CI =& get_instance();

        ($hook = get_hook('core_check_url')) ? eval($hook) : NULL;

        $error_text = $this->lang->line('uri_access_deny');

		$not_permitted = array('_install', '_deinstall', '_install_rules', 'autoload', '__construct');

		$url_segs = $CI->uri->segment_array();

        // Deny uri access to all methods like _somename 
        if ( count(explode('/_', $CI->uri->uri_string())) > 1 )
        {
            $this->error($error_text, FALSE);
        }

        if ( count($url_segs) > 0)
        {
		    foreach($url_segs as $segment)
		    {
                if( in_array($segment, $not_permitted) == TRUE )
                {
                    ($hook = get_hook('core_checkurl_access_false')) ? eval($hook) : NULL;
                    $this->error($error_text, FALSE);
                }
            }
        }

		return TRUE;
    }

	private function _process_core_data()
	{
        ($hook = get_hook('core_set_tpl_data')) ? eval($hook) : NULL;
		$this->template->add_array($this->tpl_data);
		$this->load_modules();

		return TRUE;
	}

	/**
	 * htmlspecialchars_decode text
	 *
	 * @return string
	 */
	function _prepare_content($text = '')
	{
		return htmlspecialchars_decode($text);
	}

	/**
     * Page not found
     * Show 404 error
	 */
	function error_404()
	{
        ($hook = get_hook('core_display_error_404')) ? eval($hook) : NULL;

        $this->set_meta_tags(lang('error_page_h'));

		$this->template->assign('error_text', lang('error_page_404'));
		$this->template->show('404');
		//$this->template->show();

        exit;
    }

    /**
     * Display error template end exit
     */ 
    function error($text, $back = TRUE)
    {
       ($hook = get_hook('core_display_errors_tpl')) ? eval($hook) : NULL; 

        $this->template->add_array(array(
            'content' => $this->template->read('error', array('error_text' => $text, 'back_button' => $back))
        ));

        $this->template->show();
        exit;
    }

   /**
	*  Language detection in url segments
	*/
	function segment($n)
	{
		if(array_key_exists($this->uri->segment(1),$this->langs))
		{
			$n++;
			return $this->uri->segment($n);
		}

		return $this->uri->segment($n);
	}

	/**
	 * Run module
	 *
	 * @access private
	 * @return bool
	 */
	private function is_module($n)
    {
        $segment = $this->uri->segment($n);
        $found   = FALSE;

        ($hook = get_hook('core_is_seg_module')) ? eval($hook) : NULL;

        foreach($this->modules as $k)
        {
            if ($k['identif'] === $segment AND $k['enabled'] == 1)
            {
                $found = TRUE;
                $mod_name = $k['identif']; 
            }
        }

        if($found == TRUE)
        {
            //$mod_name = $this->modules[$this->uri->segment($n)];
            $mod_function = $this->uri->segment($n + 1);

            if($mod_function == FALSE) $mod_function = 'index';

            $file = APPPATH.'modules/'.$mod_name.'/'.$mod_function.EXT;

            $this->core_data['module'] = $mod_name;

            if(file_exists($file))
            {
                ($hook = get_hook('core_run_module_by_seg')) ? eval($hook) : NULL;

                // Run module
                $func = $this->uri->segment($n + 2);
                if($func == FALSE) $func = 'index';
                
                $args = $this->grab_variables($n + 3);
                echo modules::run($mod_name.'/'.$mod_function.'/'.$func, $args);
            }else{
                $args = $this->grab_variables($n + 2);
                echo modules::run($mod_name.'/'.$mod_name.'/'.$mod_function, $args);
            }

            return TRUE;
        }
        return FALSE;
	}

	/*
	 * Check user access for page
	 */
	function check_page_access($roles)
    {
        ($hook = get_hook('core_check_page_access')) ? eval($hook) : NULL;

        if ($roles == FALSE OR count($roles) == 0) return TRUE;

        // if (count($roles) == 0) return TRUE;

        $access = FALSE;
		$logged = $this->dx_auth->is_logged_in();
		$my_role = $this->dx_auth->get_role_id();
	
		if($this->dx_auth->is_admin() === TRUE)
		$access = TRUE;

		// Check roles access
		if ($access != TRUE)
		{
			foreach($roles as $role)
			{
				if($role['role_id'] == $my_role)
				    $access = TRUE;

				if($role['role_id'] == 1  AND $logged == TRUE)
				    $access = TRUE;

				if($role['role_id'] == '0')
				    $access = TRUE;
			}
		}

		if($access == FALSE)
		{
            ($hook = get_hook('core_page_access_deny')) ? eval($hook) : NULL;

			$this->dx_auth->deny_access('deny');
			exit;
		}
	}

	/**
	 * Grab uri segments to args array
	 *
	 * @access public
	 * @return array
	 */
	function grab_variables($n)
	{
			$args = array();

			foreach($this->uri->uri_to_assoc($n) as $k => $v)
			{
				if(isset($k)) array_push($args,$k);
				if(isset($v)) array_push($args,$v);
			}

			for ($i = 0, $cnt = count($args); $i < $cnt; $i++)
			{
				if($args[$i] == FALSE) unset($args[$i]);
			}

			return $args;
	}

	/*
	 * Use default language
	 */
	private function use_def_language()
	{
            ($hook = get_hook('core_load_def_lang')) ? eval($hook) : NULL;

            $this->load_functions_file($this->settings['site_template']);
			// Load language variables into template
			//$this->template->add_array($this->lang->load('main',$this->def_lang[0]['folder'],TRUE));

			// Set config item
			$this->config->set_item('language', $this->def_lang[0]['folder']);

            // Load Language
			$this->lang->load('main', $this->def_lang[0]['folder']);

			// Set current language variable
            $this->config->set_item('cur_lang', $this->def_lang[0]['id']);
    }

    private function load_functions_file($tpl_name)
    {
        ($hook = get_hook('core_load_functions_php')) ? eval($hook) : NULL;

        $full_path ='./templates/'.$tpl_name.'/functions.php'; 

        if (file_exists($full_path))
        {
            include($full_path);
        }
    }

	/**
	 * Set meta tags for pages
	 */
	public function set_meta_tags($title = '', $keywords = '', $description = '')
	{
        ($hook = get_hook('core_set_meta_tags')) ? eval($hook) : NULL;

        if ($this->core_data['data_type'] == 'main')
        {
            $this->template->add_array(array(
                'site_title' => $this->settings['site_title'],
                'site_description' => $this->settings['site_description'],
                'site_keywords' => $this->settings['site_keywords']
            ));
        }
        else
        {
            if ($this->core_data['data_type'] == 'page' AND $this->page_content['category'] != 0 AND $this->settings['add_site_name_to_cat'])
            {
                $title .= ' '.$this->settings['delimiter'].' '.$this->cat_content['name']; 
            }

        
            if (is_array($title))
            {
                $n_title = '';
                foreach ($title as $k => $v)
                {
                    $n_title .= $v;
                    
                    if ($k < count($title) -1 )
                    {
                        $n_title .= ' '.$this->settings['delimiter']; 
                    }
                }
                $title = $n_title;
            }

            if($this->settings['add_site_name'] == 1)
            {
                $title .= ' '.$this->settings['delimiter'].' '.$this->settings['site_title'];
            }

            $this->template->add_array(array(
                'site_title' => $title,
                'site_description' => $description,
                'site_keywords' => $keywords
            ));
        }
    }

}

/* End of file core.php */
