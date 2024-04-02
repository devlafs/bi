<?php

$precision = isset($campo['casas_decimais']) ? $campo['casas_decimais'] : 2;
$thousand = isset($campo['separador_milhar']) ? $campo['separador_milhar'] : '.';
$decimal = isset($campo['separador_decimal']) ? $campo['separador_decimal'] : ',';

$js = <<<"JS"
        
    function formatNumber(number, places, symbol, thousand, decimal)
    {
        places = !isNaN(places = Math.abs(places)) ? places : 2;
        symbol = symbol !== undefined ? symbol : "$";
        thousand = thousand || ".";
        decimal = decimal || ",";
        var negative = number < 0 ? "-" : "",
            i = parseInt(number = Math.abs(+number || 0).toFixed(places), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0;
        return symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (places ? decimal + Math.abs(number - i).toFixed(places).slice(2) : "");
    };
        
    function formatNumberAbb(value, tipo, precision, symbol, thousand, decimal) 
    {
        if (value === null) { return null; }
        if (value === 0) { return '0'; }
        precision = (!precision || precision < 0) ? 0 : precision;
        
        var b = (value).toPrecision(2).split("e"),
            k = tipo - 1,
            c = k < 1 ? value.toFixed(0 + precision) : (value / Math.pow(10, k * 3) ).toFixed(precision),
            d = c < 0 ? c : Math.abs(c),
            negative = d < 0 ? "-" : "",
            i = parseInt(d = Math.abs(+d || 0).toFixed(precision), 10) + "",
            j = (j = i.length) > 3 ? j % 3 : 0,
            e = symbol + negative + (j ? i.substr(0, j) + thousand : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousand) + (precision ? decimal + Math.abs(d - i).toFixed(precision).slice(2) : "") + ['', 'K', 'M', 'B', 'T'][k];
        return e;
    }
        
    function formatData(value, tipo, notAbb) 
    {
        if(tipo == '1' || notAbb == true)
        {
            return formatNumber(value, {$precision}, "", "{$thousand}", "{$decimal}");
        }
        else
        {
            return formatNumberAbb(value, tipo, {$precision}, "", "{$thousand}", "{$decimal}");
        }
    }
        
JS;

$this->registerJs($js);