<?php

function getUserLang() {
    return session()->has('lang') ? session('lang') : 'es';
}

function getOpositeUserLang() {
    return getUserLang()=='es' ? 'en' : 'es';
}

function getExcerpt($str, $startPos = 0, $maxLength = 100) {
    $str= removeImageTag($str);
    if (strlen($str) > $maxLength) {
        $excerpt = substr($str, $startPos, $maxLength - 3);
        $lastSpace = strrpos($excerpt, ' ');
        $excerpt = substr($excerpt, 0, $lastSpace);
        $excerpt .= '...';
    } else {
        $excerpt = $str;
    }

    return $excerpt;
}

function removeImageTag($content){
    $content = preg_replace("/<img[^>]+\>/i", "(image) ", $content); 
    return $content;
}
