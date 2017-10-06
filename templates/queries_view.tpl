<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_QUERY_BC_ROOT}></a> &gt; <a href="view.php?id=<{$queries_id}>"><{$smarty.const._MD_QUERY_VIEW_SINGLE}></a></div>
<br />
<div class="itemHead">
    <h2><{$queries_title}></h2>
</div>
<div class="itemInfo">
<div class="itemPoster" style="float:left;"><{$smarty.const._MD_QUERIES_NAME}>:
<{if isset($queries_uid)}>
<a href="<{$xoops_url}>/userinfo.php?uid=<{$queries_uid}>">
<{/if}>
<{$queries_user_name}>
<{if isset($queries_uid)}>
</a>
<{/if}>
</div><br>

<div class="itemPoster" style="float:left;"><{$smarty.const._MD_QUERIES_EMAIL}>:  <{$queries_user_email}></div><br />

<div class="itemPostDate" style="float:left;"><{$smarty.const._MD_QUERIES_POSTED}>:  <{$queries_posted}></div><br />

</div>
<br>
<div class="itemBody">
<div class="itemText">
<{$queries_querytext}>
</div>
<{if is_array($actions)}>
<br><div class="itemInfo">
<{$smarty.const._MD_QUERIES_VIEW_ACTIONS}><br />
<{section name=i loop=$actions }>
<a href="<{$actions[i].action}>" <{$actions[i].extra}>><img src="<{$actions[i].button}>" alt="<{$actions[i].alt}>" border=0 ></a>
<{/section}>
</div>
<{/if}>
</div>
<hr />
<div style="text-align: center; padding: 3px; margin: 3px;">
  <{$commentsnav}>
  <{$lang_notice}>
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
<{include file='db:system_notification_select.tpl'}>
</div>
