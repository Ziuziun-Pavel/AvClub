<?php
class ControllerCustomCustom extends Controller {
    function index() {
        $data['customlink'] = $this->url->link('custom/custom');
        $this->response->setOutput('<a href="'.$data['customlink'].'">Custom URL Rewrite Link</a>');
    }
}