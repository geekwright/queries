<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><{$queries_title}></title>

<link rel="stylesheet" type="text/css" href="assets/css/print.css" />

</head>
<body onload='window.print()'; />

<div class="itemTitle"><{$queries_title}></div>
<div class="itemHead">
<div class="itemPoster"><{$smarty.const._MD_QUERIES_NAME}>: <{$queries_user_name}></div>
<div class="itemPoster"><{$smarty.const._MD_QUERIES_EMAIL}>:  <{$queries_user_email}></div>
<div class="itemPostDate"><{$smarty.const._MD_QUERIES_POSTED}>:  <{$queries_posted}></div>
</div>
<hr />
<div class="itemBody">
<{$queries_querytext}>
</div>

<div style="margin: 3px; padding: 3px;">
<!-- start comments loop -->
<{if $comment_mode == "flat"}>
  <{include file="db:system_comments_flat.tpl"}>
<{elseif $comment_mode == "thread"}>
  <{include file="db:system_comments_thread.tpl"}>
<{elseif $comment_mode == "nest"}>
  <{include file="db:system_comments_nest.tpl"}>
<{/if}>
<!-- end comments loop -->
</div>


</body>
</html>
