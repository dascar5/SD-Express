!function(n){var o={};function e(t){if(o[t])return o[t].exports;var a=o[t]={i:t,l:!1,exports:{}};return n[t].call(a.exports,a,a.exports,e),a.l=!0,a.exports}e.m=n,e.c=o,e.d=function(n,o,t){e.o(n,o)||Object.defineProperty(n,o,{enumerable:!0,get:t})},e.r=function(n){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(n,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(n,"__esModule",{value:!0})},e.t=function(n,o){if(1&o&&(n=e(n)),8&o)return n;if(4&o&&"object"==typeof n&&n&&n.__esModule)return n;var t=Object.create(null);if(e.r(t),Object.defineProperty(t,"default",{enumerable:!0,value:n}),2&o&&"string"!=typeof n)for(var a in n)e.d(t,a,function(o){return n[o]}.bind(null,a));return t},e.n=function(n){var o=n&&n.__esModule?function(){return n.default}:function(){return n};return e.d(o,"a",o),o},e.o=function(n,o){return Object.prototype.hasOwnProperty.call(n,o)},e.p="",e(e.s=67)}({67:function(n,o,e){"use strict";e.r(o);var t;e(68);(t=jQuery)("#dokan-spmv-products-admin-assign-vendors").selectWoo({minimumInputLength:3,closeOnSelect:!1,ajax:{url:dokan_admin.ajaxurl,dataType:"json",delay:250,data:function(n){return{action:"dokan_spmv_products_admin_search_vendors",_wpnonce:dokan_admin.nonce,s:n.term,product_id:dokan_admin.dokanSPMVAdmin.product_id}},processResults:function(n,o){return o.page=o.page||1,{results:n.data.vendors,pagination:{more:!1}}},cache:!0},language:{errorLoading:function(){return dokan_admin.dokanSPMVAdmin.i18n.error_loading},searching:function(){return dokan_admin.dokanSPMVAdmin.i18n.searching+"..."},inputTooShort:function(){return dokan_admin.dokanSPMVAdmin.i18n.input_too_short+"..."}},escapeMarkup:function(n){return n},templateResult:function(n){return n.loading?n.text:"<div class='dokan-spmv-vendor-dropdown-results clearfix'><div class='dokan-spmv-vendor-dropdown-results__avatar'><img src='"+n.avatar+"' /></div><div class='dokan-spmv-vendor-dropdown-results__title'>"+n.name+"</div></div>"},templateSelection:function(n){return n.name}}),t("#dokan-spmv-products-admin-assign-vendors-btn").on("click",(function(n){n.preventDefault();var o=t(this),e=t("#dokan-spmv-products-admin-assign-vendors"),a=e.selectWoo("val");a&&a.length&&(o.prop("disabled",!0),e.prop("disabled",!0),t.ajax({url:dokan_admin.ajaxurl,method:"post",dataType:"json",data:{action:"dokan_spmv_products_admin_assign_vendors",_wpnonce:dokan_admin.nonce,product_id:dokan_admin.dokanSPMVAdmin.product_id,vendors:a}}).done((function(n){window.location.href=window.location.href})).always((function(){o.prop("disabled",!0),e.prop("disabled",!0)})))})),t("#dokan-spmv-products-admin .delete-product").on("click",(function(n){if(n.preventDefault(),confirm(dokan_admin.dokanSPMVAdmin.i18n.confirm_delete)){var o=t(this).data("product-id");t.ajax({url:dokan_admin.ajaxurl,method:"post",dataType:"json",data:{action:"dokan_spmv_products_admin_delete_clone_product",_wpnonce:dokan_admin.nonce,product_id:o}}).done((function(n){window.location.href=window.location.href}))}}))},68:function(n,o,e){}});