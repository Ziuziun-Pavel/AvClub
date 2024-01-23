<?php
class ControllerExtensionModuleFreeLoadind extends Controller {
	public function index() {
		
		$config_theme = $this->config->get('config_theme');
		
		$theme = explode('_', $config_theme);
		
		if (file_exists(DIR_TEMPLATE . $theme[1] . '/stylesheet/free-loadind.css')) {
			$this->document->addStyle('catalog/view/theme/' . $theme[1] . '/stylesheet/free-loadind.css');
		} else {
			$this->document->addStyle('catalog/view/theme/default/stylesheet/free-loadind.css');
		}

		$this->load->language('extension/module/free_loadind');
		
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['products'] = array();
		
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		
		if( $this->config->get('free_loadind_rand') > 0 ) {
			
			$this->load->model('extension/module/free_loadind');
			
			$total_product = $this->model_catalog_product->getTotalProducts();
			$new_total     = $total_product - $this->config->get('free_loadind_limit') - 2;
			
			$filter = array(
			    'start' => rand(0, $new_total),
			    'limit' => $this->config->get('free_loadind_limit')
		    );
			
			$results = $this->model_extension_module_free_loadind->getRandProducts($filter);
			
		} else {
			
			if( $this->config->get('free_loadind_view') == 1 ) {
			    $orderby = 'ASC';
		    } else {
			    $orderby = 'DESC';
		    }
			
			$filter_data = array(
			    'sort'  => 'p.date_added',
			    'order' => $orderby,
			    'start' => 0,
			    'limit' => $this->config->get('free_loadind_limit')
		    );
			
			$results = $this->model_catalog_product->getProducts($filter_data);
		}
		
		
		$data['start_from']      = $this->config->get('free_loadind_limit');
		$data['start_from_load'] = $this->config->get('free_loadind_limitload');
		
		
		switch( $this->config->get('free_loadind_line') ) {
			
			case 3 :
			    $data['cols'] = 'col-md-4';
			break;
			case 4 :
			    $data['cols'] = 'col-md-3';
			break;
			default:
			    $data['cols'] = 'col-md-4';
			break;
		}
		
		$data['thumb_class'] = 'thmb-class';
		
		if( $results ) {
			
			foreach( $results as $result ) {
				
				if( $result['image'] ) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				}
				
				if( $this->customer->isLogged() || !$this->config->get('config_customer_price') ) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if( (float)$result['special'] ) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if( $this->config->get('config_tax') ) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if( $this->config->get('config_review_status') ) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}
			
			//////////////////////////////////////////////////////////////
		    if( $this->request->server['HTTPS'] ) {
		    	$server = $this->config->get('config_ssl');
		    } else {
		        $server = $this->config->get('config_url');
		    }
		
		    $data['loading_gif']  = $server . 'catalog/view/theme/' . $theme[1] . '/image/free-load.gif';
			$data['loading_more'] = $this->language->get('loading_more');
			$data['error_views']  = $this->language->get('error_views');
		    //////////////////////////////////////////////////////////////

			return $this->load->view('extension/module/freeloadind', $data);
		}
	}
	
	
	public function loading_more($start) {

		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['products'] = array();
		
		$data['text_tax'] = $this->language->get('text_tax');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		
		if( $this->config->get('free_loadind_rand') > 0 ) {
			
			$this->load->model('extension/module/free_loadind');
			
			$total_product = $this->model_catalog_product->getTotalProducts();
			$new_total     = $total_product - $this->config->get('free_loadind_limit') - 2;
			
			$filter = array(
			    'start' => rand(0, $new_total),
			    'limit' => $this->config->get('free_loadind_limitload')
		    );
			
			$results = $this->model_extension_module_free_loadind->getRandProducts($filter);
			
		} else {
			
			if( $this->config->get('free_loadind_view') == 1 ) {
			    $orderby = 'ASC';
		    } else {
			    $orderby = 'DESC';
		    }
			
			$filter_data = array(
			    'sort'  => 'p.date_added',
			    'order' => $orderby,
			    'start' => $start,
			    'limit' => $this->config->get('free_loadind_limitload')
		    );
			
			$results = $this->model_catalog_product->getProducts($filter_data);
		}
		
		switch( $this->config->get('free_loadind_line') ) {
			
			case 3 :
			    $data['cols'] = 'col-md-4';
			break;
			case 4 :
			    $data['cols'] = 'col-md-3';
			break;
			default:
			    $data['cols'] = 'col-md-4';
			break;
		}
		
		$data['thumb_class'] = 'thmb-class';
		
		if( $results ) {
			
			foreach( $results as $result ) {
				
				if( $result['image'] ) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get($this->config->get('config_theme') . '_image_related_width'), $this->config->get($this->config->get('config_theme') . '_image_related_height'));
				}
				
				if( $this->customer->isLogged() || !$this->config->get('config_customer_price') ) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if( (float)$result['special'] ) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if( $this->config->get('config_tax') ) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if( $this->config->get('config_review_status') ) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				

				$data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $result['rating'],
					
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}

			return $this->load->view('extension/module/free_loadind_ajax', $data);
		} else {
			return false;
		}
	}
	
	
	public function show_more() {
		$json = array();
		
		if( isset($this->request->post['start']) ) {
			$start = (int)$this->request->post['start'];
		} else {
			$start = $this->config->get('module_free_loadind_limit');
		}

		if( $more = $this->loading_more($start) ) {
			$json['success'] = true;
		    $json['response'] = $more;
		} else {
			$json['success'] = false;
		    $json['response'] = '';
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
		
	}
	
	
}
