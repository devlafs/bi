define(function() {

var theme = {
    // é»˜è®¤è‰²æ¿
    color: [
        '#c12e34','#e6b600','#0098d9','#2b821d',
        '#005eaa','#339ca8','#cda819','#32a487'
    ],

    // å›¾è¡¨æ ‡é¢˜
    title: {
        textStyle: {
            fontWeight: 'normal'
        }
    },
    
    // å€¼åŸŸ
    dataRange: {
        itemWidth: 15,             // å€¼åŸŸå›¾å½¢å®½åº¦ï¼Œçº¿æ€§æ¸å˜æ°´å¹³å¸ƒå±€å®½åº¦ä¸ºè¯¥å€¼ * 10
        color:['#1790cf','#a2d4e6']
    },

    // å·¥å…·ç®±
    toolbox: {
        color : ['#06467c','#00613c','#872d2f','#c47630']
    },

    // æç¤ºæ¡†
    tooltip: {
        backgroundColor: 'rgba(0,0,0,0.6)'
    },

    // åŒºåŸŸç¼©æ”¾æŽ§åˆ¶å™¨
    dataZoom: {
        dataBackgroundColor: '#dedede',            // æ•°æ®èƒŒæ™¯é¢œè‰²
        fillerColor: 'rgba(154,217,247,0.2)',   // å¡«å……é¢œè‰²
        handleColor: '#005eaa'     // æ‰‹æŸ„é¢œè‰²
    },
    
    // ç½‘æ ¼
    grid: {
        borderWidth: 0
    },
    
    // ç±»ç›®è½´
    categoryAxis: {
        axisLine: {            // åæ ‡è½´çº¿
            show: false
        },
        axisTick: {            // åæ ‡è½´å°æ ‡è®°
            show: false
        }
    },

    // æ•°å€¼åž‹åæ ‡è½´é»˜è®¤å‚æ•°
    valueAxis: {
        axisLine: {            // åæ ‡è½´çº¿
            show: false
        },
        axisTick: {            // åæ ‡è½´å°æ ‡è®°
            show: false
        },
        splitArea: {           // åˆ†éš”åŒºåŸŸ
            show: true,       // é»˜è®¤ä¸æ˜¾ç¤ºï¼Œå±žæ€§showæŽ§åˆ¶æ˜¾ç¤ºä¸Žå¦
            areaStyle: {       // å±žæ€§areaStyleï¼ˆè¯¦è§areaStyleï¼‰æŽ§åˆ¶åŒºåŸŸæ ·å¼
                color: ['rgba(250,250,250,0.2)','rgba(200,200,200,0.2)']
            }
        }
    },
    
    timeline : {
        lineStyle : {
            color : '#005eaa'
        },
        controlStyle : {
            normal : { color : '#005eaa'},
            emphasis : { color : '#005eaa'}
        }
    },

    // Kçº¿å›¾é»˜è®¤å‚æ•°
    k: {
        itemStyle: {
            normal: {
                color: '#c12e34',          // é˜³çº¿å¡«å……é¢œè‰²
                color0: '#2b821d',      // é˜´çº¿å¡«å……é¢œè‰²
                lineStyle: {
                    width: 1,
                    color: '#c12e34',   // é˜³çº¿è¾¹æ¡†é¢œè‰²
                    color0: '#2b821d'   // é˜´çº¿è¾¹æ¡†é¢œè‰²
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
                    color: '#e6b600'
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
                    color : '#005eaa'
                }
            }
        }
    },
    
    chord : {
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
                color: [[0.2, '#2b821d'],[0.8, '#005eaa'],[1, '#c12e34']], 
                width: 5
            }
        },
        axisTick: {            // åæ ‡è½´å°æ ‡è®°
            splitNumber: 10,   // æ¯ä»½splitç»†åˆ†å¤šå°‘æ®µ
            length :8,        // å±žæ€§lengthæŽ§åˆ¶çº¿é•¿
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
            length : 12,         // å±žæ€§lengthæŽ§åˆ¶çº¿é•¿
            lineStyle: {       // å±žæ€§lineStyleï¼ˆè¯¦è§lineStyleï¼‰æŽ§åˆ¶çº¿æ¡æ ·å¼
                color: 'auto'
            }
        },
        pointer : {
            length : '90%',
            width : 3,
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