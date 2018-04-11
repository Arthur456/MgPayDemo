<?php
    include_once('./utils.php');
    include_once('./configs.php');
    if (!$_POST['body']) {
        ajax('请输入商品信息');
    }
    if (!$_POST['amount']) {
        ajax('请输入商品金额');
    }
    /**
     * 商品描述
     */
    $data['body'        ] = $_POST['body'];
    /**
     * 总金额
     */
    $data['total_fee'   ] = $_POST['amount'];
    /**
     * 商户号
     */
    $data['orgno'       ] =  MERCHANT_NUMBER;
    /**
     * 时间戳（单位s）
     */
    $data['secondtimestamp'] =  time();
    /**
     * 随机串
     */
    $data['nonce_str'] =  (string)time();
    /**
     * 结算方式
     */
    $data['t0t1'] =  'T1';
    /**
     * 商户订单号
     */
    $data['out_trade_no'] =  'order' . time();

    /**
     * 签名
     */
    $data['sign'] =  makeSign($data, MERCHANT_KEY);

    $result = post(API_QQ_ORDER_URL, $data);

    ajax($result);