<span class="left"><b><{$smarty.const._MA_PEDIGREE_TITLE}></b>&#58;&#160;</span>
<span class="left"><{$smarty.const._MA_PEDIGREE_DESC}></span><br/>
<{if $adv != ''}>
    <div class="center"><{$adv}></div>
<{/if}>

<{$pedigree_breadcrumb}>

<{if $catarray.imageheader != ""}>
    <br/>
    <div class="pedigree_head_catimageheader"><{$catarray.imageheader}></div>
    <br/>
<{/if}>

<{if $down.imageheader != ""}>
    <br/>
    <div class="pedigree_head_downimageheader"><{$down.imageheader}></div>
    <br/>
<{/if}>

<{if $imageheader != ""}>
    <br/>
    <div class="pedigree_head_imageheader"><{$imageheader}></div>
    <br/>
<{/if}>

<{if $catarray.indexheader}>
    <div class="pedigree_head_catindexheader" align="<{$catarray.indexheaderalign}>"><p><{$catarray.indexheader}></p></div>
    <br/>
<{/if}>
<{if $catarray.letters}>
    <div class="pedigree_head_catletters" align="center"><{$catarray.letters}></div>
    <br/>
<{/if}>
<{if $catarray.toolbar}>
    <div class="pedigree_head_cattoolbar" align="center"><{$catarray.toolbar}></div>
    <br/>
<{/if}>
