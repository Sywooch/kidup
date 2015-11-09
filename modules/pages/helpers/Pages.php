<?php

namespace pages\helpers;

use app\components\Cache;
use GuzzleHttp\Client;
use yii\base\Component;
use yii\helpers\Url;

class Pages extends Component
{
    private $pages;
    private $baseUrl = 'http://pages.kidup.dk/wp-json/';

    public function __construct()
    {
        $this->pages = [
            1 => 'terms-and-conditions',
            4 => 'privacy',
            12 => 'faq',
            14 => 'safety',
            45 => 'how-to-rentout',
            53 => 'about-kidup',
            62 => 'how-to-rent',
            68 => 'why-rent',
            26 => 'guide',
            245 => 'cookies'
        ];
        return parent::__construct();
    }

    public function get($id)
    {
        if (!is_int($id)) {
            foreach ($this->pages as $id2 => $p) {
                if ($id == $p) {
                    $id = $id2;
                }
            }
        }
        $client = new Client();
        $func = function () use ($client, $id) {
            $res = $client->get($this->baseUrl . 'posts/' . $id);
            return $res->json();
        };
        return Cache::build('pages-wordpress')
            ->variations($id)
            ->tags(['wordpress'])
            ->data($func);
    }

    public function getPages()
    {
        return $this->pages;
    }

    public function url($slug)
    {
        if (is_int($slug)) {
            $slug = $this->pages[$slug];
        }
        return Url::to('@web/p/' . $slug);
    }
}