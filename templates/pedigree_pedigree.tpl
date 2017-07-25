<table width="100%" class="outer" cellspacing="1">
    <!-- header (dog name) -->
    <tr>
        <th colspan="4" style="text-align:center;">
            <{$d.d.name}>
        </th>
    </tr>
    <tr>
        <!-- selected dog -->
        <td width="25%" rowspan="8" class="head" style="vertical-align: center;">
            <{if $d.d.roft == 0}>
                <{$male}>
            <{else}>
                <{$female}>
            <{/if}>
            <{if $d.d.id}>
                <a href='dog.php?id=<{$d.d.id}>'><{$d.d.name}></a>
                <br>
                <{if $d.d.photo}>
                    <img src='<{$d.d.photo}>' border="0">
                    <br>
                <{/if}>
                <{if $d.d.hd}>
                    <br>
                    <{$d.d.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
        <!-- father -->
        <td width="25%" rowspan="4" class="even">
            <{$male}>
            <{if $d.f.id}>
                <a href='pedigree.php?pedid=<{$d.f.id}>'><{$d.f.name}></a>
                <br>
                <{if $d.f.photo}>
                    <img src='<{$d.f.photo}>' border="0">
                <{/if}>
                <{if $d.f.hd}>
                    <br>
                    <{$d.f.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
        <!-- father father -->
        <td width="25%" rowspan="2" class="even">
            <{$male}>
            <{if $d.ff.id}>
                <a href='pedigree.php?pedid=<{$d.ff.id}>'><{$d.ff.name}></a>
                <br>
                <{if $d.ff.photo}>
                    <img src='<{$d.ff.photo}>' border="0">
                <{/if}>
                <{if $d.ff.hd}>
                    <br>
                    <{$d.ff.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
        <!-- father father father -->
        <td width="25%" class="even">
            <{$male}>
            <{if $d.fff.id}>
                <a href='pedigree.php?pedid=<{$d.fff.id}>'><{$d.fff.name}></a>
                <br>
                <{if $d.fff.photo}>
                    <img src='<{$d.fff.photo}>' border="0">
                <{/if}>
                <{if $d.fff.hd}>
                    <br>
                    <{$d.fff.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
    </tr>
    <tr>
        <!-- father father mother -->
        <td width="25%" class="odd">
            <{$female}>
            <{if $d.ffm.id}>
                <a href='pedigree.php?pedid=<{$d.ffm.id}>'><{$d.ffm.name}></a>
                <br>
                <{if $d.ffm.photo}>
                    <img src='<{$d.ffm.photo}>' border="0">
                <{/if}>
                <{if $d.ffm.hd}>
                    <br>
                    <{$d.ffm.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
    </tr>
    <tr>
        <!-- father mother -->
        <td width="25%" rowspan="2" class="odd">
            <{$female}>
            <{if $d.fm.id}>
                <a href='pedigree.php?pedid=<{$d.fm.id}>'><{$d.fm.name}></a>
                <br>
                <{if $d.fm.photo}>
                    <img src='<{$d.fm.photo}>' border="0">
                <{/if}>
                <{if $d.fm.hd}>
                    <br>
                    <{$d.fm.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
        <!-- father mother father -->
        <td width="25%" class="even">
            <{$male}>
            <{if $d.fmf.id}>
                <a href='pedigree.php?pedid=<{$d.fmf.id}>'><{$d.fmf.name}></a>
                <br>
                <{if $d.fmf.photo}>
                    <img src='<{$d.fmf.photo}>' border="0">
                <{/if}>
                <{if $d.fmf.hd}>
                    <br>
                    <{$d.fmf.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
    </tr>
    <tr>
        <!-- father mother mother -->
        <td width="25%" class="odd">
            <{$female}>
            <{if $d.fmm.id}>
                <a href='pedigree.php?pedid=<{$d.fmm.id}>'><{$d.fmm.name}></a>
                <br>
                <{if $d.fmm.photo}>
                    <img src='<{$d.fmm.photo}>' border="0">
                <{/if}>
                <{if $d.fmm.hd}>
                    <br>
                    <{$d.fmm.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
    </tr>
    <tr>
        <!-- mother -->
        <td width="25%" rowspan="4" class="odd">
            <{$female}>
            <{if $d.m.id}>
                <a href='pedigree.php?pedid=<{$d.m.id}>'><{$d.m.name}></a>
                <br>
                <{if $d.m.photo}>
                    <img src='<{$d.m.photo}>' border="0">
                <{/if}>
                <{if $d.m.hd}>
                    <br>
                    <{$d.m.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
        <!- mother father -->
        <td width="25%" rowspan="2" class="even">
            <{$male}>
            <{if $d.mf.id}>
                <a href='pedigree.php?pedid=<{$d.mf.id}>'><{$d.mf.name}></a>
                <br>
                <{if $d.mf.photo}>
                    <img src='<{$d.mf.photo}>' border="0">
                <{/if}>
                <{if $d.mf.hd}>
                    <br>
                    <{$d.mf.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
        <!-- mother father father -->
        <td width="25%" class="even">
            <{$male}>
            <{if $d.mff.id}>
                <a href='pedigree.php?pedid=<{$d.mff.id}>'><{$d.mff.name}></a>
                <br>
                <{if $d.mff.photo}>
                    <img src='<{$d.mff.photo}>' border="0">
                <{/if}>
                <{if $d.mff.hd}>
                    <br>
                    <{$d.mff.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
    </tr>
    <tr>
        <!-- mother father mother -->
        <td width="25%" class="odd">
            <{$female}>
            <{if $d.mfm.id}>
                <a href='pedigree.php?pedid=<{$d.mfm.id}>'><{$d.mfm.name}></a>
                <br>
                <{if $d.mfm.photo}>
                    <img src='<{$d.mfm.photo}>' border="0">
                <{/if}>
                <{if $d.mfm.hd}>
                    <br>
                    <{$d.mfm.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
    </tr>
    <tr>
        <!-- mother mother -->
        <td width="25%" rowspan="2" class="odd">
            <{$female}>
            <{if $d.mm.id}>
                <a href='pedigree.php?pedid=<{$d.mm.id}>'><{$d.mm.name}></a>
                <br>
                <{if $d.mm.photo}>
                    <img src='<{$d.mm.photo}>' border="0">
                <{/if}>
                <{if $d.mm.hd}>
                    <br>
                    <{$d.mm.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
        <!-- mother mother father -->
        <td width="25%" class="even">
            <{$male}>
            <{if $d.mmf.id}>
                <a href='pedigree.php?pedid=<{$d.mmf.id}>'><{$d.mmf.name}></a>
                <br>
                <{if $d.mmf.photo}>
                    <img src='<{$d.mmf.photo}>' border="0">
                <{/if}>
                <{if $d.mmf.hd}>
                    <br>
                    <{$d.mmf.hd}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
    </tr>
    <tr>
        <!-- mother mother mother -->
        <td width="25%" class="odd">
            <{$female}>
            <{if $d.mmm.id}>
                <a href='pedigree.php?pedid=<{$d.mmm.id}>'><{$d.mmm.name}></a>
                <br>
                <{if $d.mmm.photo}>
                    <img src='<{$d.mmm.photo}>' border="0">
                <{/if}>
                <{if $d.mmm.hd}>
                    <br>
                    <{$d.mmm.hd}>
                <{/if}>
                <{if $det == 1}>
                    <br>
                    <{$d.mmm.detail}>
                <{/if}>
            <{else}>
                <{$unknown}>
            <{/if}>
        </td>
    </tr>
</table>

<!-- print options -->
<table width="100%">
    <tr>
        <td>
            &nbsp;
        </td>
        <td align="right">
            <a href="print.php?dogid=<{$d.d.id}>"><img src="<{xoModuleIcons16 printer.png}>"></a>
        </td>
    </tr>
</table>
