<?php
class ModelThemesetYoutube extends Model
{
    private $youtubeApiKey = 'AIzaSyAfGx47GSxvIkS1Ggq6BHguIjr0McixTRw';

    private $vkAccessToken = 'vk1.a.sKD12UO4-9qw4YlL5KUkEt8YW4v-9FNZAVnrRxevXWQirQNpwxKA4wyXyD2ZJu2WTz8UiMXv2z6mrF50Gimwr_VyJvrI71JsI0EL2ZDaKy4NBumx2sYT5oAZVTdlw8TQc2b8hAJzGuAQIpfgjkDXgWbFoFSRAyrYTx70fTsIK5pzwsiGOoZ2EMIj1XeMeBdPSlslowPdYzNDTgNAmogOvw';

    public function getVideos() {
        $videos = array();

        // Запрос для получения YouTube ссылок
        $query = $this->db->query("SELECT video FROM " . DB_PREFIX . "journal WHERE video LIKE 'https://www.youtube.com%' ORDER BY sort_order ASC");

        if ($query->num_rows) {
            foreach ($query->rows as $row) {
                $videos[] = $row['video'];
            }
        }

        return $videos;
    }

    public function getYoutubeTitle($url) {
        // Извлечение ID видео из URL
        parse_str(parse_url($url, PHP_URL_QUERY), $params);
        if (!isset($params['v'])) {
            return null;
        }
        $videoId = $params['v'];

        // Запрос к YouTube API для получения названия видео
        $apiUrl = "https://www.googleapis.com/youtube/v3/videos?id={$videoId}&key={$this->youtubeApiKey}&part=snippet";
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);

        return $data['items'][0]['snippet']['title'] ?? null;
    }

    public function searchVkVideo($title, $ownerId) {
        // Поиск видео на канале VK
        $apiUrl = "https://api.vk.com/method/video.search";
        $params = [
            'q' => $title,
            'owner_id' => $ownerId,
            'access_token' => $this->vkAccessToken,
            'v' => '5.131'
        ];
        $response = file_get_contents($apiUrl . '?' . http_build_query($params));
        $data = json_decode($response, true);

        return $data['response']['items'] ?? [];
    }

    public function processVideos() {
        $ownerId = '';

        $videos = $this->getVideos();
        $results = [];

        foreach ($videos as $videoUrl) {
            // Получение названия видео
            $title = $this->getYoutubeTitle($videoUrl);
        //print_r($videoUrl . ' -- ' . $title . PHP_EOL);

            if ($title) {
                // Поиск видео в VK
                $vkVideos = $this->searchVkVideo($title, $ownerId);
                $results[] = [
                    'youtube_url' => $videoUrl,
                    'youtube_title' => $title,
                    'vk_results' => $vkVideos
                ];
            }
        }
        echo '<pre>';
        print_r($results);
        die();

        return $results;
    }



}
