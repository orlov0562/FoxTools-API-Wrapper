<?php

    namespace FoxTools;

    class FoxTools {
        private $_apiUrl = 'http://api.foxtools.ru/Proxy/text';
        private $_apiKey = 'eeb65cb3-a569-528e-b46d-a539fdc8028a'; // !! add your API key !!!
        private $_apiServiceId = '520';                            // !! add your SERVICE ID key !!!

        public function GetProxy(){
            $answer = null;
            $postData = $this->getProxyPostData();
            try {
                $proxyList = $this->DoWebRequest($this->_apiUrl, $postData);
            } catch (Exception $ex) {
                throw $ex;
            }
            $proxyList = $this->ApplyProxyListFilter($proxyList);
            return $proxyList;
        }

        private function GetProxyPostData(){
            // according to http://api.foxtools.ru/Proxy/
            return array(
                'id'        => $this->_apiServiceId,
                'key'       => $this->_apiKey,
                'page'      => 1,
                'max'       => 100,
                'protocol'  => 3,
                'anonymity' => 4,
                'portfrom'  => 0,
                'portto'    => 0,
                'country'   => 0,
                'uptime'    => 500,
                'available' => 1,
                'free'      => 1,
            );
        }

        private function DoWebRequest($url, array $postData=array()){
          $ret = null;
          $ch = curl_init();
          if(!$ch) throw new Exception('Can\'t get CURL handle');
          curl_setopt($ch, CURLOPT_URL, $url);
          if (!empty($postData)) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
          }
          $ret = curl_exec($ch);
          $error = curl_error($ch);
          curl_close($ch);
          if ($error) throw new Exception($error);
          return $ret;
        }

        private function ApplyProxyListFilter($proxyList){
            if (preg_match_all('~^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}:[0-9]{0,5}\s*$~Usimx', $proxyList, $regs)) {
                $validProxyList = array();
                for ($i=0; $i<count($regs[0]); $i++) {
                    $validProxyList[] = trim($regs[0][$i]);
                }
                $proxyList = implode(PHP_EOL, $validProxyList);
            }
            return $proxyList;
        }
    }