var Cookie={get:function(n){var dc="; "+document.cookie+"; ";var coo=dc.indexOf("; "+n+"=");if(coo!=-1){var s=dc.substring(coo+n.length+3,dc.length);return unescape(s.substring(0,s.indexOf("; ")));}else{return null;}},set:function(name,value,expires,path,domain,secure){var expDays=expires*24*60*60*3;var expDate=new Date();expDate.setTime(expDate.getTime()+expDays);var expString=expires?"; expires="+expDate.toGMTString():"";var pathString="; path="+(path||"/");var domain=domain?"; domain="+domain:"";document.cookie=name+"="+escape(value)+expString+domain+pathString+(secure?"; secure":"");},del:function(n){var exp=new Date();exp.setTime(exp.getTime()-1);var cval=this.get(n);if(cval!=null)document.cookie=n+"="+cval+";expires="+exp.toGMTString();}}
function readbook(bookid){$.get("/modules/article/articlevisit.php?id="+bookid);}
function vote_nomsg(aid){$.get('/modules/article/uservote.php?id='+aid+'&ajax_request=1');}
function addBookmark(title,url){if(!title){title=document.title}
if(!url){url=window.location.href}
if(window.sidebar){window.sidebar.addPanel(title,url,"");}else if(document.all){window.external.AddFavorite(url,title);}else if(window.opera||window.print){alert('浏览器不支持，请使用Ctrl + D 收藏！');return true;}}
function killErrors(){return true;}
window.onerror=killErrors;var jieqiUserInfo=new Array();jieqiUserInfo['jieqiUserId']=0;jieqiUserInfo['jieqiUserUname']='';jieqiUserInfo['jieqiUserName']='';jieqiUserInfo['jieqiUserGroup']=0;if(document.cookie.indexOf('jieqiUserInfo')>=0){var cookieInfo=get_cookie_value('jieqiUserInfo');start=0;offset=cookieInfo.indexOf(',',start);while(offset>0){tmpval=cookieInfo.substring(start,offset);tmpidx=tmpval.indexOf('=');if(tmpidx>0){tmpname=tmpval.substring(0,tmpidx);tmpval=tmpval.substring(tmpidx+1,tmpval.length);jieqiUserInfo[tmpname]=tmpval}
start=offset+1;if(offset<cookieInfo.length){offset=cookieInfo.indexOf(',',start);if(offset==-1)offset=cookieInfo.length}else{offset=-1}}}
function get_cookie_value(Name){var search=Name+"=";var returnvalue="";if(document.cookie.length>0){offset=document.cookie.indexOf(search);if(offset!=-1){offset+=search.length;end=document.cookie.indexOf(";",offset);if(end==-1)end=document.cookie.length;returnvalue=unescape(document.cookie.substring(offset,end))}}
return returnvalue}
var isLogin=jieqiUserInfo['jieqiUserId']>0;function login(){if(isLogin){document.writeln("<a href=\'http://uukanshu.cc\'>繁</a> | <a href=\"\/history.html\">阅读历史</a> | <a href=\"\/modules\/article\/bookcase.php\" title='我的书架'>会员书架<\/a> | <a href=\"\/logout.php?jumpurl="+location.href+"\" title='退出登录'>退出<\/a>")}else{document.writeln("<a href=\'http://uukanshu.cc\'>繁</a> | <a href=\"\/history.html\">阅读历史</a> | <a href=\"\/login.php?jumpurl="+location.href+"\">登录</a> | <a href=\"\/register.php\">注册</a>")}}
function ErrorLink(articlename){var error_url='/newmessage.php?tosys=1&title=《'+articlename+'》催更报错&content='+location.href;$("#errorlink,.errorlink").attr('href',error_url);}
function ReadKeyEvent(){var index_page=$("#linkIndex").attr("href");var prev_page=$("#linkPrev").attr("href");var next_page=$("#linkNext").attr("href");function jumpPage(){var event=document.all?window.event:arguments[0];if(event.keyCode==37)document.location=prev_page;if(event.keyCode==39)document.location=next_page;if(event.keyCode==13)document.location=index_page;}
document.onkeydown=jumpPage;}
function showMsg(msg){isLogin=isLogin&&msg.indexOf("您需要登录")<=0;if(!isLogin){if(confirm("对不起，您需要登录才能使用本功能！点击确定立即登录。")){window.location.href="/login.php?jumpurl="+location.href;}
return false;}
alert(msg.replace(/<br[^<>]*>/g,'\n'));}
function BookVote(article_id){$.get('/modules/article/uservote.php?id='+article_id+'&ajax_request=1',function(data){showMsg(data);});}
function BookCaseAdd(article_id){var url='/modules/article/addbookcase.php?bid='+article_id+'&ajax_request=1';$.get(url,function(res){showMsg(res);});}
function BookCaseMark(article_id,chapter_id){var url='/modules/article/addbookcase.php?bid='+article_id+'&cid='+chapter_id+'&ajax_request=1';$.get(url,function(res){showMsg(res);});}
var _num=100;function LastRead(){this.bookList="bookList"}
LastRead.prototype={set:function(bid,tid,title,texttitle,author,sortname){if(!(bid&&tid&&title&&texttitle&&author&&sortname))return;var v=bid+'#'+tid+'#'+title+'#'+texttitle+'#'+author+'#'+sortname;this.setItem(bid,v);this.setBook(bid)},get:function(k){return this.getItem(k)?this.getItem(k).split("#"):"";},remove:function(k){this.removeItem(k);this.removeBook(k)},setBook:function(v){var reg=new RegExp("(^|#)"+v);var books=this.getItem(this.bookList);if(books==""){books=v}
else{if(books.search(reg)==-1){books+="#"+v}
else{books.replace(reg,"#"+v)}}
this.setItem(this.bookList,books)},getBook:function(){var v=this.getItem(this.bookList)?this.getItem(this.bookList).split("#"):Array();var books=Array();if(v.length){for(var i=0;i<v.length;i++){var tem=this.getItem(v[i]).split('#');if(i>v.length-(_num+1)){if(tem.length>3)books.push(tem);}
else{lastread.remove(tem[0]);}}}
return books},removeBook:function(v){var reg=new RegExp("(^|#)"+v);var books=this.getItem(this.bookList);if(!books){books=""}
else{if(books.search(reg)!=-1){books=books.replace(reg,"")}}
this.setItem(this.bookList,books)},setItem:function(k,v){if(!!window.localStorage){localStorage.setItem(k,v);}
else{var expireDate=new Date();var EXPIR_MONTH=30*24*3600*1000;expireDate.setTime(expireDate.getTime()+12*EXPIR_MONTH)
document.cookie=k+"="+encodeURIComponent(v)+";expires="+expireDate.toGMTString()+"; path=/";}},getItem:function(k){var value=""
var result=""
if(!!window.localStorage){result=window.localStorage.getItem(k);value=result||"";}
else{var reg=new RegExp("(^| )"+k+"=([^;]*)(;|\x24)");var result=reg.exec(document.cookie);if(result){value=decodeURIComponent(result[2])||""}}
return value},removeItem:function(k){if(!!window.localStorage){window.localStorage.removeItem(k);}
else{var expireDate=new Date();expireDate.setTime(expireDate.getTime()-1000)
document.cookie=k+"= "+";expires="+expireDate.toGMTString()}},removeAll:function(){if(!!window.localStorage){window.localStorage.clear();}
else{var v=this.getItem(this.bookList)?this.getItem(this.bookList).split("#"):Array();var books=Array();if(v.length){for(i in v){var tem=this.removeItem(v[k])}}
this.removeItem(this.bookList)}}}
function showbook(){var showbook=document.getElementById('showbook');var bookhtml='';var books=lastread.getBook();var books=books.reverse();if(books.length){for(var i=0;i<books.length;i++){bookhtml+='<div class="bookbox"><div class="p10"><span class="num">'+(i+1)+'</span><div class="bookinfo"><h4 class="bookname"><a href="/book/'+books[i][0]+'/">'+books[i][2]+'</a></h4><div class="cat">分类：'+books[i][5]+'</div><div class="author">作者：'+books[i][4]+'</div><div class="update"><span>已读到：</span><a href="/book/'+books[i][0]+'/'+books[i][1]+'.html">'+books[i][3]+'</a></div></div><div class="delbutton"><a class="del_but" href="javascript:removebook(\''+books[i][0]+'\')">删除</a></div></div></div>';}}else{bookhtml+='<div style="height:100px;line-height:100px; text-align:center">还木有任何书籍( ˙﹏˙ )</div>'}
showbook.innerHTML=bookhtml;}
function removebook(k){lastread.remove(k);showbook()}
window.lastread=new LastRead();function tj(){}
function cnzz(){}
function is_mobile(){var regex_match=/(nokia|iphone|android|motorola|^mot-|softbank|foma|docomo|kddi|up.browser|up.link|htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte-|longcos|pantech|gionee|^sie-|portalmmm|jigs browser|hiptop|^benq|haier|^lct|operas*mobi|opera*mini|320x320|240x320|176x220)/i;var u=navigator.userAgent;if(null==u){return true;}
var result=regex_match.exec(u);if(null==result){return false}else{return true}}
function bd_push(){}
document.writeln("<!-- Global site tag (gtag.js) - Google Analytics -->");document.writeln("<script async src=\'https://www.googletagmanager.com/gtag/js?id=UA-175206009-1\'></script>");document.writeln("<script>");document.writeln("  window.dataLayer = window.dataLayer || [];");document.writeln("  function gtag(){dataLayer.push(arguments);}");document.writeln("  gtag(\'js\', new Date());");document.writeln("");document.writeln("  gtag(\'config\', \'UA-175206009-1\');");document.writeln("</script>");