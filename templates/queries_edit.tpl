<div class="breadcrumb"><a href="index.php"><{$smarty.const._MD_QUERY_BC_ROOT}></a> &gt; <a href="edit.php"><{$smarty.const._MD_QUERY_BC_EDIT}></a></div>
<br />
<{if isset($err_message)}>
<hr />
<div class="errorMsg"><{$err_message}></div>
<hr />
<{/if}>

<{if isset($message)}>
<hr />
<div class="resultMsg"><{$message}></div>
<hr />
<{/if}>

<{if isset($body)}>
<div><{$body}></div>
<{/if}>

<{include file='db:system_notification_select.tpl'}>
<{if isset($debug)}>
<hr />
<div><{$debug}></div>
<{/if}>