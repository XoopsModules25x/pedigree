<{if $social_bookmarks != 0}>
<{include file="db:system_social_bookmarks.html"}>
<{/if}>
<{if $fbcomments != 0}>
<{include file="db:system_fbcomments.tpl"}>
<{/if}>
<div class="left"><{$copyright}></div>
<{if $pagenav != ''}>
<div class="right"><{$pagenav}></div>
<{/if}>
<br /><{if $xoops_isadmin}>
   <div class="center bold"><a href="<{$admin}>"><{$smarty.const._MA_ANIMAL_ADMIN}></a></div>
<{/if}>
