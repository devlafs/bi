define({
    // å…¨å›¾é»˜è®¤èƒŒæ™¯
    // backgroundColor: 'rgba(0,0,0,0)',
    
    // é»˜è®¤è‰²æ¿
    color: ['#8aedd5','#93bc9e','#cef1db','#7fe579','#a6d7c2',
            '#bef0bb','#99e2vb','#94f8a8','#7de5b8','#4dfb70'],

    
    
    // å€¼åŸŸ
    dataRange: {
        color:['#93bc92','#bef0bb']
    },

    // Kçº¿å›¾é»˜è®¤å‚æ•°
    k: {
        // barWidth : null          // é»˜è®¤è‡ªé€‚åº”
        // barMaxWidth : null       // é»˜è®¤è‡ªé€‚åº” 
        itemStyle: {
            normal: {
                color: '#8aedd5',          // é˜³çº¿å¡«å……é¢œè‰²
                color0: '#7fe579',      // é˜´çº¿å¡«å……é¢œè‰²
                lineStyle: {
                    width: 1,
                    color: '#8aedd5',   // é˜³çº¿è¾¹æ¡†é¢œè‰²
                    color0: '#7fe579'   // é˜´çº¿è¾¹æ¡†é¢œè‰²
                }
            },
            emphasis: {
                // color: å„å¼‚,
                // color0: å„å¼‚
            }
        }
    },
    
    // é¥¼å›¾é»˜è®¤å‚æ•°
    pie: {
        itemStyle: {
            normal: {
                // color: å„å¼‚,
                borderColor: '#fff',
                borderWidth: 1,
                label: {
                    show: true,
                    position: 'outer',
                  textStyle: {color: '#1b1b1b'},
                  lineStyle: {color: '#1b1b1b'}
                    // textStyle: null      // é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                },
                labelLine: {
                    show: true,
                    length: 20,
                    lineStyle: {
                        // color: å„å¼‚,
                        width: 1,
                        type: 'solid'
                    }
                }
            }
        }
    },
    
    map: {
        mapType: 'china',   // å„çœçš„mapTypeæš‚æ—¶éƒ½ç”¨ä¸­æ–‡
        mapLocation: {
            x : 'center',
            y : 'center'
            // width    // è‡ªé€‚åº”
            // height   // è‡ªé€‚åº”
        },
        showLegendSymbol : true,       // æ˜¾ç¤ºå›¾ä¾‹é¢œè‰²æ ‡è¯†ï¼ˆç³»åˆ—æ ‡è¯†çš„å°åœ†ç‚¹ï¼‰ï¼Œå­˜åœ¨legendæ—¶ç”Ÿæ•ˆ
        itemStyle: {
            normal: {
                // color: å„å¼‚,
                borderColor: '#fff',
                borderWidth: 1,
                areaStyle: {
                    color: '#ccc'//rgba(135,206,250,0.8)
                },
                label: {
                    show: false,
                    textStyle: {
                        color: 'rgba(139,69,19,1)'
                    }
                }
            },
            emphasis: {                 // ä¹Ÿæ˜¯é€‰ä¸­æ ·å¼
                // color: å„å¼‚,
                borderColor: 'rgba(0,0,0,0)',
                borderWidth: 1,
                areaStyle: {
                    color: '#f3f39d'
                },
                label: {
                    show: false,
                    textStyle: {
                        color: 'rgba(139,69,19,1)'
                    }
                }
            }
        }
    },
    
    force : {
        itemStyle: {
            normal: {
                // color: å„å¼‚,
                label: {
                    show: false
                    // textStyle: null      // é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                },
                nodeStyle : {
                    brushType : 'both',
                    strokeColor : '#49b485'
                },
                linkStyle : {
                    strokeColor : '#49b485'
                }
            },
            emphasis: {
                // color: å„å¼‚,
                label: {
                    show: false
                    // textStyle: null      // é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                },
                nodeStyle : {},
                linkStyle : {}
            }
        }
    },

    gauge : {
        axisLine: {            // åæ ‡è½´çº¿
            show: true,        // é»˜è®¤æ˜¾ç¤ºï¼Œå±žæ€§showæŽ§åˆ¶æ˜¾ç¤ºä¸Žå¦
            lineStyle: {       // å±žæ€§lineStyleæŽ§åˆ¶çº¿æ¡æ ·å¼
                color: [[0.2, '#93bc9e'],[0.8, '#8aedd5'],[1, '#a6d7c2']], 
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
    }
});
                