<?php
    function ajax ($info, $status = 0, $data = null){
        if (is_array($info)) {
            $result = $info;
        } else {
            $result['info'] = $info;
            $result['status'] = $status;
            if ($data) $result['data'] = $data;
        }
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($result));
    }

    function makeSign ($data, $merchant_key) {
        ksort($data);
        $signArr = [];
        foreach ($data as $key => $value) {
            if ($key !== 'sign') {
                $signArr[] = $key . '=' . $value;
            }
        }
        $signArr[] = 'key=' . $merchant_key;
        return strtoupper(md5(implode('&', $signArr)));
    }

    function post ($url, $data) {

        $opts = array (
            CURLOPT_POST           => 1,
            CURLOPT_URL            => $url,
            CURLOPT_POSTFIELDS     => $data,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_SSL_VERIFYHOST => FALSE,
            CURLOPT_SSLVERSION     => CURL_SSLVERSION_TLSv1
        );

        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        //发生错误，抛出异常
        if($error) throw new Exception('请求发生错误：' . $error);

        return json_decode($data, true);

    }