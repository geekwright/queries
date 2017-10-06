<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_QUERY_BC_ROOT}></a> &gt; <{$smarty.const._MD_QUERY_LIST_BY_DATE}></div>
<{if isset($pagenav)}>
<div align="left"><{$pagenav}></div>
<{/if}>
<{if is_array($q_ids) && count($q_ids) > 0 }>
<{assign var='queries_count' value=$q_ids|@count}>
<table width="100%"><tr><td>
<ul>
<{section name=i start=0 loop=$queries_count step=1 }>
<li><a href="view.php?id=<{$q_ids[i]}>" title="<{$q_text[i]|truncate:300:'...':false}>"><{$q_titles[i]}></a> (<{$q_dates[i]}>)</li>
<{/section}>
</ul>
</td></tr></table>
<{else}>
Nothing to display
<{/if}>
<hr />
<{if isset($pagenav)}>
<div align="right"><{$pagenav}></div>
<{/if}>
<{include file='db:system_notification_select.tpl'}>
<{if isset($debug)}>
<div><{$debug}></div>
<{/if}>