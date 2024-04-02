define(function() {

var theme = {
    // é»˜è®¤è‰²æ¿
    color: [
        '#1790cf','#1bb2d8','#99d2dd','#88b0bb',
        '#1c7099','#038cc4','#75abd0','#afd6dd'
    ],

    // å›¾è¡¨æ ‡é¢˜
    title: {
        textStyle: {
            fontWeight: 'normal',
            color: '#1790cf'
        }
    },
    
    // å€¼åŸŸ
    dataRange: {
        color:['#1178ad','#72bbd0']
    },

    // å·¥å…·ç®±
    toolbox: {
        color : ['#1790cf','#1790cf','#1790cf','#1790cf']
    },

    // æç¤ºæ¡†
    tooltip: {
        backgroundColor: 'rgba(0,0,0,0.5)',
        axisPointer : {            // åæ ‡è½´æŒ‡ç¤ºå™¨ï¼Œåæ ‡è½´è§¦å‘æœ‰æ•ˆ
            type : 'line',         // é»˜è®¤ä¸ºç›´çº¿ï¼Œå¯é€‰ä¸ºï¼š'line' | 'shadow'
            lineStyle : {          // ç›´çº¿æŒ‡ç¤ºå™¨æ ·å¼è®¾ç½®
                color: '#1790cf',
                type: 'dashed'
            },
            crossStyle: {
                color: '#1790cf'
            },
            shadowStyle : {                     // é˜´å½±æŒ‡ç¤ºå™¨æ ·å¼è®¾ç½®
                color: 'rgba(200,200,200,0.3)'
            }
        }
    },

    // åŒºåŸŸç¼©æ”¾æŽ§åˆ¶å™¨
    dataZoom: {
        dataBackgroundColor: '#eee',            // æ•°æ®èƒŒæ™¯é¢œè‰²
        fillerColor: 'rgba(144,197,237,0.2)',   // å¡«å……é¢œè‰²
        handleColor: '#1790cf'     // æ‰‹æŸ„é¢œè‰²
    },
    
    // ç½‘æ ¼
    grid: {
        borderWidth: 0
    },

    // ç±»ç›®è½´
    categoryAxis: {
        axisLine: {            // åæ ‡è½´çº¿
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: '#1790cf'
            }
        },
        splitLine: {           // åˆ†éš”çº¿
            lineStyle: {       // å±žæ€§lineStyleï¼ˆè¯¦è§lineStyleï¼‰æŽ§åˆ¶çº¿æ¡æ ·å¼
                color: ['#eee']
            }
        }
    },

    // æ•°å€¼åž‹åæ ‡è½´é»˜è®¤å‚æ•°
    valueAxis: {
        axisLine: {            // åæ ‡è½´çº¿
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: '#1790cf'
            }
        },
        splitArea : {
            show : true,
            areaStyle : {
                color: ['rgba(250,250,250,0.1)','rgba(200,200,200,0.1)']
            }
        },
        splitLine: {           // åˆ†éš”çº¿
            lineStyle: {       // å±žæ€§lineStyleï¼ˆè¯¦è§lineStyleï¼‰æŽ§åˆ¶çº¿æ¡æ ·å¼
                color: ['#eee']
            }
        }
    },

    timeline : {
        lineStyle : {
            color : '#1790cf'
        },
        controlStyle : {
            normal : { color : '#1790cf'},
            emphasis : { color : '#1790cf'}
        }
    },

    // Kçº¿å›¾é»˜è®¤å‚æ•°
    k: {
        itemStyle: {
            normal: {
                color: '#1bb2d8',          // é˜³çº¿å¡«å……é¢œè‰²
                color0: '#99d2dd',      // é˜´çº¿å¡«å……é¢œè‰²
                lineStyle: {
                    width: 1,
                    color: '#1c7099',   // é˜³çº¿è¾¹æ¡†é¢œè‰²
                    color0: '#88b0bb'   // é˜´çº¿è¾¹æ¡†é¢œè‰²
                }
            }
        }
    },
    
    map: {
        itemStyle: {
            normal: {
                areaStyle: {
                    color: '#ddd'
                },
                label: {
                    textStyle: {
                        color: '#c12e34'
                    }
                }
            },
            emphasis: {                 // ä¹Ÿæ˜¯é€‰ä¸­æ ·å¼
                areaStyle: {
                    color: '#99d2dd'
                },
                label: {
                    textStyle: {
                        color: '#c12e34'
                    }
                }
            }
        }
    },
    
    force : {
        itemStyle: {
            normal: {
                linkStyle : {
                    color : '#1790cf'
                }
            }
        }
    },
    
    chord : {
        padding : 4,
        itemStyle : {
            normal : {
                borderWidth: 1,
                borderColor: 'rgba(128, 128, 128, 0.5)',
                chordStyle : {
                    lineStyle : {
                        color : 'rgba(128, 128, 128, 0.5)'
                    }
                }
            },
            emphasis : {
                borderWidth: 1,
                borderColor: 'rgba(128, 128, 128, 0.5)',
                chordStyle : {
                    lineStyle : {
                        color : 'rgba(128, 128, 128, 0.5)'
                    }
                }
            }
        }
    },
    
    gauge : {
        axisLine: {            // åæ ‡è½´çº¿
            show: true,        // é»˜è®¤æ˜¾ç¤ºï¼Œå±žæ€§showæŽ§åˆ¶æ˜¾ç¤ºä¸Žå¦
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: [[0.2, '#1bb2d8'],[0.8, '#1790cf'],[1, '#1c7099']], 
                width: 8
            }
        },
        axisTick: {            // åæ ‡è½´å°æ ‡è®°
            splitNumber: 10,   // æ¯ä»½splitç»†åˆ†å¤šå°‘æ®µ
            length :12,        // å±žæ€§lengthæŽ§åˆ¶çº¿é•¿
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: 'auto'
            }
        },
        axisLabel: {           // åæ ‡è½´æ–‡æœ¬æ ‡ç­¾ï¼Œè¯¦è§axis.axisLabel
            textStyle: {       // å…¶ä½™å±žæ€§é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                color: 'auto'
            }
        },
        splitLine: {           // åˆ†éš”çº¿
            length : 18,         // å±žæ€§lengthæŽ§åˆ¶çº¿é•¿
            lineStyle: {       // å±žæ€§lineStyleï¼ˆè¯¦è§lineStyleï¼‰æŽ§åˆ¶çº¿æ¡æ ·å¼
                color: 'auto'
            }
        },
        pointer : {
            length : '90%',
            color : 'auto'
        },
        title : {
            textStyle: {       // å…¶ä½™å±žæ€§é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                color: '#333'
            }
        },
        detail : {
            textStyle: {       // å…¶ä½™å±žæ€§é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                color: 'auto'
            }
        }
    },
    
    textStyle: {
        fontFamily: 'å¾®è½¯é›…é»‘, Arial, Verdana, sans-serif'
    }
};

    return theme;
});