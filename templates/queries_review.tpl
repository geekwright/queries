<script LANGUAGE="JavaScript">
function confirmSubmit()
{
return confirm("<{$smarty.const._MD_QUERIES_ACTIONS_CONFIRM}>");
}
</script>

<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_QUERY_BC_ROOT}></a> &gt; <a href="review.php"><{$smarty.const._MD_QUERY_LIST_REVIEW}></a></div>
<br />
<{if isset($err_message)}>
<div class="errorMsg"><{$err_message}></div>
<hr /><br />
<{/if}>
<{if isset($message)}>
<br /><hr />
<div class="resultMsg"><{$message}></div>
<hr /><br />
<{/if}>
<div class="content">
<form action="review.php" method="POST" onSubmit="return confirmSubmit();">
<table width="100%">
<tr>
<th><{$smarty.const._MD_QUERIES_NAME}></th>
<th><{$smarty.const._MD_QUERIES_EMAIL}></th>
<th><{$smarty.const._MD_QUERIES_TITLE}></th>
<th><{$smarty.const._MD_QUERIES_ACTIONS_APPROVE}></th>
<th><{$smarty.const._MD_QUERIES_ACTIONS_DELETE}></th>
</tr>
<{section name=i loop=$ids }>
<tr class="<{cycle values="odd,even"}>">
<td><{$names[i]}></td>
<td><{$emails[i]}></td>
<td><a href="view.php?id=<{$ids[i]}>" target="_blank"><{$titles[i]}></a></td>
<td><input type="radio" name="opset[<{$ids[i]}>]" value="approve"></td>
<td><input type="radio" name="opset[<{$ids[i]}>]" value="delete"></td>
</tr>
<{/section}>
</table>
<{$formtoken}>
<input type="submit" value="submit"><input type="reset" value="reset">
</div>
<{if isset($pagenav)}>
<hr />
<div align="right"><{$pagenav}></div>
<{/if}>
<{include file='db:system_notification_select.tpl'}>
<{if isset($debug)}>
<div><{$debug}></div>
<{/if}>
