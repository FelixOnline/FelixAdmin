// http://mottie.github.io/tablesorter/docs/index.html#Download

!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof module&&"object"==typeof module.exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){return function(a){"use strict";a.extend({tablesorter:new function(){function b(a){for(var b in a)return!1;return!0}function c(b,c,d,e){for(var f,g,h=s.parsers.length,i=!1,j="",k=!0;""===j&&k;)d++,c[d]?(i=c[d].cells[e],j=s.getElementText(b,i,e),g=a(i),b.debug&&console.log("Checking if value was empty on row "+d+", column: "+e+': "'+j+'"')):k=!1;for(;--h>=0;)if(f=s.parsers[h],f&&"text"!==f.id&&f.is&&f.is(j,b.table,i,g))return f;return s.getParserById("text")}function d(a,d){var e,f,g,h,i,j,k,l,m,n,o,p,q=a.table,r=0,t={};if(a.$tbodies=a.$table.children("tbody:not(."+a.cssInfoBlock+")"),o="undefined"==typeof d?a.$tbodies:d,p=o.length,0===p)return a.debug?console.warn("Warning: *Empty table!* Not building a parser cache"):"";for(a.debug&&(n=new Date,console[console.group?"group":"log"]("Detecting parsers for each column")),f={extractors:[],parsers:[]};p>r;){if(e=o[r].rows,e.length)for(g=a.columns,h=0;g>h;h++)i=a.$headerIndexed[h],j=s.getColumnData(q,a.headers,h),m=s.getParserById(s.getData(i,j,"extractor")),l=s.getParserById(s.getData(i,j,"sorter")),k="false"===s.getData(i,j,"parser"),a.empties[h]=(s.getData(i,j,"empty")||a.emptyTo||(a.emptyToBottom?"bottom":"top")).toLowerCase(),a.strings[h]=(s.getData(i,j,"string")||a.stringTo||"max").toLowerCase(),k&&(l=s.getParserById("no-parser")),m||(m=!1),l||(l=c(a,e,-1,h)),a.debug&&(t["("+h+") "+i.text()]={parser:l.id,extractor:m?m.id:"none",string:a.strings[h],empty:a.empties[h]}),f.parsers[h]=l,f.extractors[h]=m;r+=f.parsers.length?p:1}a.debug&&(b(t)?console.warn("  No parsers detected!"):console[console.table?"table":"log"](t),console.log("Completed detecting parsers"+s.benchmark(n)),console.groupEnd&&console.groupEnd()),a.parsers=f.parsers,a.extractors=f.extractors}function e(b,c){var d,e,f,g,h,i,j,k,l,m,n,o,p,q,r=b.config,t=r.parsers;if(r.$tbodies=r.$table.children("tbody:not(."+r.cssInfoBlock+")"),j="undefined"==typeof c?r.$tbodies:c,r.cache={},r.totalRows=0,!t)return r.debug?console.warn("Warning: *Empty table!* Not building a cache"):"";for(r.debug&&(m=new Date),r.showProcessing&&s.isProcessing(b,!0),i=0;i<j.length;i++){for(q=[],d=r.cache[i]={normalized:[]},n=j[i]&&j[i].rows.length||0,g=0;n>g;++g)if(o={child:[],raw:[]},k=a(j[i].rows[g]),l=[],k.hasClass(r.cssChildRow)&&0!==g)for(e=d.normalized.length-1,p=d.normalized[e][r.columns],p.$row=p.$row.add(k),k.prev().hasClass(r.cssChildRow)||k.prev().addClass(s.css.cssHasChild),f=k.children("th, td"),e=p.child.length,p.child[e]=[],h=0;h<r.columns;h++)p.child[e][h]=s.getParsedText(r,f[h],h);else{for(o.$row=k,o.order=g,h=0;h<r.columns;++h)"undefined"!=typeof t[h]?(e=s.getElementText(r,k[0].cells[h],h),o.raw.push(e),f=s.getParsedText(r,k[0].cells[h],h,e),l.push(f),"numeric"===(t[h].type||"").toLowerCase()&&(q[h]=Math.max(Math.abs(f)||0,q[h]||0))):r.debug&&console.warn("No parser found for cell:",k[0].cells[h],"does it have a header?");l[r.columns]=o,d.normalized.push(l)}d.colMax=q,r.totalRows+=d.normalized.length}r.showProcessing&&s.isProcessing(b),r.debug&&console.log("Building cache for "+n+" rows"+s.benchmark(m))}function f(a,c){var d,e,f,g,h,i,j,k=a.config,l=k.widgetOptions,m=k.$tbodies,n=[],o=k.cache;if(b(o))return k.appender?k.appender(a,n):a.isUpdating?k.$table.trigger("updateComplete",a):"";for(k.debug&&(j=new Date),i=0;i<m.length;i++)if(f=m.eq(i),f.length){for(g=s.processTbody(a,f,!0),d=o[i].normalized,e=d.length,h=0;e>h;h++)n.push(d[h][k.columns].$row),k.appender&&(!k.pager||k.pager.removeRows&&l.pager_removeRows||k.pager.ajax)||g.append(d[h][k.columns].$row);s.processTbody(a,g,!1)}k.appender&&k.appender(a,n),k.debug&&console.log("Rebuilt table"+s.benchmark(j)),c||k.appender||s.applyWidget(a),a.isUpdating&&k.$table.trigger("updateComplete",a)}function g(a){return/^d/i.test(a)||1===a}function h(b){var c,d,e,f,h,i,k,l,m=b.config;for(m.headerList=[],m.headerContent=[],m.debug&&(k=new Date),m.columns=s.computeColumnIndex(m.$table.children("thead, tfoot").children("tr")),f=m.cssIcon?'<i class="'+(m.cssIcon===s.css.icon?s.css.icon:m.cssIcon+" "+s.css.icon)+'"></i>':"",m.$headers=a(a.map(a(b).find(m.selectorHeaders),function(j,k){return d=a(j),d.parent().hasClass(m.cssIgnoreRow)?void 0:(c=s.getColumnData(b,m.headers,k,!0),m.headerContent[k]=d.html(),""===m.headerTemplate||d.find("."+s.css.headerIn).length||(h=m.headerTemplate.replace(/\{content\}/g,d.html()).replace(/\{icon\}/g,d.find("."+s.css.icon).length?"":f),m.onRenderTemplate&&(e=m.onRenderTemplate.apply(d,[k,h]),e&&"string"==typeof e&&(h=e)),d.html('<div class="'+s.css.headerIn+'">'+h+"</div>")),m.onRenderHeader&&m.onRenderHeader.apply(d,[k,m,m.$table]),j.column=parseInt(d.attr("data-column"),10),j.order=g(s.getData(d,c,"sortInitialOrder")||m.sortInitialOrder)?[1,0,2]:[0,1,2],j.count=-1,j.lockedOrder=!1,i=s.getData(d,c,"lockedOrder")||!1,"undefined"!=typeof i&&i!==!1&&(j.order=j.lockedOrder=g(i)?[1,1,1]:[0,0,0]),d.addClass(s.css.header+" "+m.cssHeader),m.headerList[k]=j,d.parent().addClass(s.css.headerRow+" "+m.cssHeaderRow).attr("role","row"),m.tabIndex&&d.attr("tabindex",0),j)})),m.$headerIndexed=[],l=0;l<m.columns;l++)d=m.$headers.filter('[data-column="'+l+'"]'),m.$headerIndexed[l]=d.not(".sorter-false").length?d.not(".sorter-false").filter(":last"):d.filter(":last");a(b).find(m.selectorHeaders).attr({scope:"col",role:"columnheader"}),j(b),m.debug&&(console.log("Built headers:"+s.benchmark(k)),console.log(m.$headers))}function i(a,b,c){var f=a.config;f.$table.find(f.selectorRemove).remove(),d(f),e(a),q(f,b,c)}function j(a){var b,c,d,e,f=a.config,g=f.$headers.length;for(b=0;g>b;b++)d=f.$headers.eq(b),e=s.getColumnData(a,f.headers,b,!0),c="false"===s.getData(d,e,"sorter")||"false"===s.getData(d,e,"parser"),d[0].sortDisabled=c,d[c?"addClass":"removeClass"]("sorter-false").attr("aria-disabled",""+c),a.id&&(c?d.removeAttr("aria-controls"):d.attr("aria-controls",a.id))}function k(b){var c,d,e,f,g,h,i,j,k=b.config,l=k.sortList,m=l.length,n=s.css.sortNone+" "+k.cssNone,o=[s.css.sortAsc+" "+k.cssAsc,s.css.sortDesc+" "+k.cssDesc],p=[k.cssIconAsc,k.cssIconDesc,k.cssIconNone],q=["ascending","descending"],r=a(b).find("tfoot tr").children().add(a(k.namespace+"_extra_headers")).removeClass(o.join(" "));for(k.$headers.removeClass(o.join(" ")).addClass(n).attr("aria-sort","none").find("."+s.css.icon).removeClass(p.join(" ")).addClass(p[2]),e=0;m>e;e++)if(2!==l[e][1]&&(c=k.$headers.not(".sorter-false").filter('[data-column="'+l[e][0]+'"]'+(1===m?":last":"")),c.length)){for(f=0;f<c.length;f++)c[f].sortDisabled||c.eq(f).removeClass(n).addClass(o[l[e][1]]).attr("aria-sort",q[l[e][1]]).find("."+s.css.icon).removeClass(p[2]).addClass(p[l[e][1]]);r.length&&r.filter('[data-column="'+l[e][0]+'"]').removeClass(n).addClass(o[l[e][1]])}for(m=k.$headers.length,g=k.$headers.not(".sorter-false"),e=0;m>e;e++)h=g.eq(e),h.length&&(d=g[e],i=d.order[(d.count+1)%(k.sortReset?3:2)],j=a.trim(h.text())+": "+s.language[h.hasClass(s.css.sortAsc)?"sortAsc":h.hasClass(s.css.sortDesc)?"sortDesc":"sortNone"]+s.language[0===i?"nextAsc":1===i?"nextDesc":"nextNone"],h.attr("aria-label",j))}function l(b,c){var d,e,f,g,h,i,j,k,l=b.config,m=c||l.sortList,n=m.length;for(l.sortList=[],h=0;n>h;h++)if(k=m[h],d=parseInt(k[0],10),d<l.columns&&l.$headerIndexed[d]){switch(g=l.$headerIndexed[d][0],e=(""+k[1]).match(/^(1|d|s|o|n)/),e=e?e[0]:""){case"1":case"d":e=1;break;case"s":e=i||0;break;case"o":j=g.order[(i||0)%(l.sortReset?3:2)],e=0===j?1:1===j?0:2;break;case"n":g.count=g.count+1,e=g.order[g.count%(l.sortReset?3:2)];break;default:e=0}i=0===h?e:i,f=[d,parseInt(e,10)||0],l.sortList.push(f),e=a.inArray(f[1],g.order),g.count=e>=0?e:f[1]%(l.sortReset?3:2)}}function m(a,b){return a&&a[b]?a[b].type||"":""}function n(b,c,d){if(b.isUpdating)return setTimeout(function(){n(b,c,d)},50);var e,g,h,i,j,l,m,p=b.config,q=!d[p.sortMultiSortKey],r=p.$table,t=p.$headers.length;if(r.trigger("sortStart",b),c.count=d[p.sortResetKey]?2:(c.count+1)%(p.sortReset?3:2),p.sortRestart)for(g=c,h=0;t>h;h++)m=p.$headers.eq(h),m[0]===g||!q&&m.is("."+s.css.sortDesc+",."+s.css.sortAsc)||(m[0].count=-1);if(g=parseInt(a(c).attr("data-column"),10),q){if(p.sortList=[],null!==p.sortForce)for(e=p.sortForce,i=0;i<e.length;i++)e[i][0]!==g&&p.sortList.push(e[i]);if(j=c.order[c.count],2>j&&(p.sortList.push([g,j]),c.colSpan>1))for(i=1;i<c.colSpan;i++)p.sortList.push([g+i,j])}else{if(p.sortAppend&&p.sortList.length>1)for(i=0;i<p.sortAppend.length;i++)l=s.isValueInArray(p.sortAppend[i][0],p.sortList),l>=0&&p.sortList.splice(l,1);if(s.isValueInArray(g,p.sortList)>=0)for(i=0;i<p.sortList.length;i++)l=p.sortList[i],j=p.$headerIndexed[l[0]][0],l[0]===g&&(l[1]=j.order[c.count],2===l[1]&&(p.sortList.splice(i,1),j.count=-1));else if(j=c.order[c.count],2>j&&(p.sortList.push([g,j]),c.colSpan>1))for(i=1;i<c.colSpan;i++)p.sortList.push([g+i,j])}if(null!==p.sortAppend)for(e=p.sortAppend,i=0;i<e.length;i++)e[i][0]!==g&&p.sortList.push(e[i]);r.trigger("sortBegin",b),setTimeout(function(){k(b),o(b),f(b),r.trigger("sortEnd",b)},1)}function o(a){var c,d,e,f,g,h,i,j,k,l,n,o=0,p=a.config,q=p.textSorter||"",r=p.sortList,t=r.length,u=p.$tbodies.length;if(!p.serverSideSorting&&!b(p.cache)){for(p.debug&&(g=new Date),d=0;u>d;d++)h=p.cache[d].colMax,i=p.cache[d].normalized,i.sort(function(b,d){for(c=0;t>c;c++){if(f=r[c][0],j=r[c][1],o=0===j,p.sortStable&&b[f]===d[f]&&1===t)return b[p.columns].order-d[p.columns].order;if(e=/n/i.test(m(p.parsers,f)),e&&p.strings[f]?(e="boolean"==typeof p.string[p.strings[f]]?(o?1:-1)*(p.string[p.strings[f]]?-1:1):p.strings[f]?p.string[p.strings[f]]||0:0,k=p.numberSorter?p.numberSorter(b[f],d[f],o,h[f],a):s["sortNumeric"+(o?"Asc":"Desc")](b[f],d[f],e,h[f],f,a)):(l=o?b:d,n=o?d:b,k="function"==typeof q?q(l[f],n[f],o,f,a):"object"==typeof q&&q.hasOwnProperty(f)?q[f](l[f],n[f],o,f,a):s["sortNatural"+(o?"Asc":"Desc")](b[f],d[f],f,a,p)),k)return k}return b[p.columns].order-d[p.columns].order});p.debug&&console.log("Sorting on "+r.toString()+" and dir "+j+" time"+s.benchmark(g))}}function p(b,c){b.table.isUpdating&&b.$table.trigger("updateComplete",b.table),a.isFunction(c)&&c(b.table)}function q(b,c,d){var e=a.isArray(c)?c:b.sortList,f="undefined"==typeof c?b.resort:c;f===!1||b.serverSideSorting||b.table.isProcessing?(p(b,d),s.applyWidget(b.table,!1)):e.length?b.$table.trigger("sorton",[e,function(){p(b,d)},!0]):b.$table.trigger("sortReset",[function(){p(b,d),s.applyWidget(b.table,!1)}])}function r(c){var g=c.config,m=g.$table,n="sortReset update updateRows updateCell updateAll addRows updateComplete sorton appendCache updateCache applyWidgetId applyWidgets refreshWidgets destroy mouseup mouseleave ".split(" ").join(g.namespace+" ");m.unbind(n.replace(/\s+/g," ")).bind("sortReset"+g.namespace,function(b,d){b.stopPropagation(),g.sortList=[],k(c),o(c),f(c),a.isFunction(d)&&d(c)}).bind("updateAll"+g.namespace,function(a,b,d){a.stopPropagation(),c.isUpdating=!0,s.refreshWidgets(c,!0,!0),h(c),s.bindEvents(c,g.$headers,!0),r(c),i(c,b,d)}).bind("update"+g.namespace+" updateRows"+g.namespace,function(a,b,d){a.stopPropagation(),c.isUpdating=!0,j(c),i(c,b,d)}).bind("updateCell"+g.namespace,function(b,d,e,f){b.stopPropagation(),c.isUpdating=!0,m.find(g.selectorRemove).remove();var h,i,j,k,l=g.$tbodies,n=a(d),o=l.index(a.fn.closest?n.closest("tbody"):n.parents("tbody").filter(":first")),p=g.cache[o],r=a.fn.closest?n.closest("tr"):n.parents("tr").filter(":first");d=n[0],l.length&&o>=0&&(i=l.eq(o).find("tr").index(r),k=p.normalized[i],j=n.index(),h=s.getParsedText(g,d,j),k[j]=h,k[g.columns].$row=r,"numeric"===(g.parsers[j].type||"").toLowerCase()&&(p.colMax[j]=Math.max(Math.abs(h)||0,p.colMax[j]||0)),h="undefined"!==e?e:g.resort,h!==!1?q(g,h,f):(a.isFunction(f)&&f(c),g.$table.trigger("updateComplete",g.table)))}).bind("addRows"+g.namespace,function(e,f,h,k){if(e.stopPropagation(),c.isUpdating=!0,b(g.cache))j(c),i(c,h,k);else{f=a(f).attr("role","row");var l,m,n,o,p,r=f.filter("tr").length,t=g.$tbodies.index(f.parents("tbody").filter(":first"));for(g.parsers&&g.parsers.length||d(g),l=0;r>l;l++){for(n=f[l].cells.length,p=[],o={child:[],$row:f.eq(l),order:g.cache[t].normalized.length},m=0;n>m;m++)p[m]=s.getParsedText(g,f[l].cells[m],m),"numeric"===(g.parsers[m].type||"").toLowerCase()&&(g.cache[t].colMax[m]=Math.max(Math.abs(p[m])||0,g.cache[t].colMax[m]||0));p.push(o),g.cache[t].normalized.push(p)}q(g,h,k)}}).bind("updateComplete"+g.namespace,function(){c.isUpdating=!1}).bind("sorton"+g.namespace,function(d,g,h,i){var j=c.config;d.stopPropagation(),m.trigger("sortStart",this),l(c,g),k(c),j.delayInit&&b(j.cache)&&e(c),m.trigger("sortBegin",this),o(c),f(c,i),m.trigger("sortEnd",this),s.applyWidget(c),a.isFunction(h)&&h(c)}).bind("appendCache"+g.namespace,function(b,d,e){b.stopPropagation(),f(c,e),a.isFunction(d)&&d(c)}).bind("updateCache"+g.namespace,function(b,f,h){g.parsers&&g.parsers.length||d(g,h),e(c,h),a.isFunction(f)&&f(c)}).bind("applyWidgetId"+g.namespace,function(a,b){a.stopPropagation(),s.getWidgetById(b).format(c,g,g.widgetOptions)}).bind("applyWidgets"+g.namespace,function(a,b){a.stopPropagation(),s.applyWidget(c,b)}).bind("refreshWidgets"+g.namespace,function(a,b,d){a.stopPropagation(),s.refreshWidgets(c,b,d)}).bind("destroy"+g.namespace,function(a,b,d){a.stopPropagation(),s.destroy(c,b,d)}).bind("resetToLoadState"+g.namespace,function(){s.removeWidget(c,!0,!1),g=a.extend(!0,s.defaults,g.originalSettings),c.hasInitialized=!1,s.setup(c,g)})}var s=this;s.version="2.22.5",s.parsers=[],s.widgets=[],s.defaults={theme:"default",widthFixed:!1,showProcessing:!1,headerTemplate:"{content}",onRenderTemplate:null,onRenderHeader:null,cancelSelection:!0,tabIndex:!0,dateFormat:"mmddyyyy",sortMultiSortKey:"shiftKey",sortResetKey:"ctrlKey",usNumberFormat:!0,delayInit:!1,serverSideSorting:!1,resort:!0,headers:{},ignoreCase:!0,sortForce:null,sortList:[],sortAppend:null,sortStable:!1,sortInitialOrder:"asc",sortLocaleCompare:!1,sortReset:!1,sortRestart:!1,emptyTo:"bottom",stringTo:"max",textExtraction:"basic",textAttribute:"data-text",textSorter:null,numberSorter:null,widgets:[],widgetOptions:{zebra:["even","odd"]},initWidgets:!0,widgetClass:"widget-{name}",initialized:null,tableClass:"",cssAsc:"",cssDesc:"",cssNone:"",cssHeader:"",cssHeaderRow:"",cssProcessing:"",cssChildRow:"tablesorter-childRow",cssIcon:"tablesorter-icon",cssIconNone:"",cssIconAsc:"",cssIconDesc:"",cssInfoBlock:"tablesorter-infoOnly",cssNoSort:"tablesorter-noSort",cssIgnoreRow:"tablesorter-ignoreRow",pointerClick:"click",pointerDown:"mousedown",pointerUp:"mouseup",selectorHeaders:"> thead th, > thead td",selectorSort:"th, td",selectorRemove:".remove-me",debug:!1,headerList:[],empties:{},strings:{},parsers:[]},s.css={table:"tablesorter",cssHasChild:"tablesorter-hasChildRow",childRow:"tablesorter-childRow",colgroup:"tablesorter-colgroup",header:"tablesorter-header",headerRow:"tablesorter-headerRow",headerIn:"tablesorter-header-inner",icon:"tablesorter-icon",processing:"tablesorter-processing",sortAsc:"tablesorter-headerAsc",sortDesc:"tablesorter-headerDesc",sortNone:"tablesorter-headerUnSorted"},s.language={sortAsc:"Ascending sort applied, ",sortDesc:"Descending sort applied, ",sortNone:"No sort applied, ",nextAsc:"activate to apply an ascending sort",nextDesc:"activate to apply a descending sort",nextNone:"activate to remove the sort"},s.instanceMethods={},s.getElementText=function(b,c,d){if(!c)return"";var e,f=b.textExtraction||"",g=c.jquery?c:a(c);return"string"==typeof f?"basic"===f&&"undefined"!=typeof(e=g.attr(b.textAttribute))?a.trim(e):a.trim(c.textContent||g.text()):"function"==typeof f?a.trim(f(g[0],b.table,d)):"function"==typeof(e=s.getColumnData(b.table,f,d))?a.trim(e(g[0],b.table,d)):a.trim(g[0].textContent||g.text())},s.getParsedText=function(a,b,c,d){"undefined"==typeof d&&(d=s.getElementText(a,b,c));var e=""+d,f=a.parsers[c],g=a.extractors[c];return f&&(g&&"function"==typeof g.format&&(d=g.format(d,a.table,b,c)),e="no-parser"===f.id?"":f.format(""+d,a.table,b,c),a.ignoreCase&&"string"==typeof e&&(e=e.toLowerCase())),e},s.construct=function(b){return this.each(function(){var c=this,d=a.extend(!0,{},s.defaults,b,s.instanceMethods);d.originalSettings=b,!c.hasInitialized&&s.buildTable&&"TABLE"!==this.nodeName?s.buildTable(c,d):s.setup(c,d)})},s.setup=function(b,c){if(!b||!b.tHead||0===b.tBodies.length||b.hasInitialized===!0)return void(c.debug&&(b.hasInitialized?console.warn("Stopping initialization. Tablesorter has already been initialized"):console.error("Stopping initialization! No table, thead or tbody")));var f="",g=a(b),i=a.metadata;b.hasInitialized=!1,b.isProcessing=!0,b.config=c,a.data(b,"tablesorter",c),c.debug&&(console[console.group?"group":"log"]("Initializing tablesorter"),a.data(b,"startoveralltimer",new Date)),c.supportsDataObject=function(a){return a[0]=parseInt(a[0],10),a[0]>1||1===a[0]&&parseInt(a[1],10)>=4}(a.fn.jquery.split(".")),c.string={max:1,min:-1,emptymin:1,emptymax:-1,zero:0,none:0,"null":0,top:!0,bottom:!1},c.emptyTo=c.emptyTo.toLowerCase(),c.stringTo=c.stringTo.toLowerCase(),/tablesorter\-/.test(g.attr("class"))||(f=""!==c.theme?" tablesorter-"+c.theme:""),c.table=b,c.$table=g.addClass(s.css.table+" "+c.tableClass+f).attr("role","grid"),c.$headers=g.find(c.selectorHeaders),c.namespace?c.namespace="."+c.namespace.replace(/\W/g,""):c.namespace=".tablesorter"+Math.random().toString(16).slice(2),c.$table.children().children("tr").attr("role","row"),c.$tbodies=g.children("tbody:not(."+c.cssInfoBlock+")").attr({"aria-live":"polite","aria-relevant":"all"}),c.$table.children("caption").length&&(f=c.$table.children("caption")[0],f.id||(f.id=c.namespace.slice(1)+"caption"),c.$table.attr("aria-labelledby",f.id)),c.widgetInit={},c.textExtraction=c.$table.attr("data-text-extraction")||c.textExtraction||"basic",h(b),s.fixColumnWidth(b),s.applyWidgetOptions(b,c),d(c),c.totalRows=0,c.delayInit||e(b),s.bindEvents(b,c.$headers,!0),r(b),c.supportsDataObject&&"undefined"!=typeof g.data().sortlist?c.sortList=g.data().sortlist:i&&g.metadata()&&g.metadata().sortlist&&(c.sortList=g.metadata().sortlist),s.applyWidget(b,!0),c.sortList.length>0?g.trigger("sorton",[c.sortList,{},!c.initWidgets,!0]):(k(b),c.initWidgets&&s.applyWidget(b,!1)),c.showProcessing&&g.unbind("sortBegin"+c.namespace+" sortEnd"+c.namespace).bind("sortBegin"+c.namespace+" sortEnd"+c.namespace,function(a){clearTimeout(c.processTimer),s.isProcessing(b),"sortBegin"===a.type&&(c.processTimer=setTimeout(function(){s.isProcessing(b,!0)},500))}),b.hasInitialized=!0,b.isProcessing=!1,c.debug&&(console.log("Overall initialization time: "+s.benchmark(a.data(b,"startoveralltimer"))),c.debug&&console.groupEnd&&console.groupEnd()),g.trigger("tablesorter-initialized",b),"function"==typeof c.initialized&&c.initialized(b)},s.fixColumnWidth=function(b){b=a(b)[0];var c,d,e,f,g,h=b.config,i=h.$table.children("colgroup");if(i.length&&i.hasClass(s.css.colgroup)&&i.remove(),h.widthFixed&&0===h.$table.children("colgroup").length){for(i=a('<colgroup class="'+s.css.colgroup+'">'),c=h.$table.width(),e=h.$tbodies.find("tr:first").children(":visible"),f=e.length,g=0;f>g;g++)d=parseInt(e.eq(g).width()/c*1e3,10)/10+"%",i.append(a("<col>").css("width",d));h.$table.prepend(i)}},s.getColumnData=function(b,c,d,e,f){if("undefined"!=typeof c&&null!==c){b=a(b)[0];var g,h,i=b.config,j=f||i.$headers,k=i.$headerIndexed&&i.$headerIndexed[d]||j.filter('[data-column="'+d+'"]:last');if(c[d])return e?c[d]:c[j.index(k)];for(h in c)if("string"==typeof h&&(g=k.filter(h).add(k.find(h)),g.length))return c[h]}},s.computeColumnIndex=function(b){var c,d,e,f,g,h,i,j,k,l,m,n,o=[],p=[],q={};for(c=0;c<b.length;c++)for(i=b[c].cells,d=0;d<i.length;d++){for(h=i[d],g=a(h),j=h.parentNode.rowIndex,k=j+"-"+g.index(),l=h.rowSpan||1,m=h.colSpan||1,"undefined"==typeof o[j]&&(o[j]=[]),e=0;e<o[j].length+1;e++)if("undefined"==typeof o[j][e]){n=e;break}for(q[k]=n,g.attr({"data-column":n}),e=j;j+l>e;e++)for("undefined"==typeof o[e]&&(o[e]=[]),p=o[e],f=n;n+m>f;f++)p[f]="x"}return p.length},s.isProcessing=function(b,c,d){b=a(b);var e=b[0].config,f=d||b.find("."+s.css.header);c?("undefined"!=typeof d&&e.sortList.length>0&&(f=f.filter(function(){return this.sortDisabled?!1:s.isValueInArray(parseFloat(a(this).attr("data-column")),e.sortList)>=0})),b.add(f).addClass(s.css.processing+" "+e.cssProcessing)):b.add(f).removeClass(s.css.processing+" "+e.cssProcessing)},s.processTbody=function(b,c,d){b=a(b)[0];var e;return d?(b.isProcessing=!0,c.before('<colgroup class="tablesorter-savemyplace"/>'),e=a.fn.detach?c.detach():c.remove()):(e=a(b).find("colgroup.tablesorter-savemyplace"),c.insertAfter(e),e.remove(),void(b.isProcessing=!1))},s.clearTableBody=function(b){a(b)[0].config.$tbodies.children().detach()},s.bindEvents=function(c,d,f){c=a(c)[0];var g,h=null,i=c.config;f!==!0&&(d.addClass(i.namespace.slice(1)+"_extra_headers"),g=a.fn.closest?d.closest("table")[0]:d.parents("table")[0],g&&"TABLE"===g.nodeName&&g!==c&&a(g).addClass(i.namespace.slice(1)+"_extra_table")),g=(i.pointerDown+" "+i.pointerUp+" "+i.pointerClick+" sort keyup ").replace(/\s+/g," ").split(" ").join(i.namespace+" "),d.find(i.selectorSort).add(d.filter(i.selectorSort)).unbind(g).bind(g,function(f,g){var j,k,l=a(f.target),m=" "+f.type+" ";if(!(1!==(f.which||f.button)&&!m.match(" "+i.pointerClick+" | sort | keyup ")||" keyup "===m&&13!==f.which||m.match(" "+i.pointerClick+" ")&&"undefined"!=typeof f.which||m.match(" "+i.pointerUp+" ")&&h!==f.target&&g!==!0)){if(m.match(" "+i.pointerDown+" "))return h=f.target,k=l.jquery.split("."),void("1"===k[0]&&k[1]<4&&f.preventDefault());if(h=null,/(input|select|button|textarea)/i.test(f.target.nodeName)||l.hasClass(i.cssNoSort)||l.parents("."+i.cssNoSort).length>0||l.parents("button").length>0)return!i.cancelSelection;i.delayInit&&b(i.cache)&&e(c),j=a.fn.closest?a(this).closest("th, td")[0]:/TH|TD/.test(this.nodeName)?this:a(this).parents("th, td")[0],j=i.$headers[d.index(j)],j.sortDisabled||n(c,j,f)}}),i.cancelSelection&&d.attr("unselectable","on").bind("selectstart",!1).css({"user-select":"none",MozUserSelect:"none"})},s.restoreHeaders=function(b){var c,d,e=a(b)[0].config,f=e.$table.find(e.selectorHeaders),g=f.length;for(c=0;g>c;c++)d=f.eq(c),d.find("."+s.css.headerIn).length&&d.html(e.headerContent[c])},s.destroy=function(b,c,d){if(b=a(b)[0],b.hasInitialized){s.removeWidget(b,!0,!1);var e,f=a(b),g=b.config,h=g.debug,i=f.find("thead:first"),j=i.find("tr."+s.css.headerRow).removeClass(s.css.headerRow+" "+g.cssHeaderRow),k=f.find("tfoot:first > tr").children("th, td");c===!1&&a.inArray("uitheme",g.widgets)>=0&&(f.trigger("applyWidgetId",["uitheme"]),f.trigger("applyWidgetId",["zebra"])),i.find("tr").not(j).remove(),e="sortReset update updateAll updateRows updateCell addRows updateComplete sorton appendCache updateCache "+"applyWidgetId applyWidgets refreshWidgets destroy mouseup mouseleave keypress sortBegin sortEnd resetToLoadState ".split(" ").join(g.namespace+" "),f.removeData("tablesorter").unbind(e.replace(/\s+/g," ")),g.$headers.add(k).removeClass([s.css.header,g.cssHeader,g.cssAsc,g.cssDesc,s.css.sortAsc,s.css.sortDesc,s.css.sortNone].join(" ")).removeAttr("data-column").removeAttr("aria-label").attr("aria-disabled","true"),j.find(g.selectorSort).unbind("mousedown mouseup keypress ".split(" ").join(g.namespace+" ").replace(/\s+/g," ")),s.restoreHeaders(b),f.toggleClass(s.css.table+" "+g.tableClass+" tablesorter-"+g.theme,c===!1),b.hasInitialized=!1,delete b.config.cache,"function"==typeof d&&d(b),h&&console.log("tablesorter has been removed")}},s.regex={chunk:/(^([+\-]?(?:0|[1-9]\d*)(?:\.\d*)?(?:[eE][+\-]?\d+)?)?$|^0x[0-9a-f]+$|\d+)/gi,chunks:/(^\\0|\\0$)/,hex:/^0x[0-9a-f]+$/i},s.sortNatural=function(a,b){if(a===b)return 0;var c,d,e,f,g,h,i,j,k=s.regex;if(k.hex.test(b)){if(d=parseInt(a.match(k.hex),16),f=parseInt(b.match(k.hex),16),f>d)return-1;if(d>f)return 1}for(c=a.replace(k.chunk,"\\0$1\\0").replace(k.chunks,"").split("\\0"),e=b.replace(k.chunk,"\\0$1\\0").replace(k.chunks,"").split("\\0"),j=Math.max(c.length,e.length),i=0;j>i;i++){if(g=isNaN(c[i])?c[i]||0:parseFloat(c[i])||0,h=isNaN(e[i])?e[i]||0:parseFloat(e[i])||0,isNaN(g)!==isNaN(h))return isNaN(g)?1:-1;if(typeof g!=typeof h&&(g+="",h+=""),h>g)return-1;if(g>h)return 1}return 0},s.sortNaturalAsc=function(a,b,c,d,e){if(a===b)return 0;var f=e.string[e.empties[c]||e.emptyTo];return""===a&&0!==f?"boolean"==typeof f?f?-1:1:-f||-1:""===b&&0!==f?"boolean"==typeof f?f?1:-1:f||1:s.sortNatural(a,b)},s.sortNaturalDesc=function(a,b,c,d,e){if(a===b)return 0;var f=e.string[e.empties[c]||e.emptyTo];return""===a&&0!==f?"boolean"==typeof f?f?-1:1:f||1:""===b&&0!==f?"boolean"==typeof f?f?1:-1:-f||-1:s.sortNatural(b,a)},s.sortText=function(a,b){return a>b?1:b>a?-1:0},s.getTextValue=function(a,b,c){if(c){var d,e=a?a.length:0,f=c+b;for(d=0;e>d;d++)f+=a.charCodeAt(d);return b*f}return 0},s.sortNumericAsc=function(a,b,c,d,e,f){if(a===b)return 0;var g=f.config,h=g.string[g.empties[e]||g.emptyTo];return""===a&&0!==h?"boolean"==typeof h?h?-1:1:-h||-1:""===b&&0!==h?"boolean"==typeof h?h?1:-1:h||1:(isNaN(a)&&(a=s.getTextValue(a,c,d)),isNaN(b)&&(b=s.getTextValue(b,c,d)),a-b)},s.sortNumericDesc=function(a,b,c,d,e,f){if(a===b)return 0;var g=f.config,h=g.string[g.empties[e]||g.emptyTo];return""===a&&0!==h?"boolean"==typeof h?h?-1:1:h||1:""===b&&0!==h?"boolean"==typeof h?h?1:-1:-h||-1:(isNaN(a)&&(a=s.getTextValue(a,c,d)),isNaN(b)&&(b=s.getTextValue(b,c,d)),b-a)},s.sortNumeric=function(a,b){return a-b},s.characterEquivalents={a:"áàâãäąå",A:"ÁÀÂÃÄĄÅ",c:"çćč",C:"ÇĆČ",e:"éèêëěę",E:"ÉÈÊËĚĘ",i:"íìİîïı",I:"ÍÌİÎÏ",o:"óòôõöō",O:"ÓÒÔÕÖŌ",ss:"ß",SS:"ẞ",u:"úùûüů",U:"ÚÙÛÜŮ"},s.replaceAccents=function(a){var b,c="[",d=s.characterEquivalents;if(!s.characterRegex){s.characterRegexArray={};for(b in d)"string"==typeof b&&(c+=d[b],s.characterRegexArray[b]=new RegExp("["+d[b]+"]","g"));s.characterRegex=new RegExp(c+"]")}if(s.characterRegex.test(a))for(b in d)"string"==typeof b&&(a=a.replace(s.characterRegexArray[b],b));return a},s.isValueInArray=function(a,b){var c,d=b&&b.length||0;for(c=0;d>c;c++)if(b[c][0]===a)return c;return-1},s.addParser=function(a){var b,c=s.parsers.length,d=!0;for(b=0;c>b;b++)s.parsers[b].id.toLowerCase()===a.id.toLowerCase()&&(d=!1);d&&s.parsers.push(a)},s.addInstanceMethods=function(b){a.extend(s.instanceMethods,b)},s.getParserById=function(a){if("false"==a)return!1;var b,c=s.parsers.length;for(b=0;c>b;b++)if(s.parsers[b].id.toLowerCase()===a.toString().toLowerCase())return s.parsers[b];return!1},s.addWidget=function(a){s.widgets.push(a)},s.hasWidget=function(b,c){return b=a(b),b.length&&b[0].config&&b[0].config.widgetInit[c]||!1},s.getWidgetById=function(a){var b,c,d=s.widgets.length;for(b=0;d>b;b++)if(c=s.widgets[b],c&&c.hasOwnProperty("id")&&c.id.toLowerCase()===a.toLowerCase())return c},s.applyWidgetOptions=function(b,c){var d,e,f=c.widgets.length,g=c.widgetOptions;if(f)for(d=0;f>d;d++)e=s.getWidgetById(c.widgets[d]),e&&"options"in e&&(g=b.config.widgetOptions=a.extend(!0,{},e.options,g))},s.applyWidget=function(b,c,d){b=a(b)[0];var e,f,g,h,i,j,k,l,m,n,o=b.config,p=o.widgetOptions,q=" "+o.table.className+" ",r=[];if(c===!1||!b.hasInitialized||!b.isApplyingWidgets&&!b.isUpdating){if(o.debug&&(k=new Date),n=new RegExp("\\s"+o.widgetClass.replace(/\{name\}/i,"([\\w-]+)")+"\\s","g"),q.match(n)&&(m=q.match(n)))for(f=m.length,e=0;f>e;e++)o.widgets.push(m[e].replace(n,"$1"));if(o.widgets.length){for(b.isApplyingWidgets=!0,o.widgets=a.grep(o.widgets,function(b,c){return a.inArray(b,o.widgets)===c}),g=o.widgets||[],f=g.length,e=0;f>e;e++)n=s.getWidgetById(g[e]),n&&n.id&&(n.priority||(n.priority=10),r[e]=n);for(r.sort(function(a,b){return a.priority<b.priority?-1:a.priority===b.priority?0:1}),f=r.length,o.debug&&console[console.group?"group":"log"]("Start "+(c?"initializing":"applying")+" widgets"),e=0;f>e;e++)h=r[e],h&&(i=h.id,j=!1,o.debug&&(l=new Date),(c||!o.widgetInit[i])&&(o.widgetInit[i]=!0,b.hasInitialized&&s.applyWidgetOptions(b,o),"init"in h&&(j=!0,o.debug&&console[console.group?"group":"log"]("Initializing "+i+" widget"),h.init(b,h,o,p))),!c&&"format"in h&&(j=!0,o.debug&&console[console.group?"group":"log"]("Updating "+i+" widget"),h.format(b,o,p,!1)),o.debug&&j&&(console.log("Completed "+(c?"initializing ":"applying ")+i+" widget"+s.benchmark(l)),console.groupEnd&&console.groupEnd()));o.debug&&console.groupEnd&&console.groupEnd(),c||"function"!=typeof d||d(b)}setTimeout(function(){b.isApplyingWidgets=!1,a.data(b,"lastWidgetApplication",new Date)},0),o.debug&&(m=o.widgets.length,console.log("Completed "+(c===!0?"initializing ":"applying ")+m+" widget"+(1!==m?"s":"")+s.benchmark(k)))}},s.removeWidget=function(b,c,d){b=a(b)[0];var e,f,g,h,i=b.config;if(c===!0)for(c=[],h=s.widgets.length,g=0;h>g;g++)f=s.widgets[g],f&&f.id&&c.push(f.id);else c=(a.isArray(c)?c.join(","):c||"").toLowerCase().split(/[\s,]+/);for(h=c.length,e=0;h>e;e++)f=s.getWidgetById(c[e]),g=a.inArray(c[e],i.widgets),f&&"remove"in f&&(i.debug&&g>=0&&console.log('Removing "'+c[e]+'" widget'),i.debug&&console.log((d?"Refreshing":"Removing")+' "'+c[e]+'" widget'),f.remove(b,i,i.widgetOptions,d),i.widgetInit[c[e]]=!1),g>=0&&d!==!0&&i.widgets.splice(g,1)},s.refreshWidgets=function(b,c,d){b=a(b)[0];var e,f=b.config,g=f.widgets,h=s.widgets,i=h.length,j=[],k=function(b){a(b).trigger("refreshComplete")};for(e=0;i>e;e++)h[e]&&h[e].id&&(c||a.inArray(h[e].id,g)<0)&&j.push(h[e].id);s.removeWidget(b,j.join(","),!0),d!==!0?(s.applyWidget(b,c||!1,k),c&&s.applyWidget(b,!1,k)):k(b)},s.getColumnText=function(c,d,e){c=a(c)[0];var f,g,h,i,j,k,l,m,n,o,p="function"==typeof e,q="all"===d,r={raw:[],parsed:[],$cell:[]},s=c.config;if(!b(s)){for(j=s.$tbodies.length,f=0;j>f;f++)for(h=s.cache[f].normalized,k=h.length,g=0;k>g;g++)o=!0,i=h[g],m=q?i.slice(0,s.columns):i[d],i=i[s.columns],l=q?i.raw:i.raw[d],n=q?i.$row.children():i.$row.children().eq(d),p&&(o=e({tbodyIndex:f,rowIndex:g,parsed:m,raw:l,$row:i.$row,$cell:n})),o!==!1&&(r.parsed.push(m),r.raw.push(l),r.$cell.push(n));return r}s.debug&&console.warn("No cache found - aborting getColumnText function!")},s.getData=function(b,c,d){var e,f,g="",h=a(b);return h.length?(e=a.metadata?h.metadata():!1,f=" "+(h.attr("class")||""),"undefined"!=typeof h.data(d)||"undefined"!=typeof h.data(d.toLowerCase())?g+=h.data(d)||h.data(d.toLowerCase()):e&&"undefined"!=typeof e[d]?g+=e[d]:c&&"undefined"!=typeof c[d]?g+=c[d]:" "!==f&&f.match(" "+d+"-")&&(g=f.match(new RegExp("\\s"+d+"-([\\w-]+)"))[1]||""),a.trim(g)):""},s.formatFloat=function(b,c){if("string"!=typeof b||""===b)return b;var d,e=c&&c.config?c.config.usNumberFormat!==!1:"undefined"!=typeof c?c:!0;return b=e?b.replace(/,/g,""):b.replace(/[\s|\.]/g,"").replace(/,/g,"."),/^\s*\([.\d]+\)/.test(b)&&(b=b.replace(/^\s*\(([.\d]+)\)/,"-$1")),d=parseFloat(b),isNaN(d)?a.trim(b):d},s.isDigit=function(a){return isNaN(a)?/^[\-+(]?\d+[)]?$/.test(a.toString().replace(/[,.'"\s]/g,"")):""!==a}}});var b=a.tablesorter;a.fn.extend({tablesorter:b.construct}),console&&console.log||(b.logs=[],console={},console.log=console.warn=console.error=console.table=function(){b.logs.push([Date.now(),arguments])}),b.log=function(){console.log(arguments)},b.benchmark=function(a){return" ("+((new Date).getTime()-a.getTime())+"ms)"},b.addParser({id:"no-parser",is:function(){return!1},format:function(){return""},type:"text"}),b.addParser({id:"text",is:function(){return!0},format:function(c,d){var e=d.config;return c&&(c=a.trim(e.ignoreCase?c.toLocaleLowerCase():c),c=e.sortLocaleCompare?b.replaceAccents(c):c),c},type:"text"}),b.addParser({id:"digit",is:function(a){return b.isDigit(a)},format:function(c,d){var e=b.formatFloat((c||"").replace(/[^\w,. \-()]/g,""),d);return c&&"number"==typeof e?e:c?a.trim(c&&d.config.ignoreCase?c.toLocaleLowerCase():c):c},type:"numeric"}),b.addParser({id:"currency",is:function(a){return/^\(?\d+[\u00a3$\u20ac\u00a4\u00a5\u00a2?.]|[\u00a3$\u20ac\u00a4\u00a5\u00a2?.]\d+\)?$/.test((a||"").replace(/[+\-,. ]/g,""))},format:function(c,d){var e=b.formatFloat((c||"").replace(/[^\w,. \-()]/g,""),d);
return c&&"number"==typeof e?e:c?a.trim(c&&d.config.ignoreCase?c.toLocaleLowerCase():c):c},type:"numeric"}),b.addParser({id:"url",is:function(a){return/^(https?|ftp|file):\/\//.test(a)},format:function(b){return b?a.trim(b.replace(/(https?|ftp|file):\/\//,"")):b},parsed:!0,type:"text"}),b.addParser({id:"isoDate",is:function(a){return/^\d{4}[\/\-]\d{1,2}[\/\-]\d{1,2}/.test(a)},format:function(a,b){var c=a?new Date(a.replace(/-/g,"/")):a;return c instanceof Date&&isFinite(c)?c.getTime():a},type:"numeric"}),b.addParser({id:"percent",is:function(a){return/(\d\s*?%|%\s*?\d)/.test(a)&&a.length<15},format:function(a,c){return a?b.formatFloat(a.replace(/%/g,""),c):a},type:"numeric"}),b.addParser({id:"image",is:function(a,b,c,d){return d.find("img").length>0},format:function(b,c,d){return a(d).find("img").attr(c.config.imgAttr||"alt")||b},parsed:!0,type:"text"}),b.addParser({id:"usLongDate",is:function(a){return/^[A-Z]{3,10}\.?\s+\d{1,2},?\s+(\d{4})(\s+\d{1,2}:\d{2}(:\d{2})?(\s+[AP]M)?)?$/i.test(a)||/^\d{1,2}\s+[A-Z]{3,10}\s+\d{4}/i.test(a)},format:function(a,b){var c=a?new Date(a.replace(/(\S)([AP]M)$/i,"$1 $2")):a;return c instanceof Date&&isFinite(c)?c.getTime():a},type:"numeric"}),b.addParser({id:"shortDate",is:function(a){return/(^\d{1,2}[\/\s]\d{1,2}[\/\s]\d{4})|(^\d{4}[\/\s]\d{1,2}[\/\s]\d{1,2})/.test((a||"").replace(/\s+/g," ").replace(/[\-.,]/g,"/"))},format:function(a,c,d,e){if(a){var f,g,h=c.config,i=h.$headerIndexed[e],j=i.length&&i[0].dateFormat||b.getData(i,b.getColumnData(c,h.headers,e),"dateFormat")||h.dateFormat;return g=a.replace(/\s+/g," ").replace(/[\-.,]/g,"/"),"mmddyyyy"===j?g=g.replace(/(\d{1,2})[\/\s](\d{1,2})[\/\s](\d{4})/,"$3/$1/$2"):"ddmmyyyy"===j?g=g.replace(/(\d{1,2})[\/\s](\d{1,2})[\/\s](\d{4})/,"$3/$2/$1"):"yyyymmdd"===j&&(g=g.replace(/(\d{4})[\/\s](\d{1,2})[\/\s](\d{1,2})/,"$1/$2/$3")),f=new Date(g),f instanceof Date&&isFinite(f)?f.getTime():a}return a},type:"numeric"}),b.addParser({id:"time",is:function(a){return/^(([0-2]?\d:[0-5]\d)|([0-1]?\d:[0-5]\d\s?([AP]M)))$/i.test(a)},format:function(a,b){var c=a?new Date("2000/01/01 "+a.replace(/(\S)([AP]M)$/i,"$1 $2")):a;return c instanceof Date&&isFinite(c)?c.getTime():a},type:"numeric"}),b.addParser({id:"metadata",is:function(){return!1},format:function(b,c,d){var e=c.config,f=e.parserMetadataName?e.parserMetadataName:"sortValue";return a(d).metadata()[f]},type:"numeric"}),b.addWidget({id:"zebra",priority:90,format:function(b,c,d){var e,f,g,h,i,j,k,l,m=new RegExp(c.cssChildRow,"i"),n=c.$tbodies.add(a(c.namespace+"_extra_table").children("tbody:not(."+c.cssInfoBlock+")"));for(c.debug&&(i=new Date),j=0;j<n.length;j++)for(g=0,e=n.eq(j).children("tr:visible").not(c.selectorRemove),l=e.length,k=0;l>k;k++)f=e.eq(k),m.test(f[0].className)||g++,h=g%2===0,f.removeClass(d.zebra[h?1:0]).addClass(d.zebra[h?0:1])},remove:function(a,c,d,e){if(!e){var f,g,h=c.$tbodies,i=(d.zebra||["even","odd"]).join(" ");for(f=0;f<h.length;f++)g=b.processTbody(a,h.eq(f),!0),g.children().removeClass(i),b.processTbody(a,g,!1)}}})}(jQuery),a.tablesorter});

/*** This file is dynamically generated ***
█████▄ ▄████▄   █████▄ ▄████▄ ██████   ███████▄ ▄████▄ █████▄ ██ ██████ ██  ██
██  ██ ██  ██   ██  ██ ██  ██   ██     ██ ██ ██ ██  ██ ██  ██ ██ ██     ██  ██
██  ██ ██  ██   ██  ██ ██  ██   ██     ██ ██ ██ ██  ██ ██  ██ ██ ██▀▀   ▀▀▀▀██
█████▀ ▀████▀   ██  ██ ▀████▀   ██     ██ ██ ██ ▀████▀ █████▀ ██ ██     █████▀
*/
/*! tablesorter (FORK) - updated 07-28-2015 (v2.22.5)*/
/* Includes widgets ( storage,uitheme,columns,filter,stickyHeaders,resizable,saveSort ) */
(function(factory) {
	if (typeof define === 'function' && define.amd) {
		define(['jquery'], factory);
	} else if (typeof module === 'object' && typeof module.exports === 'object') {
		module.exports = factory(require('jquery'));
	} else {
		factory(jQuery);
	}
}(function($) {

/*! Widget: storage - updated 3/26/2015 (v2.21.3) */
/*global JSON:false */
;(function ($, window, document) {
	'use strict';

	var ts = $.tablesorter || {};
	// *** Store data in local storage, with a cookie fallback ***
	/* IE7 needs JSON library for JSON.stringify - (http://caniuse.com/#search=json)
	   if you need it, then include https://github.com/douglascrockford/JSON-js

	   $.parseJSON is not available is jQuery versions older than 1.4.1, using older
	   versions will only allow storing information for one page at a time

	   // *** Save data (JSON format only) ***
	   // val must be valid JSON... use http://jsonlint.com/ to ensure it is valid
	   var val = { "mywidget" : "data1" }; // valid JSON uses double quotes
	   // $.tablesorter.storage(table, key, val);
	   $.tablesorter.storage(table, 'tablesorter-mywidget', val);

	   // *** Get data: $.tablesorter.storage(table, key); ***
	   v = $.tablesorter.storage(table, 'tablesorter-mywidget');
	   // val may be empty, so also check for your data
	   val = (v && v.hasOwnProperty('mywidget')) ? v.mywidget : '';
	   alert(val); // 'data1' if saved, or '' if not
	*/
	ts.storage = function(table, key, value, options) {
		table = $(table)[0];
		var cookieIndex, cookies, date,
			hasStorage = false,
			values = {},
			c = table.config,
			wo = c && c.widgetOptions,
			storageType = ( options && options.useSessionStorage ) || ( wo && wo.storage_useSessionStorage ) ?
				'sessionStorage' : 'localStorage',
			$table = $(table),
			// id from (1) options ID, (2) table 'data-table-group' attribute, (3) widgetOptions.storage_tableId,
			// (4) table ID, then (5) table index
			id = options && options.id ||
				$table.attr( options && options.group || wo && wo.storage_group || 'data-table-group') ||
				wo && wo.storage_tableId || table.id || $('.tablesorter').index( $table ),
			// url from (1) options url, (2) table 'data-table-page' attribute, (3) widgetOptions.storage_fixedUrl,
			// (4) table.config.fixedUrl (deprecated), then (5) window location path
			url = options && options.url ||
				$table.attr(options && options.page || wo && wo.storage_page || 'data-table-page') ||
				wo && wo.storage_fixedUrl || c && c.fixedUrl || window.location.pathname;
		// https://gist.github.com/paulirish/5558557
		if (storageType in window) {
			try {
				window[storageType].setItem('_tmptest', 'temp');
				hasStorage = true;
				window[storageType].removeItem('_tmptest');
			} catch (error) {
				if (c && c.debug) {
					console.warn( storageType + ' is not supported in this browser' );
				}
			}
		}
		// *** get value ***
		if ($.parseJSON) {
			if (hasStorage) {
				values = $.parseJSON( window[storageType][key] || 'null' ) || {};
			} else {
				// old browser, using cookies
				cookies = document.cookie.split(/[;\s|=]/);
				// add one to get from the key to the value
				cookieIndex = $.inArray(key, cookies) + 1;
				values = (cookieIndex !== 0) ? $.parseJSON(cookies[cookieIndex] || 'null') || {} : {};
			}
		}
		// allow value to be an empty string too
		if ((value || value === '') && window.JSON && JSON.hasOwnProperty('stringify')) {
			// add unique identifiers = url pathname > table ID/index on page > data
			if (!values[url]) {
				values[url] = {};
			}
			values[url][id] = value;
			// *** set value ***
			if (hasStorage) {
				window[storageType][key] = JSON.stringify(values);
			} else {
				date = new Date();
				date.setTime(date.getTime() + (31536e+6)); // 365 days
				document.cookie = key + '=' + (JSON.stringify(values)).replace(/\"/g, '\"') + '; expires=' + date.toGMTString() + '; path=/';
			}
		} else {
			return values && values[url] ? values[url][id] : '';
		}
	};

})(jQuery, window, document);

/*! Widget: uitheme - updated 3/26/2015 (v2.21.3) */
;(function ($) {
	'use strict';
	var ts = $.tablesorter || {};

	ts.themes = {
		'bootstrap' : {
			table        : 'table table-bordered table-striped',
			caption      : 'caption',
			// header class names
			header       : 'bootstrap-header', // give the header a gradient background (theme.bootstrap_2.css)
			sortNone     : '',
			sortAsc      : '',
			sortDesc     : '',
			active       : '', // applied when column is sorted
			hover        : '', // custom css required - a defined bootstrap style may not override other classes
			// icon class names
			icons        : '', // add 'icon-white' to make them white; this icon class is added to the <i> in the header
			iconSortNone : 'bootstrap-icon-unsorted', // class name added to icon when column is not sorted
			iconSortAsc  : 'icon-chevron-up glyphicon glyphicon-chevron-up', // class name added to icon when column has ascending sort
			iconSortDesc : 'icon-chevron-down glyphicon glyphicon-chevron-down', // class name added to icon when column has descending sort
			filterRow    : '', // filter row class
			footerRow    : '',
			footerCells  : '',
			even         : '', // even row zebra striping
			odd          : ''  // odd row zebra striping
		},
		'jui' : {
			table        : 'ui-widget ui-widget-content ui-corner-all', // table classes
			caption      : 'ui-widget-content',
			// header class names
			header       : 'ui-widget-header ui-corner-all ui-state-default', // header classes
			sortNone     : '',
			sortAsc      : '',
			sortDesc     : '',
			active       : 'ui-state-active', // applied when column is sorted
			hover        : 'ui-state-hover',  // hover class
			// icon class names
			icons        : 'ui-icon', // icon class added to the <i> in the header
			iconSortNone : 'ui-icon-carat-2-n-s', // class name added to icon when column is not sorted
			iconSortAsc  : 'ui-icon-carat-1-n', // class name added to icon when column has ascending sort
			iconSortDesc : 'ui-icon-carat-1-s', // class name added to icon when column has descending sort
			filterRow    : '',
			footerRow    : '',
			footerCells  : '',
			even         : 'ui-widget-content', // even row zebra striping
			odd          : 'ui-state-default'   // odd row zebra striping
		}
	};

	$.extend(ts.css, {
		wrapper : 'tablesorter-wrapper' // ui theme & resizable
	});

	ts.addWidget({
		id: 'uitheme',
		priority: 10,
		format: function(table, c, wo) {
			var i, hdr, icon, time, $header, $icon, $tfoot, $h, oldtheme, oldremove, oldIconRmv, hasOldTheme,
				themesAll = ts.themes,
				$table = c.$table.add( $( c.namespace + '_extra_table' ) ),
				$headers = c.$headers.add( $( c.namespace + '_extra_headers' ) ),
				theme = c.theme || 'jui',
				themes = themesAll[theme] || {},
				remove = $.trim( [ themes.sortNone, themes.sortDesc, themes.sortAsc, themes.active ].join( ' ' ) ),
				iconRmv = $.trim( [ themes.iconSortNone, themes.iconSortDesc, themes.iconSortAsc ].join( ' ' ) );
			if (c.debug) { time = new Date(); }
			// initialization code - run once
			if (!$table.hasClass('tablesorter-' + theme) || c.theme !== c.appliedTheme || !wo.uitheme_applied) {
				wo.uitheme_applied = true;
				oldtheme = themesAll[c.appliedTheme] || {};
				hasOldTheme = !$.isEmptyObject(oldtheme);
				oldremove =  hasOldTheme ? [ oldtheme.sortNone, oldtheme.sortDesc, oldtheme.sortAsc, oldtheme.active ].join( ' ' ) : '';
				oldIconRmv = hasOldTheme ? [ oldtheme.iconSortNone, oldtheme.iconSortDesc, oldtheme.iconSortAsc ].join( ' ' ) : '';
				if (hasOldTheme) {
					wo.zebra[0] = $.trim( ' ' + wo.zebra[0].replace(' ' + oldtheme.even, '') );
					wo.zebra[1] = $.trim( ' ' + wo.zebra[1].replace(' ' + oldtheme.odd, '') );
					c.$tbodies.children().removeClass( [ oldtheme.even, oldtheme.odd ].join(' ') );
				}
				// update zebra stripes
				if (themes.even) { wo.zebra[0] += ' ' + themes.even; }
				if (themes.odd) { wo.zebra[1] += ' ' + themes.odd; }
				// add caption style
				$table.children('caption')
					.removeClass(oldtheme.caption || '')
					.addClass(themes.caption);
				// add table/footer class names
				$tfoot = $table
					// remove other selected themes
					.removeClass( (c.appliedTheme ? 'tablesorter-' + (c.appliedTheme || '') : '') + ' ' + (oldtheme.table || '') )
					.addClass('tablesorter-' + theme + ' ' + (themes.table || '')) // add theme widget class name
					.children('tfoot');
				c.appliedTheme = c.theme;

				if ($tfoot.length) {
					$tfoot
						// if oldtheme.footerRow or oldtheme.footerCells are undefined, all class names are removed
						.children('tr').removeClass(oldtheme.footerRow || '').addClass(themes.footerRow)
						.children('th, td').removeClass(oldtheme.footerCells || '').addClass(themes.footerCells);
				}
				// update header classes
				$headers
					.removeClass( (hasOldTheme ? [ oldtheme.header, oldtheme.hover, oldremove ].join(' ') : '') || '' )
					.addClass(themes.header)
					.not('.sorter-false')
					.unbind('mouseenter.tsuitheme mouseleave.tsuitheme')
					.bind('mouseenter.tsuitheme mouseleave.tsuitheme', function(event) {
						// toggleClass with switch added in jQuery 1.3
						$(this)[ event.type === 'mouseenter' ? 'addClass' : 'removeClass' ](themes.hover || '');
					});

				$headers.each(function(){
					var $this = $(this);
					if (!$this.find('.' + ts.css.wrapper).length) {
						// Firefox needs this inner div to position the icon & resizer correctly
						$this.wrapInner('<div class="' + ts.css.wrapper + '" style="position:relative;height:100%;width:100%"></div>');
					}
				});
				if (c.cssIcon) {
					// if c.cssIcon is '', then no <i> is added to the header
					$headers
						.find('.' + ts.css.icon)
						.removeClass(hasOldTheme ? [ oldtheme.icons, oldIconRmv ].join(' ') : '')
						.addClass(themes.icons || '');
				}
				if ($table.hasClass('hasFilters')) {
					$table.children('thead').children('.' + ts.css.filterRow)
						.removeClass(hasOldTheme ? oldtheme.filterRow || '' : '')
						.addClass(themes.filterRow || '');
				}
			}
			for (i = 0; i < c.columns; i++) {
				$header = c.$headers
					.add($(c.namespace + '_extra_headers'))
					.not('.sorter-false')
					.filter('[data-column="' + i + '"]');
				$icon = (ts.css.icon) ? $header.find('.' + ts.css.icon) : $();
				$h = $headers.not('.sorter-false').filter('[data-column="' + i + '"]:last');
				if ($h.length) {
					$header.removeClass(remove);
					$icon.removeClass(iconRmv);
					if ($h[0].sortDisabled) {
						// no sort arrows for disabled columns!
						$icon.removeClass(themes.icons || '');
					} else {
						hdr = themes.sortNone;
						icon = themes.iconSortNone;
						if ($h.hasClass(ts.css.sortAsc)) {
							hdr = [ themes.sortAsc, themes.active ].join(' ');
							icon = themes.iconSortAsc;
						} else if ($h.hasClass(ts.css.sortDesc)) {
							hdr = [ themes.sortDesc, themes.active ].join(' ');
							icon = themes.iconSortDesc;
						}
						$header.addClass(hdr);
						$icon.addClass(icon || '');
					}
				}
			}
			if (c.debug) {
				console.log('Applying ' + theme + ' theme' + ts.benchmark(time));
			}
		},
		remove: function(table, c, wo, refreshing) {
			if (!wo.uitheme_applied) { return; }
			var $table = c.$table,
				theme = c.appliedTheme || 'jui',
				themes = ts.themes[ theme ] || ts.themes.jui,
				$headers = $table.children('thead').children(),
				remove = themes.sortNone + ' ' + themes.sortDesc + ' ' + themes.sortAsc,
				iconRmv = themes.iconSortNone + ' ' + themes.iconSortDesc + ' ' + themes.iconSortAsc;
			$table.removeClass('tablesorter-' + theme + ' ' + themes.table);
			wo.uitheme_applied = false;
			if (refreshing) { return; }
			$table.find(ts.css.header).removeClass(themes.header);
			$headers
				.unbind('mouseenter.tsuitheme mouseleave.tsuitheme') // remove hover
				.removeClass(themes.hover + ' ' + remove + ' ' + themes.active)
				.filter('.' + ts.css.filterRow)
				.removeClass(themes.filterRow);
			$headers.find('.' + ts.css.icon).removeClass(themes.icons + ' ' + iconRmv);
		}
	});

})(jQuery);

/*! Widget: columns */
;(function ($) {
	'use strict';
	var ts = $.tablesorter || {};

	ts.addWidget({
		id: 'columns',
		priority: 30,
		options : {
			columns : [ 'primary', 'secondary', 'tertiary' ]
		},
		format: function(table, c, wo) {
			var $tbody, tbodyIndex, $rows, rows, $row, $cells, remove, indx,
			$table = c.$table,
			$tbodies = c.$tbodies,
			sortList = c.sortList,
			len = sortList.length,
			// removed c.widgetColumns support
			css = wo && wo.columns || [ 'primary', 'secondary', 'tertiary' ],
			last = css.length - 1;
			remove = css.join(' ');
			// check if there is a sort (on initialization there may not be one)
			for (tbodyIndex = 0; tbodyIndex < $tbodies.length; tbodyIndex++ ) {
				$tbody = ts.processTbody(table, $tbodies.eq(tbodyIndex), true); // detach tbody
				$rows = $tbody.children('tr');
				// loop through the visible rows
				$rows.each(function() {
					$row = $(this);
					if (this.style.display !== 'none') {
						// remove all columns class names
						$cells = $row.children().removeClass(remove);
						// add appropriate column class names
						if (sortList && sortList[0]) {
							// primary sort column class
							$cells.eq(sortList[0][0]).addClass(css[0]);
							if (len > 1) {
								for (indx = 1; indx < len; indx++) {
									// secondary, tertiary, etc sort column classes
									$cells.eq(sortList[indx][0]).addClass( css[indx] || css[last] );
								}
							}
						}
					}
				});
				ts.processTbody(table, $tbody, false);
			}
			// add classes to thead and tfoot
			rows = wo.columns_thead !== false ? [ 'thead tr' ] : [];
			if (wo.columns_tfoot !== false) {
				rows.push('tfoot tr');
			}
			if (rows.length) {
				$rows = $table.find( rows.join(',') ).children().removeClass(remove);
				if (len) {
					for (indx = 0; indx < len; indx++) {
						// add primary. secondary, tertiary, etc sort column classes
						$rows.filter('[data-column="' + sortList[indx][0] + '"]').addClass(css[indx] || css[last]);
					}
				}
			}
		},
		remove: function(table, c, wo) {
			var tbodyIndex, $tbody,
				$tbodies = c.$tbodies,
				remove = (wo.columns || [ 'primary', 'secondary', 'tertiary' ]).join(' ');
			c.$headers.removeClass(remove);
			c.$table.children('tfoot').children('tr').children('th, td').removeClass(remove);
			for (tbodyIndex = 0; tbodyIndex < $tbodies.length; tbodyIndex++ ) {
				$tbody = ts.processTbody(table, $tbodies.eq(tbodyIndex), true); // remove tbody
				$tbody.children('tr').each(function() {
					$(this).children().removeClass(remove);
				});
				ts.processTbody(table, $tbody, false); // restore tbody
			}
		}
	});

})(jQuery);

/*! Widget: filter - updated 7/28/2015 (v2.22.4) *//*
 * Requires tablesorter v2.8+ and jQuery 1.7+
 * by Rob Garrison
 */
;( function ( $ ) {
	'use strict';
	var ts = $.tablesorter || {},
	tscss = ts.css;

	$.extend( tscss, {
		filterRow      : 'tablesorter-filter-row',
		filter         : 'tablesorter-filter',
		filterDisabled : 'disabled',
		filterRowHide  : 'hideme'
	});

	ts.addWidget({
		id: 'filter',
		priority: 50,
		options : {
			filter_childRows     : false, // if true, filter includes child row content in the search
			filter_childByColumn : false, // ( filter_childRows must be true ) if true = search child rows by column; false = search all child row text grouped
			filter_columnFilters : true,  // if true, a filter will be added to the top of each table column
			filter_columnAnyMatch: true,  // if true, allows using '#:{query}' in AnyMatch searches ( column:query )
			filter_cellFilter    : '',    // css class name added to the filter cell ( string or array )
			filter_cssFilter     : '',    // css class name added to the filter row & each input in the row ( tablesorter-filter is ALWAYS added )
			filter_defaultFilter : {},    // add a default column filter type '~{query}' to make fuzzy searches default; '{q1} AND {q2}' to make all searches use a logical AND.
			filter_excludeFilter : {},    // filters to exclude, per column
			filter_external      : '',    // jQuery selector string ( or jQuery object ) of external filters
			filter_filteredRow   : 'filtered', // class added to filtered rows; needed by pager plugin
			filter_formatter     : null,  // add custom filter elements to the filter row
			filter_functions     : null,  // add custom filter functions using this option
			filter_hideEmpty     : true,  // hide filter row when table is empty
			filter_hideFilters   : false, // collapse filter row when mouse leaves the area
			filter_ignoreCase    : true,  // if true, make all searches case-insensitive
			filter_liveSearch    : true,  // if true, search column content while the user types ( with a delay )
			filter_onlyAvail     : 'filter-onlyAvail', // a header with a select dropdown & this class name will only show available ( visible ) options within the drop down
			filter_placeholder   : { search : '', select : '' }, // default placeholder text ( overridden by any header 'data-placeholder' setting )
			filter_reset         : null,  // jQuery selector string of an element used to reset the filters
			filter_saveFilters   : false, // Use the $.tablesorter.storage utility to save the most recent filters
			filter_searchDelay   : 300,   // typing delay in milliseconds before starting a search
			filter_searchFiltered: true,  // allow searching through already filtered rows in special circumstances; will speed up searching in large tables if true
			filter_selectSource  : null,  // include a function to return an array of values to be added to the column filter select
			filter_startsWith    : false, // if true, filter start from the beginning of the cell contents
			filter_useParsedData : false, // filter all data using parsed content
			filter_serversideFiltering : false, // if true, must perform server-side filtering b/c client-side filtering is disabled, but the ui and events will still be used.
			filter_defaultAttrib : 'data-value', // data attribute in the header cell that contains the default filter value
			filter_selectSourceSeparator : '|' // filter_selectSource array text left of the separator is added to the option value, right into the option text
		},
		format: function( table, c, wo ) {
			if ( !c.$table.hasClass( 'hasFilters' ) ) {
				ts.filter.init( table, c, wo );
			}
		},
		remove: function( table, c, wo, refreshing ) {
			var tbodyIndex, $tbody,
				$table = c.$table,
				$tbodies = c.$tbodies,
				events = 'addRows updateCell update updateRows updateComplete appendCache filterReset filterEnd search '
					.split( ' ' ).join( c.namespace + 'filter ' );
			$table
				.removeClass( 'hasFilters' )
				// add .tsfilter namespace to all BUT search
				.unbind( events.replace( /\s+/g, ' ' ) )
				// remove the filter row even if refreshing, because the column might have been moved
				.find( '.' + tscss.filterRow ).remove();
			if ( refreshing ) { return; }
			for ( tbodyIndex = 0; tbodyIndex < $tbodies.length; tbodyIndex++ ) {
				$tbody = ts.processTbody( table, $tbodies.eq( tbodyIndex ), true ); // remove tbody
				$tbody.children().removeClass( wo.filter_filteredRow ).show();
				ts.processTbody( table, $tbody, false ); // restore tbody
			}
			if ( wo.filter_reset ) {
				$( document ).undelegate( wo.filter_reset, 'click.tsfilter' );
			}
		}
	});

	ts.filter = {

		// regex used in filter 'check' functions - not for general use and not documented
		regex: {
			regex     : /^\/((?:\\\/|[^\/])+)\/([mig]{0,3})?$/, // regex to test for regex
			child     : /tablesorter-childRow/, // child row class name; this gets updated in the script
			filtered  : /filtered/, // filtered (hidden) row class name; updated in the script
			type      : /undefined|number/, // check type
			exact     : /(^[\"\'=]+)|([\"\'=]+$)/g, // exact match (allow '==')
			nondigit  : /[^\w,. \-()]/g, // replace non-digits (from digit & currency parser)
			operators : /[<>=]/g, // replace operators
			query     : '(q|query)' // replace filter queries
		},
		// function( c, data ) { }
		// c = table.config
		// data.$row = jQuery object of the row currently being processed
		// data.$cells = jQuery object of all cells within the current row
		// data.filters = array of filters for all columns ( some may be undefined )
		// data.filter = filter for the current column
		// data.iFilter = same as data.filter, except lowercase ( if wo.filter_ignoreCase is true )
		// data.exact = table cell text ( or parsed data if column parser enabled )
		// data.iExact = same as data.exact, except lowercase ( if wo.filter_ignoreCase is true )
		// data.cache = table cell text from cache, so it has been parsed ( & in all lower case if c.ignoreCase is true )
		// data.cacheArray = An array of parsed content from each table cell in the row being processed
		// data.index = column index; table = table element ( DOM )
		// data.parsed = array ( by column ) of boolean values ( from filter_useParsedData or 'filter-parsed' class )
		types: {
			or : function( c, data, vars ) {
				if ( /\|/.test( data.iFilter ) || ts.filter.regex.orSplit.test( data.filter ) ) {
					var indx, filterMatched, query, regex,
						// duplicate data but split filter
						data2 = $.extend( {}, data ),
						index = data.index,
						parsed = data.parsed[ index ],
						filter = data.filter.split( ts.filter.regex.orSplit ),
						iFilter = data.iFilter.split( ts.filter.regex.orSplit ),
						len = filter.length;
					for ( indx = 0; indx < len; indx++ ) {
						data2.nestedFilters = true;
						data2.filter = '' + ( ts.filter.parseFilter( c, filter[ indx ], index, parsed ) || '' );
						data2.iFilter = '' + ( ts.filter.parseFilter( c, iFilter[ indx ], index, parsed ) || '' );
						query = '(' + ( ts.filter.parseFilter( c, data2.filter, index, parsed ) || '' ) + ')';
						try {
							// use try/catch, because query may not be a valid regex if "|" is contained within a partial regex search,
							// e.g "/(Alex|Aar" -> Uncaught SyntaxError: Invalid regular expression: /(/(Alex)/: Unterminated group
							regex = new RegExp( data.isMatch ? query : '^' + query + '$', c.widgetOptions.filter_ignoreCase ? 'i' : '' );
							// filterMatched = data2.filter === '' && indx > 0 ? true
							// look for an exact match with the 'or' unless the 'filter-match' class is found
							filterMatched = regex.test( data2.exact ) || ts.filter.processTypes( c, data2, vars );
							if ( filterMatched ) {
								return filterMatched;
							}
						} catch ( error ) {
							return null;
						}
					}
					// may be null from processing types
					return filterMatched || false;
				}
				return null;
			},
			// Look for an AND or && operator ( logical and )
			and : function( c, data, vars ) {
				if ( ts.filter.regex.andTest.test( data.filter ) ) {
					var indx, filterMatched, result, query, regex,
						// duplicate data but split filter
						data2 = $.extend( {}, data ),
						index = data.index,
						parsed = data.parsed[ index ],
						filter = data.filter.split( ts.filter.regex.andSplit ),
						iFilter = data.iFilter.split( ts.filter.regex.andSplit ),
						len = filter.length;
					for ( indx = 0; indx < len; indx++ ) {
						data2.nestedFilters = true;
						data2.filter = '' + ( ts.filter.parseFilter( c, filter[ indx ], index, parsed ) || '' );
						data2.iFilter = '' + ( ts.filter.parseFilter( c, iFilter[ indx ], index, parsed ) || '' );
						query = ( '(' + ( ts.filter.parseFilter( c, data2.filter, index, parsed ) || '' ) + ')' )
							// replace wild cards since /(a*)/i will match anything
							.replace( /\?/g, '\\S{1}' ).replace( /\*/g, '\\S*' );
						try {
							// use try/catch just in case RegExp is invalid
							regex = new RegExp( data.isMatch ? query : '^' + query + '$', c.widgetOptions.filter_ignoreCase ? 'i' : '' );
							// look for an exact match with the 'and' unless the 'filter-match' class is found
							result = ( regex.test( data2.exact ) || ts.filter.processTypes( c, data2, vars ) );
							if ( indx === 0 ) {
								filterMatched = result;
							} else {
								filterMatched = filterMatched && result;
							}
						} catch ( error ) {
							return null;
						}
					}
					// may be null from processing types
					return filterMatched || false;
				}
				return null;
			},
			// Look for regex
			regex: function( c, data ) {
				if ( ts.filter.regex.regex.test( data.filter ) ) {
					var matches,
						// cache regex per column for optimal speed
						regex = data.filter_regexCache[ data.index ] || ts.filter.regex.regex.exec( data.filter ),
						isRegex = regex instanceof RegExp;
					try {
						if ( !isRegex ) {
							// force case insensitive search if ignoreCase option set?
							// if ( c.ignoreCase && !regex[2] ) { regex[2] = 'i'; }
							data.filter_regexCache[ data.index ] = regex = new RegExp( regex[1], regex[2] );
						}
						matches = regex.test( data.exact );
					} catch ( error ) {
						matches = false;
					}
					return matches;
				}
				return null;
			},
			// Look for operators >, >=, < or <=
			operators: function( c, data ) {
				// ignore empty strings... because '' < 10 is true
				if ( /^[<>]=?/.test( data.iFilter ) && data.iExact !== '' ) {
					var cachedValue, result, txt,
						table = c.table,
						index = data.index,
						parsed = data.parsed[index],
						query = ts.formatFloat( data.iFilter.replace( ts.filter.regex.operators, '' ), table ),
						parser = c.parsers[index],
						savedSearch = query;
					// parse filter value in case we're comparing numbers ( dates )
					if ( parsed || parser.type === 'numeric' ) {
						txt = $.trim( '' + data.iFilter.replace( ts.filter.regex.operators, '' ) );
						result = ts.filter.parseFilter( c, txt, index, true );
						query = ( typeof result === 'number' && result !== '' && !isNaN( result ) ) ? result : query;
					}
					// iExact may be numeric - see issue #149;
					// check if cached is defined, because sometimes j goes out of range? ( numeric columns )
					if ( ( parsed || parser.type === 'numeric' ) && !isNaN( query ) &&
						typeof data.cache !== 'undefined' ) {
						cachedValue = data.cache;
					} else {
						txt = isNaN( data.iExact ) ? data.iExact.replace( ts.filter.regex.nondigit, '' ) : data.iExact;
						cachedValue = ts.formatFloat( txt, table );
					}
					if ( />/.test( data.iFilter ) ) {
						result = />=/.test( data.iFilter ) ? cachedValue >= query : cachedValue > query;
					} else if ( /</.test( data.iFilter ) ) {
						result = /<=/.test( data.iFilter ) ? cachedValue <= query : cachedValue < query;
					}
					// keep showing all rows if nothing follows the operator
					if ( !result && savedSearch === '' ) {
						result = true;
					}
					return result;
				}
				return null;
			},
			// Look for a not match
			notMatch: function( c, data ) {
				if ( /^\!/.test( data.iFilter ) ) {
					var indx,
						txt = data.iFilter.replace( '!', '' ),
						filter = ts.filter.parseFilter( c, txt, data.index, data.parsed[data.index] ) || '';
					if ( ts.filter.regex.exact.test( filter ) ) {
						// look for exact not matches - see #628
						filter = filter.replace( ts.filter.regex.exact, '' );
						return filter === '' ? true : $.trim( filter ) !== data.iExact;
					} else {
						indx = data.iExact.search( $.trim( filter ) );
						return filter === '' ? true : !( c.widgetOptions.filter_startsWith ? indx === 0 : indx >= 0 );
					}
				}
				return null;
			},
			// Look for quotes or equals to get an exact match; ignore type since iExact could be numeric
			exact: function( c, data ) {
				/*jshint eqeqeq:false */
				if ( ts.filter.regex.exact.test( data.iFilter ) ) {
					var txt = data.iFilter.replace( ts.filter.regex.exact, '' ),
						filter = ts.filter.parseFilter( c, txt, data.index, data.parsed[data.index] ) || '';
					return data.anyMatch ? $.inArray( filter, data.rowArray ) >= 0 : filter == data.iExact;
				}
				return null;
			},
			// Look for a range ( using ' to ' or ' - ' ) - see issue #166; thanks matzhu!
			range : function( c, data ) {
				if ( ts.filter.regex.toTest.test( data.iFilter ) ) {
					var result, tmp, range1, range2,
						table = c.table,
						index = data.index,
						parsed = data.parsed[index],
						// make sure the dash is for a range and not indicating a negative number
						query = data.iFilter.split( ts.filter.regex.toSplit );

					tmp = query[0].replace( ts.filter.regex.nondigit, '' ) || '';
					range1 = ts.formatFloat( ts.filter.parseFilter( c, tmp, index, parsed ), table );
					tmp = query[1].replace( ts.filter.regex.nondigit, '' ) || '';
					range2 = ts.formatFloat( ts.filter.parseFilter( c, tmp, index, parsed ), table );
					// parse filter value in case we're comparing numbers ( dates )
					if ( parsed || c.parsers[index].type === 'numeric' ) {
						result = c.parsers[ index ].format( '' + query[0], table, c.$headers.eq( index ), index );
						range1 = ( result !== '' && !isNaN( result ) ) ? result : range1;
						result = c.parsers[ index ].format( '' + query[1], table, c.$headers.eq( index ), index );
						range2 = ( result !== '' && !isNaN( result ) ) ? result : range2;
					}
					if ( ( parsed || c.parsers[ index ].type === 'numeric' ) && !isNaN( range1 ) && !isNaN( range2 ) ) {
						result = data.cache;
					} else {
						tmp = isNaN( data.iExact ) ? data.iExact.replace( ts.filter.regex.nondigit, '' ) : data.iExact;
						result = ts.formatFloat( tmp, table );
					}
					if ( range1 > range2 ) {
						tmp = range1; range1 = range2; range2 = tmp; // swap
					}
					return ( result >= range1 && result <= range2 ) || ( range1 === '' || range2 === '' );
				}
				return null;
			},
			// Look for wild card: ? = single, * = multiple, or | = logical OR
			wild : function( c, data ) {
				if ( /[\?\*\|]/.test( data.iFilter ) ) {
					var index = data.index,
						parsed = data.parsed[ index ],
						query = '' + ( ts.filter.parseFilter( c, data.iFilter, index, parsed ) || '' );
					// look for an exact match with the 'or' unless the 'filter-match' class is found
					if ( !/\?\*/.test( query ) && data.nestedFilters ) {
						query = data.isMatch ? query : '^(' + query + ')$';
					}
					// parsing the filter may not work properly when using wildcards =/
					try {
						return new RegExp(
							query.replace( /\?/g, '\\S{1}' ).replace( /\*/g, '\\S*' ),
							c.widgetOptions.filter_ignoreCase ? 'i' : ''
						)
						.test( data.exact );
					} catch ( error ) {
						return null;
					}
				}
				return null;
			},
			// fuzzy text search; modified from https://github.com/mattyork/fuzzy ( MIT license )
			fuzzy: function( c, data ) {
				if ( /^~/.test( data.iFilter ) ) {
					var indx,
						patternIndx = 0,
						len = data.iExact.length,
						txt = data.iFilter.slice( 1 ),
						pattern = ts.filter.parseFilter( c, txt, data.index, data.parsed[data.index] ) || '';
					for ( indx = 0; indx < len; indx++ ) {
						if ( data.iExact[ indx ] === pattern[ patternIndx ] ) {
							patternIndx += 1;
						}
					}
					if ( patternIndx === pattern.length ) {
						return true;
					}
					return false;
				}
				return null;
			}
		},
		init: function( table, c, wo ) {
			// filter language options
			ts.language = $.extend( true, {}, {
				to  : 'to',
				or  : 'or',
				and : 'and'
			}, ts.language );

			var options, string, txt, $header, column, filters, val, fxn, noSelect,
				regex = ts.filter.regex;
			c.$table.addClass( 'hasFilters' );

			// define timers so using clearTimeout won't cause an undefined error
			wo.searchTimer = null;
			wo.filter_initTimer = null;
			wo.filter_formatterCount = 0;
			wo.filter_formatterInit = [];
			wo.filter_anyColumnSelector = '[data-column="all"],[data-column="any"]';
			wo.filter_multipleColumnSelector = '[data-column*="-"],[data-column*=","]';

			val = '\\{' + ts.filter.regex.query + '\\}';
			$.extend( regex, {
				child : new RegExp( c.cssChildRow ),
				filtered : new RegExp( wo.filter_filteredRow ),
				alreadyFiltered : new RegExp( '(\\s+(' + ts.language.or + '|-|' + ts.language.to + ')\\s+)', 'i' ),
				toTest : new RegExp( '\\s+(-|' + ts.language.to + ')\\s+', 'i' ),
				toSplit : new RegExp( '(?:\\s+(?:-|' + ts.language.to + ')\\s+)', 'gi' ),
				andTest : new RegExp( '\\s+(' + ts.language.and + '|&&)\\s+', 'i' ),
				andSplit : new RegExp( '(?:\\s+(?:' + ts.language.and + '|&&)\\s+)', 'gi' ),
				orSplit : new RegExp( '(?:\\s+(?:' + ts.language.or + ')\\s+|\\|)', 'gi' ),
				iQuery : new RegExp( val, 'i' ),
				igQuery : new RegExp( val, 'ig' )
			});

			// don't build filter row if columnFilters is false or all columns are set to 'filter-false'
			// see issue #156
			val = c.$headers.filter( '.filter-false, .parser-false' ).length;
			if ( wo.filter_columnFilters !== false && val !== c.$headers.length ) {
				// build filter row
				ts.filter.buildRow( table, c, wo );
			}

			txt = 'addRows updateCell update updateRows updateComplete appendCache filterReset filterEnd search '
				.split( ' ' ).join( c.namespace + 'filter ' );
			c.$table.bind( txt, function( event, filter ) {
				val = wo.filter_hideEmpty &&
					$.isEmptyObject( c.cache ) &&
					!( c.delayInit && event.type === 'appendCache' );
				// hide filter row using the 'filtered' class name
				c.$table.find( '.' + tscss.filterRow ).toggleClass( wo.filter_filteredRow, val ); // fixes #450
				if ( !/(search|filter)/.test( event.type ) ) {
					event.stopPropagation();
					ts.filter.buildDefault( table, true );
				}
				if ( event.type === 'filterReset' ) {
					c.$table.find( '.' + tscss.filter ).add( wo.filter_$externalFilters ).val( '' );
					ts.filter.searching( table, [] );
				} else if ( event.type === 'filterEnd' ) {
					ts.filter.buildDefault( table, true );
				} else {
					// send false argument to force a new search; otherwise if the filter hasn't changed,
					// it will return
					filter = event.type === 'search' ? filter :
						event.type === 'updateComplete' ? c.$table.data( 'lastSearch' ) : '';
					if ( /(update|add)/.test( event.type ) && event.type !== 'updateComplete' ) {
						// force a new search since content has changed
						c.lastCombinedFilter = null;
						c.lastSearch = [];
					}
					// pass true ( skipFirst ) to prevent the tablesorter.setFilters function from skipping the first
					// input ensures all inputs are updated when a search is triggered on the table
					// $( 'table' ).trigger( 'search', [...] );
					ts.filter.searching( table, filter, true );
				}
				return false;
			});

			// reset button/link
			if ( wo.filter_reset ) {
				if ( wo.filter_reset instanceof $ ) {
					// reset contains a jQuery object, bind to it
					wo.filter_reset.click( function() {
						c.$table.trigger( 'filterReset' );
					});
				} else if ( $( wo.filter_reset ).length ) {
					// reset is a jQuery selector, use event delegation
					$( document )
						.undelegate( wo.filter_reset, 'click.tsfilter' )
						.delegate( wo.filter_reset, 'click.tsfilter', function() {
							// trigger a reset event, so other functions ( filter_formatter ) know when to reset
							c.$table.trigger( 'filterReset' );
						});
				}
			}
			if ( wo.filter_functions ) {
				for ( column = 0; column < c.columns; column++ ) {
					fxn = ts.getColumnData( table, wo.filter_functions, column );
					if ( fxn ) {
						// remove 'filter-select' from header otherwise the options added here are replaced with
						// all options
						$header = c.$headerIndexed[ column ].removeClass( 'filter-select' );
						// don't build select if 'filter-false' or 'parser-false' set
						noSelect = !( $header.hasClass( 'filter-false' ) || $header.hasClass( 'parser-false' ) );
						options = '';
						if ( fxn === true && noSelect ) {
							ts.filter.buildSelect( table, column );
						} else if ( typeof fxn === 'object' && noSelect ) {
							// add custom drop down list
							for ( string in fxn ) {
								if ( typeof string === 'string' ) {
									options += options === '' ?
										'<option value="">' +
											( $header.data( 'placeholder' ) ||
												$header.attr( 'data-placeholder' ) ||
												wo.filter_placeholder.select ||
												''
											) +
										'</option>' : '';
									val = string;
									txt = string;
									if ( string.indexOf( wo.filter_selectSourceSeparator ) >= 0 ) {
										val = string.split( wo.filter_selectSourceSeparator );
										txt = val[1];
										val = val[0];
									}
									options += '<option ' +
										( txt === val ? '' : 'data-function-name="' + string + '" ' ) +
										'value="' + val + '">' + txt + '</option>';
								}
							}
							c.$table
								.find( 'thead' )
								.find( 'select.' + tscss.filter + '[data-column="' + column + '"]' )
								.append( options );
							txt = wo.filter_selectSource;
							fxn = $.isFunction( txt ) ? true : ts.getColumnData( table, txt, column );
							if ( fxn ) {
								// updating so the extra options are appended
								ts.filter.buildSelect( c.table, column, '', true, $header.hasClass( wo.filter_onlyAvail ) );
							}
						}
					}
				}
			}
			// not really updating, but if the column has both the 'filter-select' class &
			// filter_functions set to true, it would append the same options twice.
			ts.filter.buildDefault( table, true );

			ts.filter.bindSearch( table, c.$table.find( '.' + tscss.filter ), true );
			if ( wo.filter_external ) {
				ts.filter.bindSearch( table, wo.filter_external );
			}

			if ( wo.filter_hideFilters ) {
				ts.filter.hideFilters( table, c );
			}

			// show processing icon
			if ( c.showProcessing ) {
				txt = 'filterStart filterEnd '.split( ' ' ).join( c.namespace + 'filter ' );
				c.$table
					.unbind( txt.replace( /\s+/g, ' ' ) )
					.bind( txt, function( event, columns ) {
					// only add processing to certain columns to all columns
					$header = ( columns ) ?
						c.$table
							.find( '.' + tscss.header )
							.filter( '[data-column]' )
							.filter( function() {
								return columns[ $( this ).data( 'column' ) ] !== '';
							}) : '';
					ts.isProcessing( table, event.type === 'filterStart', columns ? $header : '' );
				});
			}

			// set filtered rows count ( intially unfiltered )
			c.filteredRows = c.totalRows;

			// add default values
			txt = 'tablesorter-initialized pagerBeforeInitialized '.split( ' ' ).join( c.namespace + 'filter ' );
			c.$table
			.unbind( txt.replace( /\s+/g, ' ' ) )
			.bind( txt, function() {
				// redefine 'wo' as it does not update properly inside this callback
				var wo = this.config.widgetOptions;
				filters = ts.filter.setDefaults( table, c, wo ) || [];
				if ( filters.length ) {
					// prevent delayInit from triggering a cache build if filters are empty
					if ( !( c.delayInit && filters.join( '' ) === '' ) ) {
						ts.setFilters( table, filters, true );
					}
				}
				c.$table.trigger( 'filterFomatterUpdate' );
				// trigger init after setTimeout to prevent multiple filterStart/End/Init triggers
				setTimeout( function() {
					if ( !wo.filter_initialized ) {
						ts.filter.filterInitComplete( c );
					}
				}, 100 );
			});
			// if filter widget is added after pager has initialized; then set filter init flag
			if ( c.pager && c.pager.initialized && !wo.filter_initialized ) {
				c.$table.trigger( 'filterFomatterUpdate' );
				setTimeout( function() {
					ts.filter.filterInitComplete( c );
				}, 100 );
			}
		},
		// $cell parameter, but not the config, is passed to the filter_formatters,
		// so we have to work with it instead
		formatterUpdated: function( $cell, column ) {
			var wo = $cell.closest( 'table' )[0].config.widgetOptions;
			if ( !wo.filter_initialized ) {
				// add updates by column since this function
				// may be called numerous times before initialization
				wo.filter_formatterInit[ column ] = 1;
			}
		},
		filterInitComplete: function( c ) {
			var indx, len,
				wo = c.widgetOptions,
				count = 0,
				completed = function() {
					wo.filter_initialized = true;
					c.$table.trigger( 'filterInit', c );
					ts.filter.findRows( c.table, c.$table.data( 'lastSearch' ) || [] );
				};
			if ( $.isEmptyObject( wo.filter_formatter ) ) {
				completed();
			} else {
				len = wo.filter_formatterInit.length;
				for ( indx = 0; indx < len; indx++ ) {
					if ( wo.filter_formatterInit[ indx ] === 1 ) {
						count++;
					}
				}
				clearTimeout( wo.filter_initTimer );
				if ( !wo.filter_initialized && count === wo.filter_formatterCount ) {
					// filter widget initialized
					completed();
				} else if ( !wo.filter_initialized ) {
					// fall back in case a filter_formatter doesn't call
					// $.tablesorter.filter.formatterUpdated( $cell, column ), and the count is off
					wo.filter_initTimer = setTimeout( function() {
						completed();
					}, 500 );
				}
			}
		},
		setDefaults: function( table, c, wo ) {
			var isArray, saved, indx, col, $filters,
				// get current ( default ) filters
				filters = ts.getFilters( table ) || [];
			if ( wo.filter_saveFilters && ts.storage ) {
				saved = ts.storage( table, 'tablesorter-filters' ) || [];
				isArray = $.isArray( saved );
				// make sure we're not just getting an empty array
				if ( !( isArray && saved.join( '' ) === '' || !isArray ) ) {
					filters = saved;
				}
			}
			// if no filters saved, then check default settings
			if ( filters.join( '' ) === '' ) {
				// allow adding default setting to external filters
				$filters = c.$headers.add( wo.filter_$externalFilters )
					.filter( '[' + wo.filter_defaultAttrib + ']' );
				for ( indx = 0; indx <= c.columns; indx++ ) {
					// include data-column='all' external filters
					col = indx === c.columns ? 'all' : indx;
					filters[indx] = $filters
						.filter( '[data-column="' + col + '"]' )
						.attr( wo.filter_defaultAttrib ) || filters[indx] || '';
				}
			}
			c.$table.data( 'lastSearch', filters );
			return filters;
		},
		parseFilter: function( c, filter, column, parsed ) {
			return parsed ? c.parsers[column].format( filter, c.table, [], column ) : filter;
		},
		buildRow: function( table, c, wo ) {
			var col, column, $header, buildSelect, disabled, name, ffxn, tmp,
				// c.columns defined in computeThIndexes()
				cellFilter = wo.filter_cellFilter,
				columns = c.columns,
				arry = $.isArray( cellFilter ),
				buildFilter = '<tr role="row" class="' + tscss.filterRow + ' ' + c.cssIgnoreRow + '">';
			for ( column = 0; column < columns; column++ ) {
				buildFilter += '<td';
				if ( arry ) {
					buildFilter += ( cellFilter[ column ] ? ' class="' + cellFilter[ column ] + '"' : '' );
				} else {
					buildFilter += ( cellFilter !== '' ? ' class="' + cellFilter + '"' : '' );
				}
				buildFilter += '></td>';
			}
			c.$filters = $( buildFilter += '</tr>' )
				.appendTo( c.$table.children( 'thead' ).eq( 0 ) )
				.find( 'td' );
			// build each filter input
			for ( column = 0; column < columns; column++ ) {
				disabled = false;
				// assuming last cell of a column is the main column
				$header = c.$headerIndexed[ column ];
				ffxn = ts.getColumnData( table, wo.filter_functions, column );
				buildSelect = ( wo.filter_functions && ffxn && typeof ffxn !== 'function' ) ||
					$header.hasClass( 'filter-select' );
				// get data from jQuery data, metadata, headers option or header class name
				col = ts.getColumnData( table, c.headers, column );
				disabled = ts.getData( $header[0], col, 'filter' ) === 'false' ||
					ts.getData( $header[0], col, 'parser' ) === 'false';

				if ( buildSelect ) {
					buildFilter = $( '<select>' ).appendTo( c.$filters.eq( column ) );
				} else {
					ffxn = ts.getColumnData( table, wo.filter_formatter, column );
					if ( ffxn ) {
						wo.filter_formatterCount++;
						buildFilter = ffxn( c.$filters.eq( column ), column );
						// no element returned, so lets go find it
						if ( buildFilter && buildFilter.length === 0 ) {
							buildFilter = c.$filters.eq( column ).children( 'input' );
						}
						// element not in DOM, so lets attach it
						if ( buildFilter && ( buildFilter.parent().length === 0 ||
							( buildFilter.parent().length && buildFilter.parent()[0] !== c.$filters[column] ) ) ) {
							c.$filters.eq( column ).append( buildFilter );
						}
					} else {
						buildFilter = $( '<input type="search">' ).appendTo( c.$filters.eq( column ) );
					}
					if ( buildFilter ) {
						tmp = $header.data( 'placeholder' ) ||
							$header.attr( 'data-placeholder' ) ||
							wo.filter_placeholder.search || '';
						buildFilter.attr( 'placeholder', tmp );
					}
				}
				if ( buildFilter ) {
					// add filter class name
					name = ( $.isArray( wo.filter_cssFilter ) ?
						( typeof wo.filter_cssFilter[column] !== 'undefined' ? wo.filter_cssFilter[column] || '' : '' ) :
						wo.filter_cssFilter ) || '';
					buildFilter.addClass( tscss.filter + ' ' + name ).attr( 'data-column', column );
					if ( disabled ) {
						buildFilter.attr( 'placeholder', '' ).addClass( tscss.filterDisabled )[0].disabled = true;
					}
				}
			}
		},
		bindSearch: function( table, $el, internal ) {
			table = $( table )[0];
			$el = $( $el ); // allow passing a selector string
			if ( !$el.length ) { return; }
			var tmp,
				c = table.config,
				wo = c.widgetOptions,
				namespace = c.namespace + 'filter',
				$ext = wo.filter_$externalFilters;
			if ( internal !== true ) {
				// save anyMatch element
				tmp = wo.filter_anyColumnSelector + ',' + wo.filter_multipleColumnSelector;
				wo.filter_$anyMatch = $el.filter( tmp );
				if ( $ext && $ext.length ) {
					wo.filter_$externalFilters = wo.filter_$externalFilters.add( $el );
				} else {
					wo.filter_$externalFilters = $el;
				}
				// update values ( external filters added after table initialization )
				ts.setFilters( table, c.$table.data( 'lastSearch' ) || [], internal === false );
			}
			// unbind events
			tmp = ( 'keypress keyup search change '.split( ' ' ).join( namespace + ' ' ) );
			$el
			// use data attribute instead of jQuery data since the head is cloned without including
			// the data/binding
			.attr( 'data-lastSearchTime', new Date().getTime() )
			.unbind( tmp.replace( /\s+/g, ' ' ) )
			// include change for select - fixes #473
			.bind( 'keyup' + namespace, function( event ) {
				$( this ).attr( 'data-lastSearchTime', new Date().getTime() );
				// emulate what webkit does.... escape clears the filter
				if ( event.which === 27 ) {
					this.value = '';
				// live search
				} else if ( wo.filter_liveSearch === false ) {
					return;
					// don't return if the search value is empty ( all rows need to be revealed )
				} else if ( this.value !== '' && (
					// liveSearch can contain a min value length; ignore arrow and meta keys, but allow backspace
					( typeof wo.filter_liveSearch === 'number' && this.value.length < wo.filter_liveSearch ) ||
					// let return & backspace continue on, but ignore arrows & non-valid characters
					( event.which !== 13 && event.which !== 8 &&
						( event.which < 32 || ( event.which >= 37 && event.which <= 40 ) ) ) ) ) {
					return;
				}
				// change event = no delay; last true flag tells getFilters to skip newest timed input
				ts.filter.searching( table, true, true );
			})
			.bind( 'search change keypress '.split( ' ' ).join( namespace + ' ' ), function( event ) {
				var column = $( this ).data( 'column' );
				// don't allow 'change' event to process if the input value is the same - fixes #685
				if ( event.which === 13 || event.type === 'search' ||
					event.type === 'change' && this.value !== c.lastSearch[column] ) {
					event.preventDefault();
					// init search with no delay
					$( this ).attr( 'data-lastSearchTime', new Date().getTime() );
					ts.filter.searching( table, false, true );
				}
			});
		},
		searching: function( table, filter, skipFirst ) {
			var wo = table.config.widgetOptions;
			clearTimeout( wo.searchTimer );
			if ( typeof filter === 'undefined' || filter === true ) {
				// delay filtering
				wo.searchTimer = setTimeout( function() {
					ts.filter.checkFilters( table, filter, skipFirst );
				}, wo.filter_liveSearch ? wo.filter_searchDelay : 10 );
			} else {
				// skip delay
				ts.filter.checkFilters( table, filter, skipFirst );
			}
		},
		checkFilters: function( table, filter, skipFirst ) {
			var c = table.config,
				wo = c.widgetOptions,
				filterArray = $.isArray( filter ),
				filters = ( filterArray ) ? filter : ts.getFilters( table, true ),
				combinedFilters = ( filters || [] ).join( '' ); // combined filter values
			// prevent errors if delay init is set
			if ( $.isEmptyObject( c.cache ) ) {
				// update cache if delayInit set & pager has initialized ( after user initiates a search )
				if ( c.delayInit && c.pager && c.pager.initialized ) {
					c.$table.trigger( 'updateCache', [ function() {
						ts.filter.checkFilters( table, false, skipFirst );
					} ] );
				}
				return;
			}
			// add filter array back into inputs
			if ( filterArray ) {
				ts.setFilters( table, filters, false, skipFirst !== true );
				if ( !wo.filter_initialized ) { c.lastCombinedFilter = ''; }
			}
			if ( wo.filter_hideFilters ) {
				// show/hide filter row as needed
				c.$table
					.find( '.' + tscss.filterRow )
					.trigger( combinedFilters === '' ? 'mouseleave' : 'mouseenter' );
			}
			// return if the last search is the same; but filter === false when updating the search
			// see example-widget-filter.html filter toggle buttons
			if ( c.lastCombinedFilter === combinedFilters && filter !== false ) {
				return;
			} else if ( filter === false ) {
				// force filter refresh
				c.lastCombinedFilter = null;
				c.lastSearch = [];
			}
			if ( wo.filter_initialized ) {
				c.$table.trigger( 'filterStart', [ filters ] );
			}
			if ( c.showProcessing ) {
				// give it time for the processing icon to kick in
				setTimeout( function() {
					ts.filter.findRows( table, filters, combinedFilters );
					return false;
				}, 30 );
			} else {
				ts.filter.findRows( table, filters, combinedFilters );
				return false;
			}
		},
		hideFilters: function( table, c ) {
			var timer;
			c.$table
				.find( '.' + tscss.filterRow )
				.bind( 'mouseenter mouseleave', function( e ) {
					// save event object - http://bugs.jquery.com/ticket/12140
					var event = e,
						$filterRow = $( this );
					clearTimeout( timer );
					timer = setTimeout( function() {
						if ( /enter|over/.test( event.type ) ) {
							$filterRow.removeClass( tscss.filterRowHide );
						} else {
							// don't hide if input has focus
							// $( ':focus' ) needs jQuery 1.6+
							if ( $( document.activeElement ).closest( 'tr' )[0] !== $filterRow[0] ) {
								// don't hide row if any filter has a value
								if ( c.lastCombinedFilter === '' ) {
									$filterRow.addClass( tscss.filterRowHide );
								}
							}
						}
					}, 200 );
				})
				.find( 'input, select' ).bind( 'focus blur', function( e ) {
					var event = e,
						$row = $( this ).closest( 'tr' );
					clearTimeout( timer );
					timer = setTimeout( function() {
						clearTimeout( timer );
						// don't hide row if any filter has a value
						if ( ts.getFilters( c.$table ).join( '' ) === '' ) {
							$row.toggleClass( tscss.filterRowHide, event.type !== 'focus' );
						}
					}, 200 );
				});
		},
		defaultFilter: function( filter, mask ) {
			if ( filter === '' ) { return filter; }
			var regex = ts.filter.regex.iQuery,
				maskLen = mask.match( ts.filter.regex.igQuery ).length,
				query = maskLen > 1 ? $.trim( filter ).split( /\s/ ) : [ $.trim( filter ) ],
				len = query.length - 1,
				indx = 0,
				val = mask;
			if ( len < 1 && maskLen > 1 ) {
				// only one 'word' in query but mask has >1 slots
				query[1] = query[0];
			}
			// replace all {query} with query words...
			// if query = 'Bob', then convert mask from '!{query}' to '!Bob'
			// if query = 'Bob Joe Frank', then convert mask '{q} OR {q}' to 'Bob OR Joe OR Frank'
			while ( regex.test( val ) ) {
				val = val.replace( regex, query[indx++] || '' );
				if ( regex.test( val ) && indx < len && ( query[indx] || '' ) !== '' ) {
					val = mask.replace( regex, val );
				}
			}
			return val;
		},
		getLatestSearch: function( $input ) {
			if ( $input ) {
				return $input.sort( function( a, b ) {
					return $( b ).attr( 'data-lastSearchTime' ) - $( a ).attr( 'data-lastSearchTime' );
				});
			}
			return $input || $();
		},
		multipleColumns: function( c, $input ) {
			// look for multiple columns '1-3,4-6,8' in data-column
			var temp, ranges, range, start, end, singles, i, indx, len,
				wo = c.widgetOptions,
				// only target 'all' column inputs on initialization
				// & don't target 'all' column inputs if they don't exist
				targets = wo.filter_initialized || !$input.filter( wo.filter_anyColumnSelector ).length,
				columns = [],
				val = $.trim( ts.filter.getLatestSearch( $input ).attr( 'data-column' ) || '' );
			// process column range
			if ( targets && /-/.test( val ) ) {
				ranges = val.match( /(\d+)\s*-\s*(\d+)/g );
				len = ranges.length;
				for ( indx = 0; indx < len; indx++ ) {
					range = ranges[indx].split( /\s*-\s*/ );
					start = parseInt( range[0], 10 ) || 0;
					end = parseInt( range[1], 10 ) || ( c.columns - 1 );
					if ( start > end ) {
						temp = start; start = end; end = temp; // swap
					}
					if ( end >= c.columns ) {
						end = c.columns - 1;
					}
					for ( ; start <= end; start++ ) {
						columns.push( start );
					}
					// remove processed range from val
					val = val.replace( ranges[ indx ], '' );
				}
			}
			// process single columns
			if ( targets && /,/.test( val ) ) {
				singles = val.split( /\s*,\s*/ );
				len = singles.length;
				for ( i = 0; i < len; i++ ) {
					if ( singles[ i ] !== '' ) {
						indx = parseInt( singles[ i ], 10 );
						if ( indx < c.columns ) {
							columns.push( indx );
						}
					}
				}
			}
			// return all columns
			if ( !columns.length ) {
				for ( indx = 0; indx < c.columns; indx++ ) {
					columns.push( indx );
				}
			}
			return columns;
		},
		processTypes: function( c, data, vars ) {
			var ffxn,
				filterMatched = null,
				matches = null;
			for ( ffxn in ts.filter.types ) {
				if ( $.inArray( ffxn, vars.excludeMatch ) < 0 && matches === null ) {
					matches = ts.filter.types[ffxn]( c, data, vars );
					if ( matches !== null ) {
						filterMatched = matches;
					}
				}
			}
			return filterMatched;
		},
		processRow: function( c, data, vars ) {
			var columnIndex, hasSelect, result, val, filterMatched,
				fxn, ffxn, txt,
				regex = ts.filter.regex,
				wo = c.widgetOptions,
				showRow = true;
			data.$cells = data.$row.children();

			if ( data.anyMatchFlag ) {
				// look for multiple columns '1-3,4-6,8'
				columnIndex = ts.filter.multipleColumns( c, wo.filter_$anyMatch );
				data.anyMatch = true;
				data.isMatch = true;
				data.rowArray = data.$cells.map( function( i ) {
					if ( $.inArray( i, columnIndex ) > -1 ) {
						if ( data.parsed[ i ] ) {
							txt = data.cacheArray[ i ];
						} else {
							txt = data.rawArray[ i ];
							txt = $.trim( wo.filter_ignoreCase ? txt.toLowerCase() : txt );
							if ( c.sortLocaleCompare ) {
								txt = ts.replaceAccents( txt );
							}
						}
						return txt;
					}
				}).get();
				data.filter = data.anyMatchFilter;
				data.iFilter = data.iAnyMatchFilter;
				data.exact = data.rowArray.join( ' ' );
				data.iExact = wo.filter_ignoreCase ? data.exact.toLowerCase() : data.exact;
				data.cache = data.cacheArray.slice( 0, -1 ).join( ' ' );

				vars.excludeMatch = vars.noAnyMatch;
				filterMatched = ts.filter.processTypes( c, data, vars );

				if ( filterMatched !== null ) {
					showRow = filterMatched;
				} else {
					if ( wo.filter_startsWith ) {
						showRow = false;
						columnIndex = c.columns;
						while ( !showRow && columnIndex > 0 ) {
							columnIndex--;
							showRow = showRow || data.rowArray[ columnIndex ].indexOf( data.iFilter ) === 0;
						}
					} else {
						showRow = ( data.iExact + data.childRowText ).indexOf( data.iFilter ) >= 0;
					}
				}
				data.anyMatch = false;
				// no other filters to process
				if ( data.filters.join( '' ) === data.filter ) {
					return showRow;
				}
			}

			for ( columnIndex = 0; columnIndex < c.columns; columnIndex++ ) {
				data.filter = data.filters[ columnIndex ];
				data.index = columnIndex;

				// filter types to exclude, per column
				vars.excludeMatch = vars.excludeFilter[ columnIndex ];

				// ignore if filter is empty or disabled
				if ( data.filter ) {
					data.cache = data.cacheArray[ columnIndex ];
					// check if column data should be from the cell or from parsed data
					if ( wo.filter_useParsedData || data.parsed[ columnIndex ] ) {
						data.exact = data.cache;
					} else {
						result = data.rawArray[ columnIndex ] || '';
						data.exact = c.sortLocaleCompare ? ts.replaceAccents( result ) : result; // issue #405
					}
					data.iExact = !regex.type.test( typeof data.exact ) && wo.filter_ignoreCase ?
						data.exact.toLowerCase() : data.exact;

					data.isMatch = c.$headerIndexed[ data.index ].hasClass( 'filter-match' );

					result = showRow; // if showRow is true, show that row

					// in case select filter option has a different value vs text 'a - z|A through Z'
					ffxn = wo.filter_columnFilters ?
						c.$filters.add( c.$externalFilters )
							.filter( '[data-column="' + columnIndex + '"]' )
							.find( 'select option:selected' )
							.attr( 'data-function-name' ) || '' : '';
					// replace accents - see #357
					if ( c.sortLocaleCompare ) {
						data.filter = ts.replaceAccents( data.filter );
					}

					val = true;
					if ( wo.filter_defaultFilter && regex.iQuery.test( vars.defaultColFilter[ columnIndex ] ) ) {
						data.filter = ts.filter.defaultFilter( data.filter, vars.defaultColFilter[ columnIndex ] );
						// val is used to indicate that a filter select is using a default filter;
						// so we override the exact & partial matches
						val = false;
					}
					// data.iFilter = case insensitive ( if wo.filter_ignoreCase is true ),
					// data.filter = case sensitive
					data.iFilter = wo.filter_ignoreCase ? ( data.filter || '' ).toLowerCase() : data.filter;
					fxn = vars.functions[ columnIndex ];
					hasSelect = c.$headerIndexed[ columnIndex ].hasClass( 'filter-select' );
					filterMatched = null;
					if ( fxn || ( hasSelect && val ) ) {
						if ( fxn === true || hasSelect ) {
							// default selector uses exact match unless 'filter-match' class is found
							filterMatched = data.isMatch ?
								data.iExact.search( data.iFilter ) >= 0 :
								data.filter === data.exact;
						} else if ( typeof fxn === 'function' ) {
							// filter callback( exact cell content, parser normalized content,
							// filter input value, column index, jQuery row object )
							filterMatched = fxn( data.exact, data.cache, data.filter, columnIndex, data.$row, c, data );
						} else if ( typeof fxn[ ffxn || data.filter ] === 'function' ) {
							// selector option function
							txt = ffxn || data.filter;
							filterMatched =
								fxn[ txt ]( data.exact, data.cache, data.filter, columnIndex, data.$row, c, data );
						}
					}
					if ( filterMatched === null ) {
						// cycle through the different filters
						// filters return a boolean or null if nothing matches
						filterMatched = ts.filter.processTypes( c, data, vars );
						if ( filterMatched !== null ) {
							result = filterMatched;
						// Look for match, and add child row data for matching
						} else {
							txt = ( data.iExact + data.childRowText )
								.indexOf( ts.filter.parseFilter( c, data.iFilter, columnIndex, data.parsed[ columnIndex ] ) );
							result = ( ( !wo.filter_startsWith && txt >= 0 ) || ( wo.filter_startsWith && txt === 0 ) );
						}
					} else {
						result = filterMatched;
					}
					showRow = ( result ) ? showRow : false;
				}
			}
			return showRow;
		},
		findRows: function( table, filters, combinedFilters ) {
			if ( table.config.lastCombinedFilter === combinedFilters ||
				!table.config.widgetOptions.filter_initialized ) {
				return;
			}
			var len, norm_rows, rowData, $rows, rowIndex, tbodyIndex, $tbody, columnIndex,
				isChild, childRow, lastSearch, showRow, time, val, indx,
				notFiltered, searchFiltered, query, injected, res, id, txt,
				storedFilters = $.extend( [], filters ),
				regex = ts.filter.regex,
				c = table.config,
				wo = c.widgetOptions,
				// data object passed to filters; anyMatch is a flag for the filters
				data = {
					anyMatch: false,
					filters: filters,
					// regex filter type cache
					filter_regexCache : []
				},
				vars = {
					// anyMatch really screws up with these types of filters
					noAnyMatch: [ 'range', 'notMatch',  'operators' ],
					// cache filter variables that use ts.getColumnData in the main loop
					functions : [],
					excludeFilter : [],
					defaultColFilter : [],
					defaultAnyFilter : ts.getColumnData( table, wo.filter_defaultFilter, c.columns, true ) || ''
				};

			// parse columns after formatter, in case the class is added at that point
			data.parsed = c.$headers.map( function( columnIndex ) {
				return c.parsers && c.parsers[ columnIndex ] &&
					// force parsing if parser type is numeric
					c.parsers[ columnIndex ].parsed ||
					// getData won't return 'parsed' if other 'filter-' class names exist
					// ( e.g. <th class="filter-select filter-parsed"> )
					ts.getData && ts.getData( c.$headerIndexed[ columnIndex ],
						ts.getColumnData( table, c.headers, columnIndex ), 'filter' ) === 'parsed' ||
					$( this ).hasClass( 'filter-parsed' );
			}).get();

			for ( columnIndex = 0; columnIndex < c.columns; columnIndex++ ) {
				vars.functions[ columnIndex ] =
					ts.getColumnData( table, wo.filter_functions, columnIndex );
				vars.defaultColFilter[ columnIndex ] =
					ts.getColumnData( table, wo.filter_defaultFilter, columnIndex ) || '';
				vars.excludeFilter[ columnIndex ] =
					( ts.getColumnData( table, wo.filter_excludeFilter, columnIndex, true ) || '' ).split( /\s+/ );
			}

			if ( c.debug ) {
				console.log( 'Filter: Starting filter widget search', filters );
				time = new Date();
			}
			// filtered rows count
			c.filteredRows = 0;
			c.totalRows = 0;
			// combindedFilters are undefined on init
			combinedFilters = ( storedFilters || [] ).join( '' );

			for ( tbodyIndex = 0; tbodyIndex < c.$tbodies.length; tbodyIndex++ ) {
				$tbody = ts.processTbody( table, c.$tbodies.eq( tbodyIndex ), true );
				// skip child rows & widget added ( removable ) rows - fixes #448 thanks to @hempel!
				// $rows = $tbody.children( 'tr' ).not( c.selectorRemove );
				columnIndex = c.columns;
				// convert stored rows into a jQuery object
				norm_rows = c.cache[ tbodyIndex ].normalized;
				$rows = $( $.map( norm_rows, function( el ) {
					return el[ columnIndex ].$row.get();
				}) );

				if ( combinedFilters === '' || wo.filter_serversideFiltering ) {
					$rows
						.removeClass( wo.filter_filteredRow )
						.not( '.' + c.cssChildRow )
						.css( 'display', '' );
				} else {
					// filter out child rows
					$rows = $rows.not( '.' + c.cssChildRow );
					len = $rows.length;

					if ( ( wo.filter_$anyMatch && wo.filter_$anyMatch.length ) ||
						typeof filters[c.columns] !== 'undefined' ) {
						data.anyMatchFlag = true;
						data.anyMatchFilter = '' + (
							filters[ c.columns ] ||
							wo.filter_$anyMatch && ts.filter.getLatestSearch( wo.filter_$anyMatch ).val() ||
							''
						);
						if ( wo.filter_columnAnyMatch ) {
							// specific columns search
							query = data.anyMatchFilter.split( regex.andSplit );
							injected = false;
							for ( indx = 0; indx < query.length; indx++ ) {
								res = query[ indx ].split( ':' );
								if ( res.length > 1 ) {
									// make the column a one-based index ( non-developers start counting from one :P )
									id = parseInt( res[0], 10 ) - 1;
									if ( id >= 0 && id < c.columns ) { // if id is an integer
										filters[ id ] = res[1];
										query.splice( indx, 1 );
										indx--;
										injected = true;
									}
								}
							}
							if ( injected ) {
								data.anyMatchFilter = query.join( ' && ' );
							}
						}
					}

					// optimize searching only through already filtered rows - see #313
					searchFiltered = wo.filter_searchFiltered;
					lastSearch = c.lastSearch || c.$table.data( 'lastSearch' ) || [];
					if ( searchFiltered ) {
						// cycle through all filters; include last ( columnIndex + 1 = match any column ). Fixes #669
						for ( indx = 0; indx < columnIndex + 1; indx++ ) {
							val = filters[indx] || '';
							// break out of loop if we've already determined not to search filtered rows
							if ( !searchFiltered ) { indx = columnIndex; }
							// search already filtered rows if...
							searchFiltered = searchFiltered && lastSearch.length &&
								// there are no changes from beginning of filter
								val.indexOf( lastSearch[indx] || '' ) === 0 &&
								// if there is NOT a logical 'or', or range ( 'to' or '-' ) in the string
								!regex.alreadyFiltered.test( val ) &&
								// if we are not doing exact matches, using '|' ( logical or ) or not '!'
								!/[=\"\|!]/.test( val ) &&
								// don't search only filtered if the value is negative
								// ( '> -10' => '> -100' will ignore hidden rows )
								!( /(>=?\s*-\d)/.test( val ) || /(<=?\s*\d)/.test( val ) ) &&
								// if filtering using a select without a 'filter-match' class ( exact match ) - fixes #593
								!( val !== '' && c.$filters && c.$filters.eq( indx ).find( 'select' ).length &&
									!c.$headerIndexed[indx].hasClass( 'filter-match' ) );
						}
					}
					notFiltered = $rows.not( '.' + wo.filter_filteredRow ).length;
					// can't search when all rows are hidden - this happens when looking for exact matches
					if ( searchFiltered && notFiltered === 0 ) { searchFiltered = false; }
					if ( c.debug ) {
						console.log( 'Filter: Searching through ' +
							( searchFiltered && notFiltered < len ? notFiltered : 'all' ) + ' rows' );
					}
					if ( data.anyMatchFlag ) {
						if ( c.sortLocaleCompare ) {
							// replace accents
							data.anyMatchFilter = ts.replaceAccents( data.anyMatchFilter );
						}
						if ( wo.filter_defaultFilter && regex.iQuery.test( vars.defaultAnyFilter ) ) {
							data.anyMatchFilter = ts.filter.defaultFilter( data.anyMatchFilter, vars.defaultAnyFilter );
							// clear search filtered flag because default filters are not saved to the last search
							searchFiltered = false;
						}
						// make iAnyMatchFilter lowercase unless both filter widget & core ignoreCase options are true
						// when c.ignoreCase is true, the cache contains all lower case data
						data.iAnyMatchFilter = !( wo.filter_ignoreCase && c.ignoreCase ) ?
							data.anyMatchFilter :
							data.anyMatchFilter.toLowerCase();
					}

					// loop through the rows
					for ( rowIndex = 0; rowIndex < len; rowIndex++ ) {

						txt = $rows[ rowIndex ].className;
						// the first row can never be a child row
						isChild = rowIndex && regex.child.test( txt );
						// skip child rows & already filtered rows
						if ( isChild || ( searchFiltered && regex.filtered.test( txt ) ) ) {
							continue;
						}

						data.$row = $rows.eq( rowIndex );
						data.cacheArray = norm_rows[ rowIndex ];
						rowData = data.cacheArray[ c.columns ];
						data.rawArray = rowData.raw;
						data.childRowText = '';

						if ( !wo.filter_childByColumn ) {
							txt = '';
							// child row cached text
							childRow = rowData.child;
							// so, if 'table.config.widgetOptions.filter_childRows' is true and there is
							// a match anywhere in the child row, then it will make the row visible
							// checked here so the option can be changed dynamically
							for ( indx = 0; indx < childRow.length; indx++ ) {
								txt += ' ' + childRow[indx].join( '' ) || '';
							}
							data.childRowText = wo.filter_childRows ?
								( wo.filter_ignoreCase ? txt.toLowerCase() : txt ) :
								'';
						}

						showRow = ts.filter.processRow( c, data, vars );
						childRow = rowData.$row.filter( ':gt( 0 )' );

						if ( wo.filter_childRows && childRow.length ) {
							if ( wo.filter_childByColumn ) {
								// cycle through each child row
								for ( indx = 0; indx < childRow.length; indx++ ) {
									data.$row = childRow.eq( indx );
									data.cacheArray = rowData.child[ indx ];
									data.rawArray = data.cacheArray;
									// use OR comparison on child rows
									showRow = showRow || ts.filter.processRow( c, data, vars );
								}
							}
							childRow.toggleClass( wo.filter_filteredRow, !showRow );
						}

						rowData.$row
							.toggleClass( wo.filter_filteredRow, !showRow )[0]
							.display = showRow ? '' : 'none';
					}
				}
				c.filteredRows += $rows.not( '.' + wo.filter_filteredRow ).length;
				c.totalRows += $rows.length;
				ts.processTbody( table, $tbody, false );
			}
			c.lastCombinedFilter = combinedFilters; // save last search
			// don't save 'filters' directly since it may have altered ( AnyMatch column searches )
			c.lastSearch = storedFilters;
			c.$table.data( 'lastSearch', storedFilters );
			if ( wo.filter_saveFilters && ts.storage ) {
				ts.storage( table, 'tablesorter-filters', storedFilters );
			}
			if ( c.debug ) {
				console.log( 'Completed filter widget search' + ts.benchmark(time) );
			}
			if ( wo.filter_initialized ) {
				c.$table.trigger( 'filterEnd', c );
			}
			setTimeout( function() {
				c.$table.trigger( 'applyWidgets' ); // make sure zebra widget is applied
			}, 0 );
		},
		getOptionSource: function( table, column, onlyAvail ) {
			table = $( table )[0];
			var cts, txt, indx, len,
				c = table.config,
				wo = c.widgetOptions,
				parsed = [],
				arry = false,
				source = wo.filter_selectSource,
				last = c.$table.data( 'lastSearch' ) || [],
				fxn = $.isFunction( source ) ? true : ts.getColumnData( table, source, column );

			if ( onlyAvail && last[column] !== '' ) {
				onlyAvail = false;
			}

			// filter select source option
			if ( fxn === true ) {
				// OVERALL source
				arry = source( table, column, onlyAvail );
			} else if ( fxn instanceof $ || ( $.type( fxn ) === 'string' && fxn.indexOf( '</option>' ) >= 0 ) ) {
				// selectSource is a jQuery object or string of options
				return fxn;
			} else if ( $.isArray( fxn ) ) {
				arry = fxn;
			} else if ( $.type( source ) === 'object' && fxn ) {
				// custom select source function for a SPECIFIC COLUMN
				arry = fxn( table, column, onlyAvail );
			}
			if ( arry === false ) {
				// fall back to original method
				arry = ts.filter.getOptions( table, column, onlyAvail );
			}

			// get unique elements and sort the list
			// if $.tablesorter.sortText exists ( not in the original tablesorter ),
			// then natural sort the list otherwise use a basic sort
			arry = $.grep( arry, function( value, indx ) {
				return $.inArray( value, arry ) === indx;
			});

			if ( c.$headerIndexed[ column ].hasClass( 'filter-select-nosort' ) ) {
				// unsorted select options
				return arry;
			} else {
				len = arry.length;
				// parse select option values
				for ( indx = 0; indx < len; indx++ ) {
					txt = arry[ indx ];
					// parse array data using set column parser; this DOES NOT pass the original
					// table cell to the parser format function
					parsed.push({
						t : txt,
						// check parser length - fixes #934
						p : c.parsers && c.parsers.length && c.parsers[ column ].format( txt, table, [], column ) || txt
					});
				}

				// sort parsed select options
				cts = c.textSorter || '';
				parsed.sort( function( a, b ) {
					// sortNatural breaks if you don't pass it strings
					var x = a.p.toString(),
						y = b.p.toString();
					if ( $.isFunction( cts ) ) {
						// custom OVERALL text sorter
						return cts( x, y, true, column, table );
					} else if ( typeof cts === 'object' && cts.hasOwnProperty( column ) ) {
						// custom text sorter for a SPECIFIC COLUMN
						return cts[column]( x, y, true, column, table );
					} else if ( ts.sortNatural ) {
						// fall back to natural sort
						return ts.sortNatural( x, y );
					}
					// using an older version! do a basic sort
					return true;
				});
				// rebuild arry from sorted parsed data
				arry = [];
				len = parsed.length;
				for ( indx = 0; indx < len; indx++ ) {
					arry.push( parsed[indx].t );
				}
				return arry;
			}
		},
		getOptions: function( table, column, onlyAvail ) {
			table = $( table )[0];
			var rowIndex, tbodyIndex, len, row, cache,
				c = table.config,
				wo = c.widgetOptions,
				arry = [];
			for ( tbodyIndex = 0; tbodyIndex < c.$tbodies.length; tbodyIndex++ ) {
				cache = c.cache[tbodyIndex];
				len = c.cache[tbodyIndex].normalized.length;
				// loop through the rows
				for ( rowIndex = 0; rowIndex < len; rowIndex++ ) {
					// get cached row from cache.row ( old ) or row data object
					// ( new; last item in normalized array )
					row = cache.row ?
						cache.row[ rowIndex ] :
						cache.normalized[ rowIndex ][ c.columns ].$row[0];
					// check if has class filtered
					if ( onlyAvail && row.className.match( wo.filter_filteredRow ) ) {
						continue;
					}
					// get non-normalized cell content
					if ( wo.filter_useParsedData ||
						c.parsers[column].parsed ||
						c.$headerIndexed[column].hasClass( 'filter-parsed' ) ) {
						arry.push( '' + cache.normalized[ rowIndex ][ column ] );
					} else {
						// get raw cached data instead of content directly from the cells
						arry.push( cache.normalized[ rowIndex ][ c.columns ].raw[ column ] );
					}
				}
			}
			return arry;
		},
		buildSelect: function( table, column, arry, updating, onlyAvail ) {
			table = $( table )[0];
			column = parseInt( column, 10 );
			if ( !table.config.cache || $.isEmptyObject( table.config.cache ) ) {
				return;
			}
			var indx, val, txt, t, $filters, $filter,
				c = table.config,
				wo = c.widgetOptions,
				node = c.$headerIndexed[ column ],
				// t.data( 'placeholder' ) won't work in jQuery older than 1.4.3
				options = '<option value="">' +
					( node.data( 'placeholder' ) ||
						node.attr( 'data-placeholder' ) ||
						wo.filter_placeholder.select || ''
					) + '</option>',
				// Get curent filter value
				currentValue = c.$table
					.find( 'thead' )
					.find( 'select.' + tscss.filter + '[data-column="' + column + '"]' )
					.val();
			// nothing included in arry ( external source ), so get the options from
			// filter_selectSource or column data
			if ( typeof arry === 'undefined' || arry === '' ) {
				arry = ts.filter.getOptionSource( table, column, onlyAvail );
			}

			if ( $.isArray( arry ) ) {
				// build option list
				for ( indx = 0; indx < arry.length; indx++ ) {
					txt = arry[indx] = ( '' + arry[indx] ).replace( /\"/g, '&quot;' );
					val = txt;
					// allow including a symbol in the selectSource array
					// 'a-z|A through Z' so that 'a-z' becomes the option value
					// and 'A through Z' becomes the option text
					if ( txt.indexOf( wo.filter_selectSourceSeparator ) >= 0 ) {
						t = txt.split( wo.filter_selectSourceSeparator );
						val = t[0];
						txt = t[1];
					}
					// replace quotes - fixes #242 & ignore empty strings
					// see http://stackoverflow.com/q/14990971/145346
					options += arry[indx] !== '' ?
						'<option ' +
							( val === txt ? '' : 'data-function-name="' + arry[indx] + '" ' ) +
							'value="' + val + '">' + txt +
						'</option>' : '';
				}
				// clear arry so it doesn't get appended twice
				arry = [];
			}

			// update all selects in the same column ( clone thead in sticky headers &
			// any external selects ) - fixes 473
			$filters = ( c.$filters ? c.$filters : c.$table.children( 'thead' ) )
				.find( '.' + tscss.filter );
			if ( wo.filter_$externalFilters ) {
				$filters = $filters && $filters.length ?
					$filters.add( wo.filter_$externalFilters ) :
					wo.filter_$externalFilters;
			}
			$filter = $filters.filter( 'select[data-column="' + column + '"]' );

			// make sure there is a select there!
			if ( $filter.length ) {
				$filter[ updating ? 'html' : 'append' ]( options );
				if ( !$.isArray( arry ) ) {
					// append options if arry is provided externally as a string or jQuery object
					// options ( default value ) was already added
					$filter.append( arry ).val( currentValue );
				}
				$filter.val( currentValue );
			}
		},
		buildDefault: function( table, updating ) {
			var columnIndex, $header, noSelect,
				c = table.config,
				wo = c.widgetOptions,
				columns = c.columns;
			// build default select dropdown
			for ( columnIndex = 0; columnIndex < columns; columnIndex++ ) {
				$header = c.$headerIndexed[columnIndex];
				noSelect = !( $header.hasClass( 'filter-false' ) || $header.hasClass( 'parser-false' ) );
				// look for the filter-select class; build/update it if found
				if ( ( $header.hasClass( 'filter-select' ) ||
					ts.getColumnData( table, wo.filter_functions, columnIndex ) === true ) && noSelect ) {
					ts.filter.buildSelect( table, columnIndex, '', updating, $header.hasClass( wo.filter_onlyAvail ) );
				}
			}
		}
	};

	ts.getFilters = function( table, getRaw, setFilters, skipFirst ) {
		var i, $filters, $column, cols,
			filters = false,
			c = table ? $( table )[0].config : '',
			wo = c ? c.widgetOptions : '';
		if ( ( getRaw !== true && wo && !wo.filter_columnFilters ) ||
			// setFilters called, but last search is exactly the same as the current
			// fixes issue #733 & #903 where calling update causes the input values to reset
			( $.isArray(setFilters) && setFilters.join('') === c.lastCombinedFilter ) ) {
			return $( table ).data( 'lastSearch' );
		}
		if ( c ) {
			if ( c.$filters ) {
				$filters = c.$filters.find( '.' + tscss.filter );
			}
			if ( wo.filter_$externalFilters ) {
				$filters = $filters && $filters.length ?
					$filters.add( wo.filter_$externalFilters ) :
					wo.filter_$externalFilters;
			}
			if ( $filters && $filters.length ) {
				filters = setFilters || [];
				for ( i = 0; i < c.columns + 1; i++ ) {
					cols = ( i === c.columns ?
						// 'all' columns can now include a range or set of columms ( data-column='0-2,4,6-7' )
						wo.filter_anyColumnSelector + ',' + wo.filter_multipleColumnSelector :
						'[data-column="' + i + '"]' );
					$column = $filters.filter( cols );
					if ( $column.length ) {
						// move the latest search to the first slot in the array
						$column = ts.filter.getLatestSearch( $column );
						if ( $.isArray( setFilters ) ) {
							// skip first ( latest input ) to maintain cursor position while typing
							if ( skipFirst && $column.length > 1 ) {
								$column = $column.slice( 1 );
							}
							if ( i === c.columns ) {
								// prevent data-column='all' from filling data-column='0,1' ( etc )
								cols = $column.filter( wo.filter_anyColumnSelector );
								$column = cols.length ? cols : $column;
							}
							$column
								.val( setFilters[ i ] )
								.trigger( 'change.tsfilter' );
						} else {
							filters[i] = $column.val() || '';
							// don't change the first... it will move the cursor
							if ( i === c.columns ) {
								// don't update range columns from 'all' setting
								$column
									.slice( 1 )
									.filter( '[data-column*="' + $column.attr( 'data-column' ) + '"]' )
									.val( filters[ i ] );
							} else {
								$column
									.slice( 1 )
									.val( filters[ i ] );
							}
						}
						// save any match input dynamically
						if ( i === c.columns && $column.length ) {
							wo.filter_$anyMatch = $column;
						}
					}
				}
			}
		}
		if ( filters.length === 0 ) {
			filters = false;
		}
		return filters;
	};

	ts.setFilters = function( table, filter, apply, skipFirst ) {
		var c = table ? $( table )[0].config : '',
			valid = ts.getFilters( table, true, filter, skipFirst );
		if ( c && apply ) {
			// ensure new set filters are applied, even if the search is the same
			c.lastCombinedFilter = null;
			c.lastSearch = [];
			ts.filter.searching( c.table, filter, skipFirst );
			c.$table.trigger( 'filterFomatterUpdate' );
		}
		return !!valid;
	};

})( jQuery );

/*! Widget: stickyHeaders - updated 3/26/2015 (v2.21.3) *//*
 * Requires tablesorter v2.8+ and jQuery 1.4.3+
 * by Rob Garrison
 */
;(function ($, window) {
	'use strict';
	var ts = $.tablesorter || {};

	$.extend(ts.css, {
		sticky    : 'tablesorter-stickyHeader', // stickyHeader
		stickyVis : 'tablesorter-sticky-visible',
		stickyHide: 'tablesorter-sticky-hidden',
		stickyWrap: 'tablesorter-sticky-wrapper'
	});

	// Add a resize event to table headers
	ts.addHeaderResizeEvent = function(table, disable, settings) {
		table = $(table)[0]; // make sure we're using a dom element
		if ( !table.config ) { return; }
		var defaults = {
				timer : 250
			},
			options = $.extend({}, defaults, settings),
			c = table.config,
			wo = c.widgetOptions,
			checkSizes = function( triggerEvent ) {
				var index, headers, $header, sizes, width, height,
					len = c.$headers.length;
				wo.resize_flag = true;
				headers = [];
				for ( index = 0; index < len; index++ ) {
					$header = c.$headers.eq( index );
					sizes = $header.data( 'savedSizes' ) || [ 0, 0 ]; // fixes #394
					width = $header[0].offsetWidth;
					height = $header[0].offsetHeight;
					if ( width !== sizes[0] || height !== sizes[1] ) {
						$header.data( 'savedSizes', [ width, height ] );
						headers.push( $header[0] );
					}
				}
				if ( headers.length && triggerEvent !== false ) {
					c.$table.trigger( 'resize', [ headers ] );
				}
				wo.resize_flag = false;
			};
		checkSizes( false );
		clearInterval(wo.resize_timer);
		if (disable) {
			wo.resize_flag = false;
			return false;
		}
		wo.resize_timer = setInterval(function() {
			if (wo.resize_flag) { return; }
			checkSizes();
		}, options.timer);
	};

	// Sticky headers based on this awesome article:
	// http://css-tricks.com/13465-persistent-headers/
	// and https://github.com/jmosbech/StickyTableHeaders by Jonas Mosbech
	// **************************
	ts.addWidget({
		id: 'stickyHeaders',
		priority: 60, // sticky widget must be initialized after the filter widget!
		options: {
			stickyHeaders : '',       // extra class name added to the sticky header row
			stickyHeaders_attachTo : null, // jQuery selector or object to attach sticky header to
			stickyHeaders_xScroll : null, // jQuery selector or object to monitor horizontal scroll position (defaults: xScroll > attachTo > window)
			stickyHeaders_yScroll : null, // jQuery selector or object to monitor vertical scroll position (defaults: yScroll > attachTo > window)
			stickyHeaders_offset : 0, // number or jquery selector targeting the position:fixed element
			stickyHeaders_filteredToTop: true, // scroll table top into view after filtering
			stickyHeaders_cloneId : '-sticky', // added to table ID, if it exists
			stickyHeaders_addResizeEvent : true, // trigger 'resize' event on headers
			stickyHeaders_includeCaption : true, // if false and a caption exist, it won't be included in the sticky header
			stickyHeaders_zIndex : 2 // The zIndex of the stickyHeaders, allows the user to adjust this to their needs
		},
		format: function(table, c, wo) {
			// filter widget doesn't initialize on an empty table. Fixes #449
			if ( c.$table.hasClass('hasStickyHeaders') || ($.inArray('filter', c.widgets) >= 0 && !c.$table.hasClass('hasFilters')) ) {
				return;
			}
			var index, len, $t,
				$table = c.$table,
				// add position: relative to attach element, hopefully it won't cause trouble.
				$attach = $(wo.stickyHeaders_attachTo),
				namespace = c.namespace + 'stickyheaders ',
				// element to watch for the scroll event
				$yScroll = $(wo.stickyHeaders_yScroll || wo.stickyHeaders_attachTo || window),
				$xScroll = $(wo.stickyHeaders_xScroll || wo.stickyHeaders_attachTo || window),
				$thead = $table.children('thead:first'),
				$header = $thead.children('tr').not('.sticky-false').children(),
				$tfoot = $table.children('tfoot'),
				$stickyOffset = isNaN(wo.stickyHeaders_offset) ? $(wo.stickyHeaders_offset) : '',
				stickyOffset = $stickyOffset.length ? $stickyOffset.height() || 0 : parseInt(wo.stickyHeaders_offset, 10) || 0,
				// is this table nested? If so, find parent sticky header wrapper (div, not table)
				$nestedSticky = $table.parent().closest('.' + ts.css.table).hasClass('hasStickyHeaders') ?
					$table.parent().closest('table.tablesorter')[0].config.widgetOptions.$sticky.parent() : [],
				nestedStickyTop = $nestedSticky.length ? $nestedSticky.height() : 0,
				// clone table, then wrap to make sticky header
				$stickyTable = wo.$sticky = $table.clone()
					.addClass('containsStickyHeaders ' + ts.css.sticky + ' ' + wo.stickyHeaders + ' ' + c.namespace.slice(1) + '_extra_table' )
					.wrap('<div class="' + ts.css.stickyWrap + '">'),
				$stickyWrap = $stickyTable.parent()
					.addClass(ts.css.stickyHide)
					.css({
						position   : $attach.length ? 'absolute' : 'fixed',
						padding    : parseInt( $stickyTable.parent().parent().css('padding-left'), 10 ),
						top        : stickyOffset + nestedStickyTop,
						left       : 0,
						visibility : 'hidden',
						zIndex     : wo.stickyHeaders_zIndex || 2
					}),
				$stickyThead = $stickyTable.children('thead:first'),
				$stickyCells,
				laststate = '',
				spacing = 0,
				setWidth = function($orig, $clone){
					var index, width, border, $cell, $this,
						$cells = $orig.filter(':visible'),
						len = $cells.length;
					for ( index = 0; index < len; index++ ) {
						$cell = $clone.filter(':visible').eq(index);
						$this = $cells.eq(index);
						// code from https://github.com/jmosbech/StickyTableHeaders
						if ($this.css('box-sizing') === 'border-box') {
							width = $this.outerWidth();
						} else {
							if ($cell.css('border-collapse') === 'collapse') {
								if (window.getComputedStyle) {
									width = parseFloat( window.getComputedStyle($this[0], null).width );
								} else {
									// ie8 only
									border = parseFloat( $this.css('border-width') );
									width = $this.outerWidth() - parseFloat( $this.css('padding-left') ) - parseFloat( $this.css('padding-right') ) - border;
								}
							} else {
								width = $this.width();
							}
						}
						$cell.css({
							'width': width,
							'min-width': width,
							'max-width': width
						});
					}
				},
				resizeHeader = function() {
					stickyOffset = $stickyOffset.length ? $stickyOffset.height() || 0 : parseInt(wo.stickyHeaders_offset, 10) || 0;
					spacing = 0;
					$stickyWrap.css({
						left : $attach.length ? parseInt($attach.css('padding-left'), 10) || 0 :
								$table.offset().left - parseInt($table.css('margin-left'), 10) - $xScroll.scrollLeft() - spacing,
						width: $table.outerWidth()
					});
					setWidth( $table, $stickyTable );
					setWidth( $header, $stickyCells );
				},
				scrollSticky = function( resizing ) {
					if (!$table.is(':visible')) { return; } // fixes #278
					// Detect nested tables - fixes #724
					nestedStickyTop = $nestedSticky.length ? $nestedSticky.offset().top - $yScroll.scrollTop() + $nestedSticky.height() : 0;
					var offset = $table.offset(),
						yWindow = $.isWindow( $yScroll[0] ), // $.isWindow needs jQuery 1.4.3
						xWindow = $.isWindow( $xScroll[0] ),
						// scrollTop = ( $attach.length ? $attach.offset().top : $yScroll.scrollTop() ) + stickyOffset + nestedStickyTop,
						scrollTop = ( $attach.length ? ( yWindow ? $yScroll.scrollTop() : $yScroll.offset().top ) : $yScroll.scrollTop() ) + stickyOffset + nestedStickyTop,
						tableHeight = $table.height() - ($stickyWrap.height() + ($tfoot.height() || 0)),
						isVisible = ( scrollTop > offset.top ) && ( scrollTop < offset.top + tableHeight ) ? 'visible' : 'hidden',
						cssSettings = { visibility : isVisible };

					if ($attach.length) {
						cssSettings.top = yWindow ? scrollTop - $attach.offset().top : $attach.scrollTop();
					}
					if (xWindow) {
						// adjust when scrolling horizontally - fixes issue #143
						cssSettings.left = $table.offset().left - parseInt($table.css('margin-left'), 10) - $xScroll.scrollLeft() - spacing;
					}
					if ($nestedSticky.length) {
						cssSettings.top = ( cssSettings.top || 0 ) + stickyOffset + nestedStickyTop;
					}
					$stickyWrap
						.removeClass( ts.css.stickyVis + ' ' + ts.css.stickyHide )
						.addClass( isVisible === 'visible' ? ts.css.stickyVis : ts.css.stickyHide )
						.css(cssSettings);
					if (isVisible !== laststate || resizing) {
						// make sure the column widths match
						resizeHeader();
						laststate = isVisible;
					}
				};
			// only add a position relative if a position isn't already defined
			if ($attach.length && !$attach.css('position')) {
				$attach.css('position', 'relative');
			}
			// fix clone ID, if it exists - fixes #271
			if ($stickyTable.attr('id')) { $stickyTable[0].id += wo.stickyHeaders_cloneId; }
			// clear out cloned table, except for sticky header
			// include caption & filter row (fixes #126 & #249) - don't remove cells to get correct cell indexing
			$stickyTable.find('thead:gt(0), tr.sticky-false').hide();
			$stickyTable.find('tbody, tfoot').remove();
			$stickyTable.find('caption').toggle(wo.stickyHeaders_includeCaption);
			// issue #172 - find td/th in sticky header
			$stickyCells = $stickyThead.children().children();
			$stickyTable.css({ height:0, width:0, margin: 0 });
			// remove resizable block
			$stickyCells.find('.' + ts.css.resizer).remove();
			// update sticky header class names to match real header after sorting
			$table
				.addClass('hasStickyHeaders')
				.bind('pagerComplete' + namespace, function() {
					resizeHeader();
				});

			ts.bindEvents(table, $stickyThead.children().children('.' + ts.css.header));

			// add stickyheaders AFTER the table. If the table is selected by ID, the original one (first) will be returned.
			$table.after( $stickyWrap );

			// onRenderHeader is defined, we need to do something about it (fixes #641)
			if (c.onRenderHeader) {
				$t = $stickyThead.children('tr').children();
				len = $t.length;
				for ( index = 0; index < len; index++ ) {
					// send second parameter
					c.onRenderHeader.apply( $t.eq( index ), [ index, c, $stickyTable ] );
				}
			}

			// make it sticky!
			$xScroll.add($yScroll)
				.unbind( ('scroll resize '.split(' ').join( namespace )).replace(/\s+/g, ' ') )
				.bind('scroll resize '.split(' ').join( namespace ), function( event ) {
					scrollSticky( event.type === 'resize' );
				});
			c.$table
				.unbind('stickyHeadersUpdate' + namespace)
				.bind('stickyHeadersUpdate' + namespace, function(){
					scrollSticky( true );
				});

			if (wo.stickyHeaders_addResizeEvent) {
				ts.addHeaderResizeEvent(table);
			}

			// look for filter widget
			if ($table.hasClass('hasFilters') && wo.filter_columnFilters) {
				// scroll table into view after filtering, if sticky header is active - #482
				$table.bind('filterEnd' + namespace, function() {
					// $(':focus') needs jQuery 1.6+
					var $td = $(document.activeElement).closest('td'),
						column = $td.parent().children().index($td);
					// only scroll if sticky header is active
					if ($stickyWrap.hasClass(ts.css.stickyVis) && wo.stickyHeaders_filteredToTop) {
						// scroll to original table (not sticky clone)
						window.scrollTo(0, $table.position().top);
						// give same input/select focus; check if c.$filters exists; fixes #594
						if (column >= 0 && c.$filters) {
							c.$filters.eq(column).find('a, select, input').filter(':visible').focus();
						}
					}
				});
				ts.filter.bindSearch( $table, $stickyCells.find('.' + ts.css.filter) );
				// support hideFilters
				if (wo.filter_hideFilters) {
					ts.filter.hideFilters($stickyTable, c);
				}
			}

			$table.trigger('stickyHeadersInit');

		},
		remove: function(table, c, wo) {
			var namespace = c.namespace + 'stickyheaders ';
			c.$table
				.removeClass('hasStickyHeaders')
				.unbind( ('pagerComplete filterEnd stickyHeadersUpdate '.split(' ').join(namespace)).replace(/\s+/g, ' ') )
				.next('.' + ts.css.stickyWrap).remove();
			if (wo.$sticky && wo.$sticky.length) { wo.$sticky.remove(); } // remove cloned table
			$(window)
				.add(wo.stickyHeaders_xScroll)
				.add(wo.stickyHeaders_yScroll)
				.add(wo.stickyHeaders_attachTo)
				.unbind( ('scroll resize '.split(' ').join(namespace)).replace(/\s+/g, ' ') );
			ts.addHeaderResizeEvent(table, false);
		}
	});

})(jQuery, window);

/*! Widget: resizable - updated 6/26/2015 (v2.22.2) */
/*jshint browser:true, jquery:true, unused:false */
;(function ($, window) {
	'use strict';
	var ts = $.tablesorter || {};

	$.extend(ts.css, {
		resizableContainer : 'tablesorter-resizable-container',
		resizableHandle    : 'tablesorter-resizable-handle',
		resizableNoSelect  : 'tablesorter-disableSelection',
		resizableStorage   : 'tablesorter-resizable'
	});

	// Add extra scroller css
	$(function(){
		var s = '<style>' +
			'body.' + ts.css.resizableNoSelect + ' { -ms-user-select: none; -moz-user-select: -moz-none;' +
				'-khtml-user-select: none; -webkit-user-select: none; user-select: none; }' +
			'.' + ts.css.resizableContainer + ' { position: relative; height: 1px; }' +
			// make handle z-index > than stickyHeader z-index, so the handle stays above sticky header
			'.' + ts.css.resizableHandle + ' { position: absolute; display: inline-block; width: 8px;' +
				'top: 1px; cursor: ew-resize; z-index: 3; user-select: none; -moz-user-select: none; }' +
			'</style>';
		$(s).appendTo('body');
	});

	ts.resizable = {
		init : function( c, wo ) {
			if ( c.$table.hasClass( 'hasResizable' ) ) { return; }
			c.$table.addClass( 'hasResizable' );

			var noResize, $header, column, storedSizes, tmp,
				$table = c.$table,
				$parent = $table.parent(),
				marginTop = parseInt( $table.css( 'margin-top' ), 10 ),

			// internal variables
			vars = wo.resizable_vars = {
				useStorage : ts.storage && wo.resizable !== false,
				$wrap : $parent,
				mouseXPosition : 0,
				$target : null,
				$next : null,
				overflow : $parent.css('overflow') === 'auto' ||
					$parent.css('overflow') === 'scroll' ||
					$parent.css('overflow-x') === 'auto' ||
					$parent.css('overflow-x') === 'scroll',
				storedSizes : []
			};

			// set default widths
			ts.resizableReset( c.table, true );

			// now get measurements!
			vars.tableWidth = $table.width();
			// attempt to autodetect
			vars.fullWidth = Math.abs( $parent.width() - vars.tableWidth ) < 20;

			/*
			// Hacky method to determine if table width is set to 'auto'
			// http://stackoverflow.com/a/20892048/145346
			if ( !vars.fullWidth ) {
				tmp = $table.width();
				$header = $table.wrap('<span>').parent(); // temp variable
				storedSizes = parseInt( $table.css( 'margin-left' ), 10 ) || 0;
				$table.css( 'margin-left', storedSizes + 50 );
				vars.tableWidth = $header.width() > tmp ? 'auto' : tmp;
				$table.css( 'margin-left', storedSizes ? storedSizes : '' );
				$header = null;
				$table.unwrap('<span>');
			}
			*/

			if ( vars.useStorage && vars.overflow ) {
				// save table width
				ts.storage( c.table, 'tablesorter-table-original-css-width', vars.tableWidth );
				tmp = ts.storage( c.table, 'tablesorter-table-resized-width' ) || 'auto';
				ts.resizable.setWidth( $table, tmp, true );
			}
			wo.resizable_vars.storedSizes = storedSizes = ( vars.useStorage ?
				ts.storage( c.table, ts.css.resizableStorage ) :
				[] ) || [];
			ts.resizable.setWidths( c, wo, storedSizes );
			ts.resizable.updateStoredSizes( c, wo );

			wo.$resizable_container = $( '<div class="' + ts.css.resizableContainer + '">' )
				.css({ top : marginTop })
				.insertBefore( $table );
			// add container
			for ( column = 0; column < c.columns; column++ ) {
				$header = c.$headerIndexed[ column ];
				tmp = ts.getColumnData( c.table, c.headers, column );
				noResize = ts.getData( $header, tmp, 'resizable' ) === 'false';
				if ( !noResize ) {
					$( '<div class="' + ts.css.resizableHandle + '">' )
						.appendTo( wo.$resizable_container )
						.attr({
							'data-column' : column,
							'unselectable' : 'on'
						})
						.data( 'header', $header )
						.bind( 'selectstart', false );
				}
			}
			$table.one('tablesorter-initialized', function() {
				ts.resizable.setHandlePosition( c, wo );
				ts.resizable.bindings( this.config, this.config.widgetOptions );
			});
		},

		updateStoredSizes : function( c, wo ) {
			var column, $header,
				len = c.columns,
				vars = wo.resizable_vars;
			vars.storedSizes = [];
			for ( column = 0; column < len; column++ ) {
				$header = c.$headerIndexed[ column ];
				vars.storedSizes[ column ] = $header.is(':visible') ? $header.width() : 0;
			}
		},

		setWidth : function( $el, width, overflow ) {
			// overflow tables need min & max width set as well
			$el.css({
				'width' : width,
				'min-width' : overflow ? width : '',
				'max-width' : overflow ? width : ''
			});
		},

		setWidths : function( c, wo, storedSizes ) {
			var column, $temp,
				vars = wo.resizable_vars,
				$extra = $( c.namespace + '_extra_headers' ),
				$col = c.$table.children( 'colgroup' ).children( 'col' );
			storedSizes = storedSizes || vars.storedSizes || [];
			// process only if table ID or url match
			if ( storedSizes.length ) {
				for ( column = 0; column < c.columns; column++ ) {
					// set saved resizable widths
					ts.resizable.setWidth( c.$headerIndexed[ column ], storedSizes[ column ], vars.overflow );
					if ( $extra.length ) {
						// stickyHeaders needs to modify min & max width as well
						$temp = $extra.eq( column ).add( $col.eq( column ) );
						ts.resizable.setWidth( $temp, storedSizes[ column ], vars.overflow );
					}
				}
				$temp = $( c.namespace + '_extra_table' );
				if ( $temp.length && !ts.hasWidget( c.table, 'scroller' ) ) {
					ts.resizable.setWidth( $temp, c.$table.outerWidth(), vars.overflow );
				}
			}
		},

		setHandlePosition : function( c, wo ) {
			var startPosition,
				hasScroller = ts.hasWidget( c.table, 'scroller' ),
				tableHeight = c.$table.height(),
				$handles = wo.$resizable_container.children(),
				handleCenter = Math.floor( $handles.width() / 2 );

			if ( hasScroller ) {
				tableHeight = 0;
				c.$table.closest( '.' + ts.css.scrollerWrap ).children().each(function(){
					var $this = $(this);
					// center table has a max-height set
					tableHeight += $this.filter('[style*="height"]').length ? $this.height() : $this.children('table').height();
				});
			}
			// subtract out table left position from resizable handles. Fixes #864
			startPosition = c.$table.position().left;
			$handles.each( function() {
				var $this = $(this),
					column = parseInt( $this.attr( 'data-column' ), 10 ),
					columns = c.columns - 1,
					$header = $this.data( 'header' );
				if ( !$header ) { return; } // see #859
				if ( !$header.is(':visible') ) {
					$this.hide();
				} else if ( column < columns || column === columns && wo.resizable_addLastColumn ) {
					$this.css({
						display: 'inline-block',
						height : tableHeight,
						left : $header.position().left - startPosition + $header.outerWidth() - handleCenter
					});
				}
			});
		},

		// prevent text selection while dragging resize bar
		toggleTextSelection : function( c, toggle ) {
			var namespace = c.namespace + 'tsresize';
			c.widgetOptions.resizable_vars.disabled = toggle;
			$( 'body' ).toggleClass( ts.css.resizableNoSelect, toggle );
			if ( toggle ) {
				$( 'body' )
					.attr( 'unselectable', 'on' )
					.bind( 'selectstart' + namespace, false );
			} else {
				$( 'body' )
					.removeAttr( 'unselectable' )
					.unbind( 'selectstart' + namespace );
			}
		},

		bindings : function( c, wo ) {
			var namespace = c.namespace + 'tsresize';
			wo.$resizable_container.children().bind( 'mousedown', function( event ) {
				// save header cell and mouse position
				var column,
					vars = wo.resizable_vars,
					$extras = $( c.namespace + '_extra_headers' ),
					$header = $( event.target ).data( 'header' );

				column = parseInt( $header.attr( 'data-column' ), 10 );
				vars.$target = $header = $header.add( $extras.filter('[data-column="' + column + '"]') );
				vars.target = column;

				// if table is not as wide as it's parent, then resize the table
				vars.$next = event.shiftKey || wo.resizable_targetLast ?
					$header.parent().children().not( '.resizable-false' ).filter( ':last' ) :
					$header.nextAll( ':not(.resizable-false)' ).eq( 0 );

				column = parseInt( vars.$next.attr( 'data-column' ), 10 );
				vars.$next = vars.$next.add( $extras.filter('[data-column="' + column + '"]') );
				vars.next = column;

				vars.mouseXPosition = event.pageX;
				ts.resizable.updateStoredSizes( c, wo );
				ts.resizable.toggleTextSelection( c, true );
			});

			$( document )
				.bind( 'mousemove' + namespace, function( event ) {
					var vars = wo.resizable_vars;
					// ignore mousemove if no mousedown
					if ( !vars.disabled || vars.mouseXPosition === 0 || !vars.$target ) { return; }
					if ( wo.resizable_throttle ) {
						clearTimeout( vars.timer );
						vars.timer = setTimeout( function() {
							ts.resizable.mouseMove( c, wo, event );
						}, isNaN( wo.resizable_throttle ) ? 5 : wo.resizable_throttle );
					} else {
						ts.resizable.mouseMove( c, wo, event );
					}
				})
				.bind( 'mouseup' + namespace, function() {
					if (!wo.resizable_vars.disabled) { return; }
					ts.resizable.toggleTextSelection( c, false );
					ts.resizable.stopResize( c, wo );
					ts.resizable.setHandlePosition( c, wo );
				});

			// resizeEnd event triggered by scroller widget
			$( window ).bind( 'resize' + namespace + ' resizeEnd' + namespace, function() {
				ts.resizable.setHandlePosition( c, wo );
			});

			// right click to reset columns to default widths
			c.$table
				.bind( 'columnUpdate' + namespace, function() {
					ts.resizable.setHandlePosition( c, wo );
				})
				.find( 'thead:first' )
				.add( $( c.namespace + '_extra_table' ).find( 'thead:first' ) )
				.bind( 'contextmenu' + namespace, function() {
					// $.isEmptyObject() needs jQuery 1.4+; allow right click if already reset
					var allowClick = wo.resizable_vars.storedSizes.length === 0;
					ts.resizableReset( c.table );
					ts.resizable.setHandlePosition( c, wo );
					wo.resizable_vars.storedSizes = [];
					return allowClick;
				});

		},

		mouseMove : function( c, wo, event ) {
			if ( wo.resizable_vars.mouseXPosition === 0 || !wo.resizable_vars.$target ) { return; }
			// resize columns
			var column,
				total = 0,
				vars = wo.resizable_vars,
				$next = vars.$next,
				tar = vars.storedSizes[ vars.target ],
				leftEdge = event.pageX - vars.mouseXPosition;
			if ( vars.overflow ) {
				if ( tar + leftEdge > 0 ) {
					vars.storedSizes[ vars.target ] += leftEdge;
					ts.resizable.setWidth( vars.$target, vars.storedSizes[ vars.target ], true );
					// update the entire table width
					for ( column = 0; column < c.columns; column++ ) {
						total += vars.storedSizes[ column ];
					}
					ts.resizable.setWidth( c.$table.add( $( c.namespace + '_extra_table' ) ), total );
				}
				if ( !$next.length ) {
					// if expanding right-most column, scroll the wrapper
					vars.$wrap[0].scrollLeft = c.$table.width();
				}
			} else if ( vars.fullWidth ) {
				vars.storedSizes[ vars.target ] += leftEdge;
				vars.storedSizes[ vars.next ] -= leftEdge;
				ts.resizable.setWidths( c, wo );
			} else {
				vars.storedSizes[ vars.target ] += leftEdge;
				ts.resizable.setWidths( c, wo );
			}
			vars.mouseXPosition = event.pageX;
			// dynamically update sticky header widths
			c.$table.trigger('stickyHeadersUpdate');
		},

		stopResize : function( c, wo ) {
			var vars = wo.resizable_vars;
			ts.resizable.updateStoredSizes( c, wo );
			if ( vars.useStorage ) {
				// save all column widths
				ts.storage( c.table, ts.css.resizableStorage, vars.storedSizes );
				ts.storage( c.table, 'tablesorter-table-resized-width', c.$table.width() );
			}
			vars.mouseXPosition = 0;
			vars.$target = vars.$next = null;
			// will update stickyHeaders, just in case, see #912
			c.$table.trigger('stickyHeadersUpdate');
		}
	};

	// this widget saves the column widths if
	// $.tablesorter.storage function is included
	// **************************
	ts.addWidget({
		id: 'resizable',
		priority: 40,
		options: {
			resizable : true, // save column widths to storage
			resizable_addLastColumn : false,
			resizable_widths : [],
			resizable_throttle : false, // set to true (5ms) or any number 0-10 range
			resizable_targetLast : false,
			resizable_fullWidth : null
		},
		init: function(table, thisWidget, c, wo) {
			ts.resizable.init( c, wo );
		},
		remove: function( table, c, wo, refreshing ) {
			if (wo.$resizable_container) {
				var namespace = c.namespace + 'tsresize';
				c.$table.add( $( c.namespace + '_extra_table' ) )
					.removeClass('hasResizable')
					.children( 'thead' )
					.unbind( 'contextmenu' + namespace );

				wo.$resizable_container.remove();
				ts.resizable.toggleTextSelection( c, false );
				ts.resizableReset( table, refreshing );
				$( document ).unbind( 'mousemove' + namespace + ' mouseup' + namespace );
			}
		}
	});

	ts.resizableReset = function( table, refreshing ) {
		$( table ).each(function(){
			var index, $t,
				c = this.config,
				wo = c && c.widgetOptions,
				vars = wo.resizable_vars;
			if ( table && c && c.$headerIndexed.length ) {
				// restore the initial table width
				if ( vars.overflow && vars.tableWidth ) {
					ts.resizable.setWidth( c.$table, vars.tableWidth, true );
					if ( vars.useStorage ) {
						ts.storage( table, 'tablesorter-table-resized-width', 'auto' );
					}
				}
				for ( index = 0; index < c.columns; index++ ) {
					$t = c.$headerIndexed[ index ];
					if ( wo.resizable_widths && wo.resizable_widths[ index ] ) {
						ts.resizable.setWidth( $t, wo.resizable_widths[ index ], vars.overflow );
					} else if ( !$t.hasClass( 'resizable-false' ) ) {
						// don't clear the width of any column that is not resizable
						ts.resizable.setWidth( $t, '', vars.overflow );
					}
				}

				// reset stickyHeader widths
				c.$table.trigger( 'stickyHeadersUpdate' );
				if ( ts.storage && !refreshing ) {
					ts.storage( this, ts.css.resizableStorage, {} );
				}
			}
		});
	};

})( jQuery, window );

/*! Widget: saveSort */
;(function ($) {
	'use strict';
	var ts = $.tablesorter || {};

	// this widget saves the last sort only if the
	// saveSort widget option is true AND the
	// $.tablesorter.storage function is included
	// **************************
	ts.addWidget({
		id: 'saveSort',
		priority: 20,
		options: {
			saveSort : true
		},
		init: function(table, thisWidget, c, wo) {
			// run widget format before all other widgets are applied to the table
			thisWidget.format(table, c, wo, true);
		},
		format: function(table, c, wo, init) {
			var stored, time,
				$table = c.$table,
				saveSort = wo.saveSort !== false, // make saveSort active/inactive; default to true
				sortList = { 'sortList' : c.sortList };
			if (c.debug) {
				time = new Date();
			}
			if ($table.hasClass('hasSaveSort')) {
				if (saveSort && table.hasInitialized && ts.storage) {
					ts.storage( table, 'tablesorter-savesort', sortList );
					if (c.debug) {
						console.log('saveSort widget: Saving last sort: ' + c.sortList + ts.benchmark(time));
					}
				}
			} else {
				// set table sort on initial run of the widget
				$table.addClass('hasSaveSort');
				sortList = '';
				// get data
				if (ts.storage) {
					stored = ts.storage( table, 'tablesorter-savesort' );
					sortList = (stored && stored.hasOwnProperty('sortList') && $.isArray(stored.sortList)) ? stored.sortList : '';
					if (c.debug) {
						console.log('saveSort: Last sort loaded: "' + sortList + '"' + ts.benchmark(time));
					}
					$table.bind('saveSortReset', function(event) {
						event.stopPropagation();
						ts.storage( table, 'tablesorter-savesort', '' );
					});
				}
				// init is true when widget init is run, this will run this widget before all other widgets have initialized
				// this method allows using this widget in the original tablesorter plugin; but then it will run all widgets twice.
				if (init && sortList && sortList.length > 0) {
					c.sortList = sortList;
				} else if (table.hasInitialized && sortList && sortList.length > 0) {
					// update sort change
					$table.trigger('sorton', [ sortList ]);
				}
			}
		},
		remove: function(table, c) {
			c.$table.removeClass('hasSaveSort');
			// clear storage
			if (ts.storage) { ts.storage( table, 'tablesorter-savesort', '' ); }
		}
	});

})(jQuery);

return $.tablesorter;
}));