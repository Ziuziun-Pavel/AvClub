<?php
class ControllerThemesetYoutube extends Controller {
    public function index() {
        // НЕ УДАЛЯТЬ!
        // используется для локального приложения

        return true;
    }

    public function getYoutubeLinks() {
        $this->load->model('themeset/youtube');
        $this->model_themeset_youtube->processVideos();
    }
}