!function(k,C){var _=k.loco,i=_&&_.conf,u=document.getElementById("loco-editor-inner");if(_&&i&&u){var D,r,t,z,m,l,d=!!i.WP_DEBUG,s=_.po.ref&&_.po.ref.init(_,i),p=null,a=null,o=i.multipart,T=_.l10n,b=_.string.sprintf,I=i.locale,y=_.po.init(I).wrap(i.powrap),c=!I,n=document.getElementById("loco-actions"),f=i.popath,g=i.potpath,v=String(i.syncmode).toLowerCase(),e=document.getElementById("loco-fs"),h=e&&_.fs.init(e),w=i.readonly,x=!w,j={},S=0,M={my:"top",at:"top",of:"#loco-content"};!o||k.FormData&&k.Blob||(o=!1,_.notices.warn("Your browser doesn't support Ajax file uploads. Falling back to standard postdata")),s||_.notices.warn("admin.js is out of date. Please empty your browser cache and reload the page.");var L,E,U=(E=parseInt(C(u).css("min-height")||0),function(){var t=function(t,n){for(var o=t.offsetTop||0;(t=t.offsetParent)&&t!==n;)o+=t.offsetTop||0;return o}(u,document.body),n=k.innerHeight,o=Math.max(E,n-t-20);L!==o&&(u.style.height=String(o)+"px",L=o)});U(),C(k).resize(U),u.innerHTML="",D=_.po.ed.init(u).localise(T),_.po.kbd.init(D).add("save",x?function(){D.dirty&&H()}:Y).add("hint",I&&x&&F||Y).enable("copy","clear","enter","next","prev","fuzzy","save","invis","hint");var P={save:x&&function(n){function o(){n.disabled=!0}function t(){n.disabled=!1}function e(){t(),C(n).removeClass("loco-loading")}return n,D.on("poUnsaved",function(){t(),C(n).addClass("button-primary")}).on("poSave",function(){o(),C(n).removeClass("button-primary")}),a=C.extend({path:f},i.project||{}),C(n).click(function(t){return t.preventDefault(),o(),C(n).addClass("loco-loading"),H(e),!1}),!0},sync:x&&function(n){var t=i.project;if(t){function o(){n.disabled=!0}function e(){n.disabled=!1}function a(){e(),C(n).removeClass("loco-loading")}D.on("poUnsaved",function(){o()}).on("poSave",function(){e()}),p={bundle:t.bundle,domain:t.domain,type:c?"pot":"po",path:f||"",sync:g||"",strip:"pot"===v?"1":""},C(n).click(function(t){return t.preventDefault(),o(),C(n).addClass("loco-loading"),q(a),!1}),e()}return!0},revert:function(t){return D.on("poUnsaved",function(){t.disabled=!1}).on("poSave",function(){t.disabled=!0}),C(t).click(function(t){return t.preventDefault(),location.reload(),!1}),!0},invs:function(t){var o=C(t);return t.disabled=!1,D.on("poInvs",function(t,n){o[n?"addClass":"removeClass"]("inverted")}),o.click(function(t){return t.preventDefault(),D.setInvs(!D.getInvs()),!1}),_.tooltip.init(o),!0},code:function(t){var o=C(t);return t.disabled=!1,o.click(function(t){t.preventDefault();var n=!D.getMono();return o[n?"addClass":"removeClass"]("inverted"),D.setMono(n),!1}),_.tooltip.init(o),!0},source:W,binary:c?null:W};c?(P.add=x&&function(t){return t.disabled=!1,C(t).click(function(t){t.preventDefault();var n,o=1,e=/(\d+)$/;for(n="New message";y.get(n);)o=e.exec(n)?Math.max(o,RegExp.$1):o,n="New message "+ ++o;return D.add(n),!1}),!0},P.del=x&&function(t){return t.disabled=!1,C(t).click(function(t){return t.preventDefault(),D.del(),!1}),!0}):P.auto=function(t){function n(){t.disabled=!1}return D.on("poUnsaved",function(){t.disabled=!0}).on("poSave poAuto",function(){n()}),C(t).click(N),n(),!0},C("#loco-editor > nav .button").each(function(t,n){var o=n.getAttribute("data-loco"),e=P[o];e&&e(n,o)||C(n).addClass("loco-noop")}),C(n).submit(Y),function(n){function e(t){C(n.parentNode)[t||null==t?"removeClass":"addClass"]("invalid")}D.searchable(_.fulltext.init()),n.disabled=!1,n.value="";var a=_.watchtext(n,function(t){e(D.filter(t,!0))});D.on("poFilter",function(t,n,o){a.val(n||""),e(o)}).on("poMerge",function(t,n){var o=a.val();o&&D.filter(o)})}(document.getElementById("loco-search")),D.on("poUnsaved",function(){k.onbeforeunload=$}).on("poSave",function(){G(),k.onbeforeunload=null}).on("poHint",F).on("poUpdate",G).on("poMeta",function(t,n){var o,e,a=(e="CODE",(o=n).tagName===e?o:o.getElementsByTagName(e)[0]);return!a||!s||(s.load(a.textContent),t.preventDefault(),!1)}),y.load(i.podata),D.load(y),(I=D.targetLocale)?I.isRTL()&&C(u).addClass("trg-rtl"):D.unlock(),G(),delete _.conf,i=P=null}function q(c){_.ajax.post("sync",p,function(t){var n=[],o=t.pot,e=t.po,a=t.done||{add:[],del:[],fuz:[]},i=a.add.length,r=a.del.length,l=a.fuz.length,s=T;y.clear().load(e),D.load(y),i||r||l?(o?n.push(b(s._("Merged from %s"),o)):n.push(s._("Merged from source code")),i&&n.push(b(s._n("1 new string added","%s new strings added",i),i)),r&&n.push(b(s._n("1 obsolete string removed","%s obsolete strings removed",r),r)),l&&n.push(b(s._n("1 string marked Fuzzy","%s strings marked Fuzzy",l),l)),C(u).trigger("poUnsaved",[]),G(),d&&k.console&&function(t,n){var o=-1,e=n.add.length;for(;++o<e;)t.log(" + "+String(n.add[o]));for(e=n.del.length,o=0;o<e;o++)t.log(" - "+String(n.del[o]));for(e=n.fuz.length,o=0;o<e;o++)t.log(" ~ "+String(n.fuz[o]))}(console,a)):o?n.push(b(s._("Strings up to date with %s"),o)):n.push(s._("Strings up to date with source code")),_.notices.success(n.join(". ")),C(u).trigger("poMerge",[t]),c&&c()},c)}function B(){return t=t||function(){for(var t,n=-1,o=[],e=r,a=e.length;++n<a;)try{t=e[n],o.push(_.apis.create(t))}catch(t){_.notices.error(String(t))}return o}()}function O(e){var a;function i(){return(new Date).getTime()}c||w?_.notices.error("Logic error. APIs not available in current mode"):null==r||0===r.length||10<Math.round((i()-S)/1e3)?(l&&l.remove(),l=null,m&&m.remove(),m=null,z&&z.remove(),r=z=null,a=C('<div><div class="loco-loading"></div></div>').dialog({dialogClass:"loco-modal loco-modal-no-close",appendTo:"#loco-admin.wrap",title:"Loading..",modal:!0,autoOpen:!0,closeOnEscape:!1,resizable:!1,draggable:!1,position:M,height:200}),_.ajax.get("apis",{locale:String(I)},function(t,n,o){S=i(),0===(r=t&&t.apis||[]).length?l=A("loco-apis-empty",t.html):m=A("loco-apis-batch",t.html),a.remove(),e(r)})):(S=i(),e(r))}function A(t,n){var o=C(n);return o.attr("id",t),o.dialog({dialogClass:"loco-modal",appendTo:"#loco-admin.wrap",title:o.attr("title"),modal:!0,autoOpen:!1,closeOnEscape:!0,resizable:!1,draggable:!1,position:M}),o}function F(){O(function(t){t.length?function(){var p=T,t=D.current(),n=D.getTargetOffset(),o=t&&t.source(null,n),f='lang="'+String(I)+'" dir="'+(I.isRTL()?"RTL":"LTR")+'"',g=99;if(!o)return;function e(t){return!t.isDefaultPrevented()&&(!(0<=(n=t.which-49)&&n<10&&(o=h&&h.find("button.button-primary").eq(n))&&1===o.length)||(t.preventDefault(),t.stopPropagation(),o.click(),!1));var n,o}function a(t,n,o,e){var a=e.getId(),i=x[a],r=String(i+1),l=e.getUrl(),s=p._("Use this translation"),c=String(e),u=y&&y[a],d=C('<button class="button button-primary"></button>').attr("tabindex",String(1+g+i)).on("click",function(e,a){return function(t){t.preventDefault(),t.stopPropagation(),v();var n=D.current(),o=D.getTargetOffset();n&&n.source(null,o)===e?(n.translate(a,o),D.focus().reloadMessage(n)):_.notices.warn("Source changed since suggestion")}}(t,n));d.attr("accesskey",r),1<m.length&&(s+=" ("+r+")"),d.text(s),u&&u.replaceWith(C('<div class="loco-api loco-api-'+a+'"></div>').append(C('<a class="loco-api-credit" target="_blank" tabindex="-1"></a>').attr("href",l).text(c)).append(C("<blockquote "+f+"></blockquote>").text(n||"FAILED")).append(d)),++w===b&&(h&&h.dialog("option","title",p._("Suggested translations")+" — "+o.label),g+=w),0===i&&d.focus()}function v(t){h&&null==t&&h.dialog("close"),y=h=null,C(k).off("keydown",e)}function i(e){return function(t,n,o){a(t,u[e.getId()]=n,o,e)}}var h=(z=z||A("loco-apis-hint","<div></div>")).html("").append(C('<div class="loco-api"><p>Source text:</p></div>').append(C('<blockquote lang="en"></blockquote>').text(o))).dialog("option","title",p._("Loading suggestions")+"...").off("dialogclose").on("dialogclose",v).dialog("open"),r=t.translation(n);r&&C('<div class="loco-api"><p>Current translation:</p></div>').append(C("<blockquote "+f+"></blockquote>").text(r)).append(C('<button class="button"></button>').attr("tabindex",String(++g)).text(p._("Keep this translation")).on("click",function(t){t.preventDefault(),v()})).appendTo(h);var l,s,m=B(),b=m.length,c=-1,u=j[o]||(j[o]={}),y={},w=0,x={};for(;++c<b;)l=m[c],h.append((d=l,void 0,S=C('<div class="loco-api loco-api-loading"></div>').text("Calling "+d+" ..."),y[d.getId()]=S)),s=l.getId(),x[s]=c,u[s]?a(o,u[s],I,l):l.translate(o,I,i(l));var d,S;C(k).on("keydown",e)}():R()})}function N(t){return t.preventDefault(),O(function(t){t.length?function(){var e,a,i,r=0,l=T,n=!1,s=m.dialog("open"),t=s.find("form"),c=t.find("button.button-primary"),o=C("#loco-job-progress");function u(){c[0].disabled=!0}function d(){c.removeClass("loco-loading")}function p(t){o.text(t)}function f(t){var n=function(t){for(var n,o=B(),e=o.length,a=-1;++a<e;)if((n=o[a]).getId()===t)return n;_.notices.error("No "+t+" client")}(C(t.api).val()),o=t.existing.checked;p("Calculating...."),(e=n.createJob()).init(y,o),a=n.toString(),p(b(l._("%s unique source strings."),e.length.format(0))+" "+b(l._("%s characters will be sent for translation."),e.chars.format(0))),e.length?c[0].disabled=!1:u(),i=null}function g(t){e&&(n&&t.fuzzy(0,!0),D.pasteMessage(t),t===D.active&&D.setStatus(t),D.unsave(t,0),r++)}function v(t,n){var o=n?100*t/n:0;p(b(l._("Translation progress %s%%"),o.format(0)))}function h(){if(d(),e&&i){var t=i.todo();t&&_.notices.warn(b(l._n("Translation job aborted with one string remaining","Translation job aborted with %s strings remaining",t),t.format(0))).slow();var n=[],o=i.did();o&&n.push(b(l._n("%s string translated via %s","%s strings translated via %s",o),o.format(0),a)),r?n.push(b(l._n("%s string updated","%s strings updated",r),r.format(0))):n.push(l._("Nothing needed updating")),_.notices.success(n.join(". ")).slow(),i=e=null}r&&(G(),D.rebuildSearch()),s&&(s.off("dialogclose").dialog("close"),s=null),D.fire("poAuto")}d(),u(),_.notices.clear(),t.off("submit change"),f(t[0]),t.on("change",function(t){var n=t.target,o=n.name;return"api"!==o&&"existing"!==o||f(n.form),!0}).on("submit",function(t){t.preventDefault(),c.addClass("loco-loading"),u(),v(r=0),n=t.target.fuzzy.checked,i=e.dispatch().done(h).each(g).prog(v).stat()}),s.off("dialogclose").on("dialogclose",function(){e.abort(),s=null,h()})}():R()}),!1}function R(){l?l.dialog("open"):_.notices.error("Logic error. Unconfigured API modal missing")}function H(n){var t=C.extend({locale:String(y.locale()||"")},a||{});h&&h.applyCreds(t),o?(t=function(t){var n,o=new FormData;for(n in t)t.hasOwnProperty(n)&&o.append(n,t[n]);return o}(t)).append("po",new Blob([String(y)],{type:"application/x-gettext"}),String(t.path).split("/").pop()||"untitled.po"):t.data=String(y),_.ajax.post("save",t,function(t){n&&n(),D.save(!0),C("#loco-po-modified").text(t.datetime||"[datetime error]")},n)}function $(){return T._("Your changes will be lost if you continue without saving")}function W(e,a){return e.disabled=!1,C(e).click(function(t){var n=e.form,o=f;return"binary"===a&&(o=o.replace(/\.po$/,".mo")),n.path.value=o,n.source.value=y.toString(),!0}),!0}function Y(t){return t.preventDefault(),!1}function G(){var t=T,n=D.stats(),o=n.t,e=n.f,a=n.u,i=b(t._n("1 string","%s strings",o),o.format(0)),r=[];I&&(i=b(t._("%s%% translated"),n.p.replace("%",""))+", "+i,e&&r.push(b(t._("%s fuzzy"),e.format(0))),a&&r.push(b(t._("%s untranslated"),a.format(0))),r.length&&(i+=" ("+r.join(", ")+")")),C("#loco-po-status").text(i)}}(window,window.jQuery);