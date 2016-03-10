<?php
namespace admin\models;

class VariableExtractor {

    public static function extract($template) {
        $template = str_replace("\n", '', $template);
        preg_match_all('/t\s*\(\s*\'(.*?)\'\s*,\s*\'(.*?)\'/', $template, $matches1);
        preg_match_all('/t\s*\(\s*\'(.*?)\'\s*,\s*"(.*?)"/', $template, $matches2);
        preg_match_all('/t\s*\(\s*"(.*?)"\s*,\s*\'(.*?)\'/', $template, $matches3);
        preg_match_all('/t\s*\(\s*"(.*?)"\s*,\s*"(.*?)"/', $template, $matches4);
        $keys = array_merge($matches1[1], $matches2[1], $matches3[1], $matches4[1]);
        $translations = array_merge($matches1[2], $matches2[2], $matches3[2], $matches4[2]);
        for ($i = 0; $i < count($keys); $i++) {
            $result[$keys[$i]] = $translations[$i];
        }
        return $result;
    }

}
?>