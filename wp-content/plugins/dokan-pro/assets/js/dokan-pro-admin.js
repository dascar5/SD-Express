!function(e){var n={};function o(t){if(n[t])return n[t].exports;var r=n[t]={i:t,l:!1,exports:{}};return e[t].call(r.exports,r,r.exports,o),r.l=!0,r.exports}o.m=e,o.c=n,o.d=function(e,n,t){o.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:t})},o.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},o.t=function(e,n){if(1&n&&(e=o(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var t=Object.create(null);if(o.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var r in e)o.d(t,r,function(n){return e[n]}.bind(null,r));return t},o.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return o.d(n,"a",n),n},o.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},o.p="",o(o.s=37)}({37:function(e,n){var o,t;o=jQuery,t={init:function(){o(".dokan-modules").on("change","input.dokan-toggle-module",this.toggleModule)},toggleModule:function(e){var n=o(this);if(n.is(":checked"))var t=dokan_admin.activating,r={action:"dokan-toggle-module",type:"activate",module:n.closest("li").data("module"),nonce:dokan_admin.nonce};else t=dokan_admin.deactivating,r={action:"dokan-toggle-module",type:"deactivate",module:n.closest("li").data("module"),nonce:dokan_admin.nonce};n.closest(".plugin-card").block({message:t,overlayCSS:{background:"#222",opacity:.7},css:{fontSize:"19px",color:"#fff",border:"none",backgroundColor:"none",cursor:"wait"}}),wp.ajax.send("dokan-toggle-module",{data:r,success:function(e){},error:function(e){"plugin-exists"===e.error&&wp.ajax.send("dokan-toggle-module",{data:r})},complete:function(e){o(".blockMsg").text(e.data),setTimeout((function(){n.closest(".plugin-card").unblock()}),1e3)}})}},o(document).ready((function(){t.init()}))}});