<?php 

$route = !empty($this->request->get['route']) ? $this->request->get['route'] : '';

$parent = 'journal';
$child = '';

$arr_parent_company = array(
	'company/company',
	'company/company/category',
	'company/info'
);

$arr_parent_expert = array(
	'expert/list',
	'expert/expert'
);

if($route === 'avevent/event' || $route === 'avevent/event/info') {
	$parent = 'event';
}else if($route === 'master/master' || $route === 'master/master/info') {
	$parent = 'master';
}else if(in_array($route, $arr_parent_company)) {
	$parent = 'company';
}else if(in_array($route, $arr_parent_expert)) {
	$parent = 'expert';
}else{
	switch (true) {
		case ($route === 'journal/news' || $route === 'journal/news/info'):
		$child = 'news';
		break;
		case ($route === 'journal/opinion' || $route === 'journal/opinion/info'):
		$child = 'opinion';
		break;
		case ($route === 'journal/case' || $route === 'journal/case/info'):
		$child = 'case';
		break;
		case ($route === 'journal/article' || $route === 'journal/article/info'):
		$child = 'article';
		break;
		case ($route === 'journal/video' || $route === 'journal/video/info'):
		$child = 'video';
		break;
		case ($route === 'journal/special' || $route === 'journal/special/info'):
		$child = 'special';
		break;
		case ($route === 'tag/tag' || $route === 'tag/tag/info'):
		$child = 'tag';
		break;
	}
}

$menu_data = array();

// journal
$menu_data['journal'] = array(
	'title'			=>	'Журнал',
	'route'			=>	'common/home',
	'href'			=>	$this->url->link('common/home'),
	'active'		=>	$parent === 'journal' ? true : false,
	'children'	=> array(
		'news'	=> array(
			'title'		=> 'Новости',
			'route'		=> 'journal/news',
			'href'			=>	$this->url->link('journal/news'),
			'active'	=> $child === 'news' ? true : false
		),
		'opinion'	=> array(
			'title'		=> 'Мнения',
			'route'		=> 'journal/opinion',
			'href'			=>	$this->url->link('journal/opinion'),
			'active'	=> $child === 'opinion' ? true : false
		),
		'case'	=> array(
			'title'		=> 'Кейсы',
			'route'		=> 'journal/case',
			'href'			=>	$this->url->link('journal/case'),
			'active'	=> $child === 'case' ? true : false
		),
		'article'	=> array(
			'title'		=> 'Статьи',
			'route'		=> 'journal/article',
			'href'			=>	$this->url->link('journal/article'),
			'active'	=> $child === 'article' ? true : false
		),
		'video'	=> array(
			'title'		=> 'Видео',
			'route'		=> 'journal/video',
			'href'			=>	$this->url->link('journal/video'),
			'active'	=> $child === 'video' ? true : false
		),
		'special'	=> array(
			'title'		=> 'Спецпроекты',
			'route'		=> 'journal/special',
			'href'			=>	$this->url->link('journal/special'),
			'active'	=> $child === 'special' ? true : false
		),
		'tag'	=> array(
			'title'		=> 'Теги',
			'route'		=> '',
			'href'			=>	'',
			'active'	=> $child === 'tag' ? true : false
		),
	)
);

// master
$menu_data['master'] = array(
	'title'			=>	'Онлайн-события',
	'route'			=>	'master/master',
	'href'			=>	$this->url->link('master/master'),
	'active'		=>	$parent === 'master' ? true : false,
	'children'	=> array(
		'all'	=> array(
			'title'		=> 'Все онлайн-события',
			'route'		=> 'master/master',
			'href'			=>	$this->url->link('master/master'),
			'active'		=>	$parent === 'master' ? true : false,
		)
	)
);

// event
$menu_data['event'] = array(
	'title'			=>	'Мероприятия',
	'route'			=>	'avevent/event',
	'href'			=>	$this->url->link('avevent/event'),
	'active'		=>	$parent === 'event' ? true : false,
	'children'	=> array(
		'all'	=> array(
			'title'		=> 'Все мероприятия',
			'route'		=> 'avevent/event',
			'href'			=>	$this->url->link('avevent/event'),
			'active'		=>	$parent === 'event' ? true : false,
		)
	)
);

// company
/*$company_childs = array();
if($parent === 'company' && false) {
	$this->load->model('company/company');
	$company_cats = $this->model_company_company->getCompanyCategories();
	$company_cat_id = !empty($this->request->get['company_category_id']) ? $this->request->get['company_category_id'] : 0;
	foreach($company_cats as $item) {
		$company_childs[] = array(
			'title'		=> $item['title'],
			'route'		=> 'company/company/category',
			'href'		=>	$this->url->link('company/company/category', 'company_category_id=' . $item['category_id']),
			'active'	=>	$item['category_id'] == $company_cat_id ? true : false,
		);
	}
}*/

$menu_data['expert'] = array(
	'title'			=>	'Эксперты',
	'route'			=>	'expert/list',
	'href'			=>	$this->url->link('expert/list'),
	'active'		=>	$parent === 'expert' ? true : false,
	'children'	=> array()
);

// adv
$data['adv'] = $this->config->get('themeset_adv');

$data['menu'] = $menu_data;

?>