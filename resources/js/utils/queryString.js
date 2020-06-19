//获取url参数
export function queryString(url, key) {
    const query = url.split('?')[1];
    const obj = {};
    if (query) {
        const parms = query.split('&');
        for (let i = 0; i < parms.length; i++) {
            const name = parms[i].split('=')[0];
            const value = parms[i].split('=')[1];
            // const value = value1.match(new RegExp('(^|&)' + key + '=([^&]*)(&|$)'));
            obj[name] = value;
        }
        if (Object.hasOwnProperty.call(obj, key)) {
            return obj[key];
        }
    }
    return false;
}

//获取url里的中文
export function queryStringZh (url,key) {
    // 正则筛选地址栏
    var reg = new RegExp('(^|&)' + key + '=([^&]*)(&|$)')
    // 匹配目标参数
    var result = url.substr(1).match(reg)
    // 返回参数值
    return result ? decodeURIComponent(result[2]) : ''
}

export function queryStringChange(uri, par, par_value) {
    var pattern = par + '=([^&]*)';
    var replaceText = par + '=' + par_value;
    if (uri.match(pattern)) {//如果连接中带这个参数
        var tmp = '/\\' + par + '=[^&]*/';
        tmp = uri.replace(eval(tmp), replaceText);
        return (tmp);
    }
    else {
        if (uri.match('[\?]')) {//如果链接中不带这个参数但是有其他参数
            return uri + '&' + replaceText;
        }
        else {//如果链接中没有带任何参数
            return uri + '?' + replaceText;
        }
    }
    return uri + '\n' + par + '\n' + par_value;
}
