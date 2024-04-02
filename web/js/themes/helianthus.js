define(function() {
var theme = {
    backgroundColor: '#F2F2E6',
    // é»˜è®¤è‰²æ¿
    color: [
        '#44B7D3','#E42B6D','#F4E24E','#FE9616','#8AED35',
        '#ff69b4','#ba55d3','#cd5c5c','#ffa500','#40e0d0',
        '#E95569','#ff6347','#7b68ee','#00fa9a','#ffd700',
        '#6699FF','#ff6666','#3cb371','#b8860b','#30e0e0'
    ],

    // å›¾è¡¨æ ‡é¢˜
    title: {
        backgroundColor: '#F2F2E6',
        itemGap: 10,               // ä¸»å‰¯æ ‡é¢˜çºµå‘é—´éš”ï¼Œå•ä½pxï¼Œé»˜è®¤ä¸º10ï¼Œ
        textStyle: {
            color: '#8A826D'          // ä¸»æ ‡é¢˜æ–‡å­—é¢œè‰²
        },
        subtextStyle: {
            color: '#E877A3'          // å‰¯æ ‡é¢˜æ–‡å­—é¢œè‰²
        }
    },

    // å€¼åŸŸ
    dataRange: {
        x:'right',
        y:'center',
        itemWidth: 5,
        itemHeight:25,
        color:['#E42B6D','#F9AD96'],
        text:['é«˜','ä½Ž'],         // æ–‡æœ¬ï¼Œé»˜è®¤ä¸ºæ•°å€¼æ–‡æœ¬
        textStyle: {
            color: '#8A826D'          // å€¼åŸŸæ–‡å­—é¢œè‰²
        }
    },

    toolbox: {
        color : ['#E95569','#E95569','#E95569','#E95569'],
        effectiveColor : '#ff4500',
        itemGap: 8
    },

    // æç¤ºæ¡†
    tooltip: {
        backgroundColor: 'rgba(138,130,109,0.7)',     // æç¤ºèƒŒæ™¯é¢œè‰²ï¼Œé»˜è®¤ä¸ºé€æ˜Žåº¦ä¸º0.7çš„é»‘è‰²
        axisPointer : {            // åæ ‡è½´æŒ‡ç¤ºå™¨ï¼Œåæ ‡è½´è§¦å‘æœ‰æ•ˆ
            type : 'line',         // é»˜è®¤ä¸ºç›´çº¿ï¼Œå¯é€‰ä¸ºï¼š'line' | 'shadow'
            lineStyle : {          // ç›´çº¿æŒ‡ç¤ºå™¨æ ·å¼è®¾ç½®
                color: '#6B6455',
                type: 'dashed'
            },
            crossStyle: {          //åå­—å‡†æ˜ŸæŒ‡ç¤ºå™¨
                color: '#A6A299'
            },
            shadowStyle : {                     // é˜´å½±æŒ‡ç¤ºå™¨æ ·å¼è®¾ç½®
                color: 'rgba(200,200,200,0.3)'
            }
        }
    },

    // åŒºåŸŸç¼©æ”¾æŽ§åˆ¶å™¨
    dataZoom: {
        dataBackgroundColor: 'rgba(130,197,209,0.6)',            // æ•°æ®èƒŒæ™¯é¢œè‰²
        fillerColor: 'rgba(233,84,105,0.1)',   // å¡«å……é¢œè‰²
        handleColor: 'rgba(107,99,84,0.8)'     // æ‰‹æŸ„é¢œè‰²
    },

    // ç½‘æ ¼
    grid: {
        borderWidth:0
    },

    // ç±»ç›®è½´
    categoryAxis: {
        axisLine: {            // åæ ‡è½´çº¿
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: '#6B6455'
            }
        },
        splitLine: {           // åˆ†éš”çº¿
            show: false
        }
    },

    // æ•°å€¼åž‹åæ ‡è½´é»˜è®¤å‚æ•°
    valueAxis: {
        axisLine: {            // åæ ‡è½´çº¿
            show: false
        },
        splitArea : {
            show: false
        },
        splitLine: {           // åˆ†éš”çº¿
            lineStyle: {       // å±žæ€§lineStyleï¼ˆè¯¦è§lineStyleï¼‰æŽ§åˆ¶çº¿æ¡æ ·å¼
                color: ['#FFF'],
                type: 'dashed'
            }
        }
    },

    polar : {
        axisLine: {            // åæ ‡è½´çº¿
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: '#ddd'
            }
        },
        splitArea : {
            show : true,
            areaStyle : {
                color: ['rgba(250,250,250,0.2)','rgba(200,200,200,0.2)']
            }
        },
        splitLine : {
            lineStyle : {
                color : '#ddd'
            }
        }
    },

    timeline : {
        lineStyle : {
            color : '#6B6455'
        },
        controlStyle : {
            normal : { color : '#6B6455'},
            emphasis : { color : '#6B6455'}
        },
        symbol : 'emptyCircle',
        symbolSize : 3
    },

    // æŸ±å½¢å›¾é»˜è®¤å‚æ•°
    bar: {
        itemStyle: {
            normal: {
                barBorderRadius: 0
            },
            emphasis: {
                barBorderRadius: 0
            }
        }
    },

    // æŠ˜çº¿å›¾é»˜è®¤å‚æ•°
    line: {
        smooth : true,
        symbol: 'emptyCircle',  // æ‹ç‚¹å›¾å½¢ç±»åž‹
        symbolSize: 3           // æ‹ç‚¹å›¾å½¢å¤§å°
    },


    // Kçº¿å›¾é»˜è®¤å‚æ•°
    k: {
        itemStyle: {
            normal: {
                color: '#E42B6D',       // é˜³çº¿å¡«å……é¢œè‰²
                color0: '#44B7D3',      // é˜´çº¿å¡«å……é¢œè‰²
                lineStyle: {
                    width: 1,
                    color: '#E42B6D',   // é˜³çº¿è¾¹æ¡†é¢œè‰²
                    color0: '#44B7D3'   // é˜´çº¿è¾¹æ¡†é¢œè‰²
                }
            }
        }
    },

    // æ•£ç‚¹å›¾é»˜è®¤å‚æ•°
    scatter: {
        itemStyle: {
            normal: {
                borderWidth:1,
                borderColor:'rgba(200,200,200,0.5)'
            },
            emphasis: {
                borderWidth:0
            }
        },
        symbol: 'circle',    // å›¾å½¢ç±»åž‹
        symbolSize: 4        // å›¾å½¢å¤§å°ï¼ŒåŠå®½ï¼ˆåŠå¾„ï¼‰å‚æ•°ï¼Œå½“å›¾å½¢ä¸ºæ–¹å‘æˆ–è±å½¢åˆ™æ€»å®½åº¦ä¸ºsymbolSize * 2
    },

    // é›·è¾¾å›¾é»˜è®¤å‚æ•°
    radar : {
        symbol: 'emptyCircle',    // å›¾å½¢ç±»åž‹
        symbolSize:3
        //symbol: null,         // æ‹ç‚¹å›¾å½¢ç±»åž‹
        //symbolRotate : null,  // å›¾å½¢æ—‹è½¬æŽ§åˆ¶
    },

    map: {
        itemStyle: {
            normal: {
                areaStyle: {
                    color: '#ddd'
                },
                label: {
                    textStyle: {
                        color: '#E42B6D'
                    }
                }
            },
            emphasis: {                 // ä¹Ÿæ˜¯é€‰ä¸­æ ·å¼
                areaStyle: {
                    color: '#fe994e'
                },
                label: {
                    textStyle: {
                        color: 'rgb(100,0,0)'
                    }
                }
            }
        }
    },

    force : {
        itemStyle: {
            normal: {
                nodeStyle : {
                    borderColor : 'rgba(0,0,0,0)'
                },
                linkStyle : {
                    color : '#6B6455'
                }
            }
        }
    },

    chord : {
        itemStyle : {
            normal : {
                chordStyle : {
                    lineStyle : {
                        width : 0,
                        color : 'rgba(128, 128, 128, 0.5)'
                    }
                }
            },
            emphasis : {
                chordStyle : {
                    lineStyle : {
                        width : 1,
                        color : 'rgba(128, 128, 128, 0.5)'
                    }
                }
            }
        }
    },

    gauge : {                  // ä»ªè¡¨ç›˜
        center:['50%','80%'],
        radius:'100%',
        startAngle: 180,
        endAngle : 0,
        axisLine: {            // åæ ‡è½´çº¿
            show: true,        // é»˜è®¤æ˜¾ç¤ºï¼Œå±žæ€§showæŽ§åˆ¶æ˜¾ç¤ºä¸Žå¦
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: [[0.2, '#44B7D3'],[0.8, '#6B6455'],[1, '#E42B6D']],
                width: '40%'
            }
        },
        axisTick: {            // åæ ‡è½´å°æ ‡è®°
            splitNumber: 2,   // æ¯ä»½splitç»†åˆ†å¤šå°‘æ®µ
            length: 5,        // å±žæ€§lengthæŽ§åˆ¶çº¿é•¿
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: '#fff'
            }
        },
        axisLabel: {           // åæ ‡è½´æ–‡æœ¬æ ‡ç­¾ï¼Œè¯¦è§axis.axisLabel
            textStyle: {       // å…¶ä½™å±žæ€§é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                color: '#fff',
                fontWeight:'bolder'
            }
        },
        splitLine: {           // åˆ†éš”çº¿
            length: '5%',         // å±žæ€§lengthæŽ§åˆ¶çº¿é•¿
            lineStyle: {       // å±žæ€§lineStyleï¼ˆè¯¦è§lineStyleï¼‰æŽ§åˆ¶çº¿æ¡æ ·å¼
                color: '#fff'
            }
        },
        pointer : {
            width : '40%',
            length: '80%',
            color: '#fff'
        },
        title : {
          offsetCenter: [0, -20],       // x, yï¼Œå•ä½px
          textStyle: {       // å…¶ä½™å±žæ€§é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
            color: 'auto',
            fontSize: 20
          }
        },
        detail : {
            offsetCenter: [0, 0],       // x, yï¼Œå•ä½px
            textStyle: {       // å…¶ä½™å±žæ€§é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                color: 'auto',
                fontSize: 40
            }
        }
    },

    textStyle: {
        fontFamily: 'å¾®è½¯é›…é»‘, Arial, Verdana, sans-serif'
    }
};

    return theme;
});