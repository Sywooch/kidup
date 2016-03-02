<?php

namespace app\commands;

use item\models\Item;
use search\components\ItemSearchDb;
use Yii;
use yii\console\Controller;

class ProductController extends Controller
{

    public function actionSyncDb()
    {
        define(YII_ENV, 'prod');
        $batches = Item::find()
            ->where(['is_available' => 1])
            ->batch(10);

        foreach ($batches as $batch) {
            (new ItemSearchDb())->sync($batch);
        }
    }


    private function uploadFile($file, $itemId, $accessToken)
    {
        if ($itemId) {
            $target_url = 'https://www.kidup.dk/api/v1/media?access-token=' . $accessToken . '&item_id=' . $itemId;
        } else {
            $target_url = 'https://www.kidup.dk/api/v1/media?access-token=' . $accessToken . '&profile_pic=true';
        }
        //This needs to be the full path to the file you want to send.
        /* curl will accept an array here too.
         * Many examples I found showed a url-encoded string instead.
         * Take note that the 'key' in the array will be the key that shows up in the
         * $_FILES array of the accept script. and the at sign '@' is required before the
         * file name.
         */
        $post = array('file' => curl_file_create($file, 'image/jpeg', 'cattle-01.jpg'));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $target_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        try {
            $res = json_decode($result);
            if (is_int($res->id) || $res == true) {
                return true;
            } else {

            }
        } catch (\yii\base\ErrorException $e) {

        }
        return $result;
    }
}
