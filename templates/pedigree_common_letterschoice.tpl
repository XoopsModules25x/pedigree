<style type="text/css">
    .button_green {
        -moz-box-shadow: inset 0 1px 0 0 #d9fbbe;
        -webkit-box-shadow: inset 0 1px 0 0 #d9fbbe;
        box-shadow: inset 0 1px 0 0 #d9fbbe;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #d9fbbe), color-stop(1, #d9fbbe));
        background: -moz-linear-gradient(center top, #a5cc52 5%, #d9fbbe 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#d9fbbe', endColorstr='#b8e356');
        background-color: #d9fbbe;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        text-indent: 0;
        border: 1px solid #83c41a;
        display: inline-block;
        color: inherit;
        font-family: inherit;
        font-size: 12px;
        font-weight: bold;
        font-style: normal;
        height: 20px;
        line-height: 20px;
        width: auto;
        min-width: 10px;
        text-decoration: none;
        text-align: center;
        text-shadow: 1px 1px 0 #d9fbbe;
        margin: 2px 0;
        padding: 0 4px;
    }

    .button_green:hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #b8e356), color-stop(1, #a5cc52));
        background: -moz-linear-gradient(center top, #b8e356 5%, #a5cc52 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#b8e356', endColorstr='#a5cc52');
        background-color: #86ae47;
    }

    .button_green:active {
        position: relative;
        top: 1px;
    }

    .button_grey {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #dfdfdf));
        background: -moz-linear-gradient(center top, #ededed 5%, #dfdfdf 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#dfdfdf');
        background-color: #ededed;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        border-radius: 2px;
        text-indent: 0;
        border: 1px solid #dcdcdc;
        display: inline-block;
        color: inherit;
        font-family: inherit;
        font-size: 12px;
        font-weight: bold;
        font-style: normal;
        height: 20px;
        line-height: 20px;
        width: auto;
        min-width: 10px;
        text-decoration: none;
        text-align: center;
        text-shadow: 1px 1px 0 #ffffff;
        margin: 2px 0;
        padding: 0 4px;
    }

    .button_grey:hover {
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0.05, #dfdfdf), color-stop(1, #ededed));
        background: -moz-linear-gradient(center top, #dfdfdf 5%, #ededed 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#dfdfdf', endColorstr='#ededed');
        background-color: #dfdfdf;
    }

    .button_grey:active {
        position: relative;
        top: 1px;
    }
</style>
<{$smarty.const._MD_WFDOWNLOADS_BROWSETOTOPIC}>
<br/>
<div>
    <{assign var="alphabetcount" value=$alphabet|@count}>
    <{foreach name=letters item=letter from=$alphabet}>
    <{if ($letter.count > 0)}>
    <a class='button_green' href='<{$letter.url}>' title='<{$letter.count}>'><{$letter.letter}></a>
    <{else}>
    <span class='button_grey'><{$letter.letter}></span>
    <{/if}>
    <{if ($smarty.foreach.letters.iteration == (round($alphabetcount/2))+1)}>
</div>
<div><{else}><{/if}>
    <{/foreach}>
</div>
