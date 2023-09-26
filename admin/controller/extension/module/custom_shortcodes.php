<?php
class ControllerExtensionModuleCustomShortcodes extends Controller {
    
    private $error = array();
    
    public function index() {
        $this->load->language('extension/module/custom_shortcodes');
        
        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('extension/module/custom_shortcodes');         
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_no_results'] = $this->language->get('text_no_results');
        $data['text_confirm'] = $this->language->get('text_confirm');
        
        $data['column_admin_name'] = $this->language->get('column_admin_name');
        $data['column_name'] = $this->language->get('column_name');
        $data['column_type'] = $this->language->get('column_type');        
        $data['column_action'] = $this->language->get('column_action');
        
        $data['button_add'] = $this->language->get('button_add');
        $data['button_edit'] = $this->language->get('button_edit');
        $data['button_delete'] = $this->language->get('button_delete');        
        

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }        
        
        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'], true)
        );
        
        $data['token'] = $this->session->data['token'];
        
        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'admin_name';
        }
        
        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'ASC';
        }
        
        if (isset($this->request->get['page'])) {
            $page = $this->request->get['page'];
        } else {
            $page = 1;
        }
        
        $url = '';
        
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }
        
        $data['add'] = $this->url->link('extension/module/custom_shortcodes/add', 'token=' . $this->session->data['token'] . $url, true);
        $data['delete'] = $this->url->link('extension/module/custom_shortcodes/delete', 'token=' . $this->session->data['token'] . $url, true);
        
        $filter_data = array(
            'sort'            => $sort,
            'order'           => $order,
            'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
            'limit'           => $this->config->get('config_limit_admin')
        );
        
        $data['shortcodes'] = array();        
        $result = $this->model_extension_module_custom_shortcodes->getShortcodes($filter_data); 
        
        foreach ($result as $shortcode) {
            $data['shortcodes'][] = array(
                'id' => $shortcode['id'],
                'admin_name' => $shortcode['admin_name'],
                'name' => $shortcode['name'],
                'type' => $shortcode['type'],
                'edit' => $this->url->link('extension/module/custom_shortcodes/edit', 'token=' . $this->session->data['token'] . '&shortcode_id=' . $shortcode['id'] . $url, true)
            );
        }
        
        $url = '';
        if ($order == 'ASC') {
            $url .= '&order=DESC';
        } else {
            $url .= '&order=ASC';
        }
        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }

        $data['sort_admin_name'] = $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . '&sort=admin_name' . $url, true);        
        $data['sort_name'] = $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
        $data['sort_type'] = $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . '&sort=type' . $url, true);
        
        $data['sort'] = $sort;
        $data['order'] = $order;
        
        $shortcodes_total = $this->model_extension_module_custom_shortcodes->getTotalShortcodes();
        
        $pagination = new Pagination();
        $pagination->total = $shortcodes_total;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit_admin');
        $pagination->url = $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

        $data['pagination'] = $pagination->render();        
        $data['results'] = sprintf($this->language->get('text_pagination'), ($shortcodes_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($shortcodes_total - $this->config->get('config_limit_admin'))) ? $shortcodes_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $shortcodes_total, ceil($shortcodes_total / $this->config->get('config_limit_admin')));
        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->response->setOutput($this->load->view('extension/module/custom_shortcodes', $data));
        
    }
    
    public function edit() {
        $this->load->language('extension/module/custom_shortcodes');        
        $this->load->model('extension/module/custom_shortcodes');         
        
        $url = '';
        
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }        
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                
            $this->model_extension_module_custom_shortcodes->updateShortcode($this->request->get['shortcode_id'], $this->request->post['shortcode']);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . $url, true));
        }
        
        if(isset($this->request->get['shortcode_id'])) {
            $data['shortcode'] = $this->model_extension_module_custom_shortcodes->getShortcode($this->request->get['shortcode_id']);
        } else {
            $this->response->redirect($this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . $url, true));
        }
        
        
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['entry_admin_name'] = $this->language->get('entry_admin_name');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_code'] = $this->language->get('entry_code');
        
        $data['help_name'] = $this->language->get('help_name');
        
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        $data['text_edit'] = $this->language->get('text_edit');
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'], true)
        );
        
        $data['token'] = $this->session->data['token'];
        
        $data['action'] = $this->url->link('extension/module/custom_shortcodes/edit', 'token=' . $this->session->data['token'] . '&shortcode_id=' . $this->request->get['shortcode_id'] . $url, true);
        $data['cancel'] = $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . $url, true);
        

        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        
        switch ($data['shortcode']['type']) {
            case 'php':
                $this->response->setOutput($this->load->view('extension/module/custom_shortcodes_edit_php', $data));
                break;
            case 'js':
                $this->response->setOutput($this->load->view('extension/module/custom_shortcodes_edit_js', $data));
                break;
            default :
                $this->response->setOutput($this->load->view('extension/module/custom_shortcodes_edit_html', $data));
                break;
        }
        
        
    }
    
    
    public function add() {
        $this->load->language('extension/module/custom_shortcodes');        
        $this->load->model('extension/module/custom_shortcodes');         
        
        $url = '';
        
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
            $url .= '&page=' . $this->request->get['page'];
        }        
        
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
                
            $shortcode_id = $this->model_extension_module_custom_shortcodes->addShortcode($this->request->post['shortcode']);

            $this->response->redirect($this->url->link('extension/module/custom_shortcodes/edit', 'token=' . $this->session->data['token'] . '&shortcode_id=' . $shortcode_id . $url, true));
        }
        
        $data['shortcode'] = array(            
            'admin_name' => isset($this->request->post['shortcode']['admin_name']) ? $this->request->post['shortcode']['admin_name'] : '',
            'name' => isset($this->request->post['shortcode']['name']) ? $this->request->post['shortcode']['name'] : '',
            'type' => isset($this->request->post['shortcode']['type']) ? $this->request->post['shortcode']['type'] : '',
        );
        
        
        $this->document->setTitle($this->language->get('heading_title'));
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['entry_admin_name'] = $this->language->get('entry_admin_name');
        $data['entry_name'] = $this->language->get('entry_name');
        $data['entry_type'] = $this->language->get('entry_type');
        $data['entry_code'] = $this->language->get('entry_code');
        
        $data['help_name'] = $this->language->get('help_name');
        
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        
        $data['text_edit'] = $this->language->get('text_add');
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['name'])) {
            $data['error_name'] = $this->error['name'];
        } else {
            $data['error_name'] = array();
        }
        
        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=module', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'], true)
        );
        
        $data['token'] = $this->session->data['token'];
        
        $data['action'] = $this->url->link('extension/module/custom_shortcodes/add', 'token=' . $this->session->data['token'] .$url, true);
        $data['cancel'] = $this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . $url, true);
        

        
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        
        $this->response->setOutput($this->load->view('extension/module/custom_shortcodes_add', $data));
        
        
    }


    public function install() {
        // Register the event triggers
        $this->load->model('extension/event');

        $this->model_extension_event->addEvent('custom_shortcodes', 'catalog/view/*/before', 'event/custom_shortcodes');
    }
    
    public function uninstall() {
        // delete the event triggers
        $this->load->model('extension/event');

        $this->model_extension_event->deleteEvent('custom_shortcodes');
    }
    
    protected function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/featured')) {
                $this->error['warning'] = $this->language->get('error_permission');
        }

        if ((utf8_strlen($this->request->post['shortcode']['name']) < 3) || (utf8_strlen($this->request->post['shortcode']['name']) > 255)) {
                $this->error['name'] = $this->language->get('error_name');
        }

        return !$this->error;
    }
    
    public function delete() {
        $this->load->language('extension/module/custom_shortcodes');

        $this->load->model('extension/module/custom_shortcodes'); 
        
        $url = '';

        if (isset($this->request->get['sort'])) {
                $url .= '&sort=' . $this->request->get['sort'];
        }

        if (isset($this->request->get['order'])) {
                $url .= '&order=' . $this->request->get['order'];
        }

        if (isset($this->request->get['page'])) {
                $url .= '&page=' . $this->request->get['page'];
        }

        if (isset($this->request->post['selected'])) {
                foreach ($this->request->post['selected'] as $shortcode_id) {
                        $this->model_extension_module_custom_shortcodes->deleteShortcode($shortcode_id);
                }

                $this->session->data['success'] = $this->language->get('text_success');                

                $this->response->redirect($this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . $url, true));
        }

        $this->response->redirect($this->url->link('extension/module/custom_shortcodes', 'token=' . $this->session->data['token'] . $url, true));
    }
    
}