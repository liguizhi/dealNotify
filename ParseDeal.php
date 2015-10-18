<?php


class ParseDeal {
    /**
     * getContents 
     * 
     * @param mixed $url 
     * @static
     * @access public
     * @return void
     */
    public static function getContents($url='') {
        $contents = self::getHtml('http://www.firstp2p.com', '', true);
        file_put_contents('content.txt',$contents);
        $reg = "/<div class=\"p2p_product p5\">(.*)<div class=\"p2p_product p5\">/";
        $reg = "/<div class=\"p2p_product p5\">/";
#        $reg = "/<div class=\"p2p_product p5\">(.*)<\/div>/i";
        $match = preg_split($reg, $contents['html']);
#        preg_match_all($reg, $contents['html'], $match);
        return $match;
    }
    public static function getHtml($url,$handle='',$needClose=false) {
        if(!$handle)    $handle = curl_init();
        $timeout = 50;
        $useragent='Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)';
        $header = array('Accept-Language:zh-cn', 'Connection:Keep-Alive', 'Cache-Control:no-cache');
        curl_setopt($handle, CURLOPT_REFERER, $url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($handle, CURLOPT_USERAGENT, $useragent);
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_TIMEOUT, $timeout);
        $html = curl_exec($handle);
        $info = curl_getinfo($handle);
        if(isset($info['redirect_url']) && $info['redirect_url']) {
            $url = $info['redirect_url'];
            curl_setopt($handle, CURLOPT_URL, $url);
            $html = curl_exec($handle);
        }
//        var_dump($info);exit;
        $html = self::str2utf8($html);
        if($needClose) curl_close($handle);
        return array('html'=>$html, 'handle'=>$handle, 'status' => $needClose);
    }
    /**
     * 字符转换
     * @param type $content
     * @return type
     */
    public static function str2utf8($content) {
        $encode  = mb_detect_encoding($content , array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
        if($encode != 'UTF-8') {
            $content = mb_convert_encoding($content,'UTF-8',array('UTF-8','ASCII','EUC-CN','CP936','BIG-5','GB2312','GBK'));
        }
        return $content;
    }
}
