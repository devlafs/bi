define({
    // å…¨å›¾é»˜è®¤èƒŒæ™¯
    // backgroundColor: 'rgba(0,0,0,0)',
    
    // é»˜è®¤è‰²æ¿
    color: ['#ed9678','#e7dac9','#cb8e85','#f3f39d','#c8e49c',
            '#f16d7a','#f3d999','#d3758f','#dcc392','#2e4783',
            '#82b6e9','#ff6347','#a092f1','#0a915d','#eaf889',
            '#6699FF','#ff6666','#3cb371','#d5b158','#38b6b6'],
    
    // å€¼åŸŸ
    dataRange: {
        color:['#cb8e85','#e7dac9'],//é¢œè‰² 
        //text:['é«˜','ä½Ž'],         // æ–‡æœ¬ï¼Œé»˜è®¤ä¸ºæ•°å€¼æ–‡æœ¬
        textStyle: {
            color: '#333'          // å€¼åŸŸæ–‡å­—é¢œè‰²
        }
    },


    // æŸ±å½¢å›¾é»˜è®¤å‚æ•°
    bar: {
        barMinHeight: 0,          // æœ€å°é«˜åº¦æ”¹ä¸º0
        // barWidth: null,        // é»˜è®¤è‡ªé€‚åº”
        barGap: '30%',            // æŸ±é—´è·ç¦»ï¼Œé»˜è®¤ä¸ºæŸ±å½¢å®½åº¦çš„30%ï¼Œå¯è®¾å›ºå®šå€¼
        barCategoryGap : '20%',   // ç±»ç›®é—´æŸ±å½¢è·ç¦»ï¼Œé»˜è®¤ä¸ºç±»ç›®é—´è·çš„20%ï¼Œå¯è®¾å›ºå®šå€¼
        itemStyle: {
            normal: {
                // color: 'å„å¼‚',
                barBorderColor: '#fff',       // æŸ±æ¡è¾¹çº¿
                barBorderRadius: 0,           // æŸ±æ¡è¾¹çº¿åœ†è§’ï¼Œå•ä½pxï¼Œé»˜è®¤ä¸º0
                barBorderWidth: 1,            // æŸ±æ¡è¾¹çº¿çº¿å®½ï¼Œå•ä½pxï¼Œé»˜è®¤ä¸º1
                label: {
                    show: false
                    // position: é»˜è®¤è‡ªé€‚åº”ï¼Œæ°´å¹³å¸ƒå±€ä¸º'top'ï¼Œåž‚ç›´å¸ƒå±€ä¸º'right'ï¼Œå¯é€‰ä¸º
                    //           'inside'|'left'|'right'|'top'|'bottom'
                    // textStyle: null      // é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                }
            },
            emphasis: {
                // color: 'å„å¼‚',
                barBorderColor: 'rgba(0,0,0,0)',   // æŸ±æ¡è¾¹çº¿
                barBorderRadius: 0,                // æŸ±æ¡è¾¹çº¿åœ†è§’ï¼Œå•ä½pxï¼Œé»˜è®¤ä¸º0
                barBorderWidth: 1,                 // æŸ±æ¡è¾¹çº¿çº¿å®½ï¼Œå•ä½pxï¼Œé»˜è®¤ä¸º1
                label: {
                    show: false
                    // position: é»˜è®¤è‡ªé€‚åº”ï¼Œæ°´å¹³å¸ƒå±€ä¸º'top'ï¼Œåž‚ç›´å¸ƒå±€ä¸º'right'ï¼Œå¯é€‰ä¸º
                    //           'inside'|'left'|'right'|'top'|'bottom'
                    // textStyle: null      // é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                }
            }
        }
    },

    // æŠ˜çº¿å›¾é»˜è®¤å‚æ•°
    line: {
        itemStyle: {
            normal: {
                // color: å„å¼‚,
                label: {
                    show: false
                    // position: é»˜è®¤è‡ªé€‚åº”ï¼Œæ°´å¹³å¸ƒå±€ä¸º'top'ï¼Œåž‚ç›´å¸ƒå±€ä¸º'right'ï¼Œå¯é€‰ä¸º
                    //           'inside'|'left'|'right'|'top'|'bottom'
                    // textStyle: null      // é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                },
                lineStyle: {
                    width: 2,
                    type: 'solid',
                    shadowColor : 'rgba(0,0,0,0)', //é»˜è®¤é€æ˜Ž
                    shadowBlur: 5,
                    shadowOffsetX: 3,
                    shadowOffsetY: 3
                }
            },
            emphasis: {
                // color: å„å¼‚,
                label: {
                    show: false
                    // position: é»˜è®¤è‡ªé€‚åº”ï¼Œæ°´å¹³å¸ƒå±€ä¸º'top'ï¼Œåž‚ç›´å¸ƒå±€ä¸º'right'ï¼Œå¯é€‰ä¸º
                    //           'inside'|'left'|'right'|'top'|'bottom'
                    // textStyle: null      // é»˜è®¤ä½¿ç”¨å…¨å±€æ–‡æœ¬æ ·å¼ï¼Œè¯¦è§TEXTSTYLE
                }
            }
        },
        //smooth : false,
        //symbol: null,         // æ‹ç‚¹å›¾å½¢ç±»åž‹
        symbolSize: 2,          // æ‹ç‚¹å›¾å½¢å¤§å°
        //symbolRotate : null,  // æ‹ç‚¹å›¾å½¢æ—‹è½¬æŽ§åˆ¶
        showAllSymbol: false    // æ ‡å¿—å›¾å½¢é»˜è®¤åªæœ‰ä¸»è½´æ˜¾ç¤ºï¼ˆéšä¸»è½´æ ‡ç­¾é—´éš”éšè—ç­–ç•¥ï¼‰
    },
    
    // Kçº¿å›¾é»˜è®¤å‚æ•°
    k: {
        // barWidth : null          // é»˜è®¤è‡ªé€‚åº”
        // barMaxWidth : null       // é»˜è®¤è‡ªé€‚åº” 
        itemStyle: {
            normal: {
                color: '#fe9778',          // é˜³çº¿å¡«å……é¢œè‰²
                color0: '#e7dac9',      // é˜´çº¿å¡«å……é¢œè‰²
                lineStyle: {
                    width: 1,
                    color: '#f78766',   // é˜³çº¿è¾¹æ¡†é¢œè‰²
                    color0: '#f1ccb8'   // é˜´çº¿è¾¹æ¡†é¢œè‰²
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
        center : ['50%', '50%'],    // é»˜è®¤å…¨å±€å±…ä¸­
        radius : [0, '75%'],
        clockWise : false,          // é»˜è®¤é€†æ—¶é’ˆ
        startAngle: 90,
        minAngle: 0,                // æœ€å°è§’åº¦æ”¹ä¸º0
        selectedOffset: 10,         // é€‰ä¸­æ˜¯æ‰‡åŒºåç§»é‡
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
                    strokeColor : '#a17e6e'
                },
                linkStyle : {
                    strokeColor : '#a17e6e'
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
                color: [[0.2, '#ed9678'],[0.8, '#e7dac9'],[1, '#cb8e85']], 
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
                