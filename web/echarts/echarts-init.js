var myChart = echarts.init(document.getElementById('bar-chart'));

option = {
    tooltip : {
        trigger: 'axis'
    },
    legend: {
        data:['1.1.1 - Dados de pedidos']
    },
    toolbox: {
        show : true,
        feature : {
            mark : {show: true, title: {mark: "Marcador",markUndo: "Remover marca",markClear: "Limpar marcas"}},
            magicType : {show: true, type: ['line', 'bar'], title: {line: "Alterar para linha",bar:"Alterar para coluna"}},
            restore : {show: true, title: 'Restaurar'},
            saveAsImage : {show: true, title: 'Exportar como Imagem'}
        }
    },
    color: ["#628e8d"],
    calculable : true,
    xAxis : [
        {
            type : 'category',
            data : ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        }
    ],
    yAxis : [
        {
            type : 'value'
        }
    ],
    series : [
        {
            name:'1.1.1 - Dados de pedidos',
            type:'bar',
            data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3],
            markLine : {
                data : [
                    {type : 'average', name: 'Média'}
                ]
            }
        },
    ]
};
                    
myChart.setOption(option, true), $(function() {
    function resize() {
        setTimeout(function() {
            myChart.resize()
        }, 100)
    }
    $(window).on("resize", resize), $(".sidebartoggler").on("click", resize)
});
