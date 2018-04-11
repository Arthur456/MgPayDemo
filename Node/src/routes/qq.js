const express = require('express');

const {
    md5,
    post,
    makeSign
} = require('../utills');

const {
    MERCHANT_NUMBER,
    MERCHANT_KEY,
    API_QQ_ORDER_URL
} = require('../configs');

const router = express.Router();

router.get('/', (request, response, next) => {
    response.render('qq');
});

router.post('/', async (request, response, next) => {
    const {
        body,
        amount: total_fee
    } = request.body;

    if (!body) {
        return response.send({ status: 0, info: '请输入商品信息' });
    }
    if (!total_fee) {
        return response.send({ status: 0, info: '请输入商品金额' });
    }
    const date = new Date();

    const data = {
        /**
         * 商户号
         */
        orgno: MERCHANT_NUMBER,
        /**
         * 时间戳（单位s）
         */
        secondtimestamp: Math.floor(date.getTime() / 1000),
        /**
         * 随机串
         */
        nonce_str: date.getTime(),
        /**
         * 签名
         */
        sign: '',
        /**
         * 商品描述
         */
        body,
        /**
         * 总金额
         */
        total_fee,
        /**
         * 结算方式
         */
        t0t1: 'T1',
        /**
         * 商户订单号
         */
        out_trade_no: date.getTime()
    };

    Object.assign(data, { sign: makeSign(data, MERCHANT_KEY) })

    try {
        const result = await post(API_QQ_ORDER_URL, data);
        response.send(result);
    } catch (error) {
        response.send(error);
    }
});

module.exports = router;
