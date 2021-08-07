<{if $isAdmin === true}>
    <div class="pedigree_adminlinks"><{$pedigree_adminpage}></div>
<{/if}>

<{if $com_rule != 0}>
    <a name="comments"></a>
    <div class="pedigree_foot_commentnav">
        <{$commentsnav}>
        <{$lang_notice}>
    </div>
    <div class="pedigree_foot_comments">
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
<{/if}>

<{include file='db:system_notification_select.tpl'}>
