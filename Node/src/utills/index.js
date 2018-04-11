const request = require('request');
const crypto = require('crypto');

function md5 (content) {
    let md5 = crypto.createHash('md5');
    md5.update(content);
    return md5.digest('hex');
}

function makeSign (data, key) {
    let signArr = [];
    for (let key of Object.keys(data).sort()) {
        if (key !== 'sign') {
            signArr.push(`${key}=${data[key]}`);
        }
    }
    signArr.push(`key=${key}`);
    return md5(signArr.join('&')).toUpperCase();
}

function post (url, form) {
    const options = {
        url,
        form,
        method: 'POST',
        headers:
            {
                'Cache-Control': 'no-cache',
                'Content-Type': 'application/x-www-form-urlencoded'
            },
        rejectUnauthorized: false
    };
    return new Promise((resolve, reject) => {
        request(options, (error, response, body) => {
            if (error) reject(error);
            resolve(body);
        });
    });

}

module.exports = {
    md5,
    makeSign,
    post
}