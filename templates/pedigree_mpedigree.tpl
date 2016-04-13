
    <table>
    <!-- header (dog name) -->
    <tr>
        <th colspan="8" style="text-align:center;">
            <{$d.d.name}>
        </th>
    </tr>
     <tr>
      <td rowspan=16 width="20%" class="head">
          <{if $d.d.roft == 0}>
            <{$male}>
        <{else}>
            <{$female}>
        <{/if}>
          <a href="dog.php?id=<{$d.d.id}>"><{$d.d.name}></a>
      </td>
      <td rowspan=8 width="20%" class="even" style="background-color: <{$d.f.col}>;">
          <{$male}><a href="mpedigree.php?pedid=<{$d.f.id}>"><{$d.f.name}></a>
      </td>
      <td rowspan=4 width="20%" class="even" style="background-color: <{$d.ff.col}>;">
         <{$male}><a href="mpedigree.php?pedid=<{$d.ff.id}>"><{$d.ff.name}></a>
      </td>
      <td rowspan=2 width="20%" class="even" style="background-color: <{$d.fff.col}>;">
          <{$male}><a href="mpedigree.php?pedid=<{$d.fff.id}>"><{$d.fff.name}></a>
      </td>
      <td width="20%" class="even" style="background-color: <{$d.ffff.col}>;">
          <{$male}><a href="mpedigree.php?pedid=<{$d.ffff.id}>"><{$d.ffff.name}></a>
      </td>
     </tr>
     <tr>
      <td class="odd" style="background-color: <{$d.fffm.col}>;">
          <{$female}><a href="mpedigree.php?pedid=<{$d.fffm.id}>"><{$d.fffm.name}></a>
      </td>
     </tr>
     <tr>
      <td rowspan=2 class="odd" style="background-color: <{$d.ffm.col}>;">
          <{$female}><a href="mpedigree.php?pedid=<{$d.ffm.id}>"><{$d.ffm.name}></a>
      </td>
      <td class="even" style="background-color: <{$d.ffmf.col}>;">
          <{$male}><a href="mpedigree.php?pedid=<{$d.ffmf.id}>"><{$d.ffmf.name}></a>
      </td>
     </tr>
     <tr>
      <td class="odd" style="background-color: <{$d.ffmm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.ffmm.id}>"><{$d.ffmm.name}></a>
      </td>
     </tr>
     <tr>
      <td rowspan=4 class="odd" style="background-color: <{$d.fm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.fm.id}>"><{$d.fm.name}></a>
      </td>
      <td rowspan=2 class="even" style="background-color: <{$d.fmf.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.fmf.id}>"><{$d.fmf.name}></a>
      </td>
      <td class="even" style="background-color: <{$d.fmff.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.fmff.id}>"><{$d.fmff.name}></a>
      </td>
     </tr>
     <tr>
      <td class="odd" style="background-color: <{$d.fmfm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.fmfm.id}>"><{$d.fmfm.name}></a>
      </td>
     </tr>
     <tr>
      <td rowspan=2 class="odd" style="background-color: <{$d.fmm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.fmm.id}>"><{$d.fmm.name}></a>
      </td>
      <td class="even" style="background-color: <{$d.fmmf.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.fmmf.id}>"><{$d.fmmf.name}></a>
      </td>
     </tr>
     <tr>
      <td class="odd" style="background-color: <{$d.fmmm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.fmmm.id}>"><{$d.fmmm.name}></a>
      </td>
     </tr>
     <tr>
      <td rowspan=8 class="odd" style="background-color: <{$d.m.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.m.id}>"><{$d.m.name}></a>
      </td>
      <td rowspan=4 class="even" style="background-color: <{$d.mf.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.mf.id}>"><{$d.mf.name}></a>
      </td>
      <td rowspan=2 class="even" style="background-color: <{$d.mff.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.mff.id}>"><{$d.mff.name}></a>
      </td>
      <td class="even" style="background-color: <{$d.mfff.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.mfff.id}>"><{$d.mfff.name}></a>
      </td>
     </tr>
     <tr>
      <td class="odd" style="background-color: <{$d.mffm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.mffm.id}>"><{$d.mffm.name}></a>
      </td>
     </tr>
     <tr>
      <td rowspan=2 class="odd" style="background-color: <{$d.mfm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.mfm.id}>"><{$d.mfm.name}></a>
      </td>
      <td class="even" style="background-color: <{$d.mfmf.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.mfmf.id}>"><{$d.mfmf.name}></a>
      </td>
     </tr>
     <tr>
      <td class="odd" style="background-color: <{$d.mfmm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.mfmm.id}>"><{$d.mfmm.name}></a>
      </td>
     </tr>
     <tr>
      <td rowspan=4 class="odd" style="background-color: <{$d.mm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.mm.id}>"><{$d.mm.name}></a>
      </td>
      <td rowspan=2 class="even" style="background-color: <{$d.mmf.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.mmf.id}>"><{$d.mmf.name}></a>
      </td>
      <td class="even" style="background-color: <{$d.mmff.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.mmff.id}>"><{$d.mmff.name}></a>
      </td>
     </tr>
     <tr>
      <td class="odd" style="background-color: <{$d.mmfm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.mmfm.id}>"><{$d.mmfm.name}></a>
      </td>
     </tr>
     <tr>
      <td rowspan=2 class="odd" style="background-color: <{$d.mmm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.mmm.id}>"><{$d.mmm.name}></a>
      </td>
      <td class="even" style="background-color: <{$d.mmmf.col}>;">
      <{$male}><a href="mpedigree.php?pedid=<{$d.mmmf.id}>"><{$d.mmmf.name}></a>
      </td>
     </tr>
     <tr>
      <td class="odd" style="background-color: <{$d.mmmm.col}>;">
      <{$female}><a href="mpedigree.php?pedid=<{$d.mmmm.id}>"><{$d.mmmm.name}></a>
      </td>
     </tr>
    </table>
    <br /><br />
    <table width="50%">
        <tr class="odd">
            <td style="background-color: #FFC8C8; width: 50px;">
                <{$female}>
            </td>
            <td>
                <{$f2}>
            </td>
        </tr>
        <tr class="even">
            <td style="background-color: #C8C8FF; width: 50px;">
                <{$male}>
            </td>
            <td>
                <{$m2}>
            </td>
        </tr>
        <tr class="odd">
            <td style="background-color: #FF6464; width: 50px;">
                <{$female}>
            </td>
            <td>
                <{$f3}>
            </td>
        </tr>
        <tr class="even">
            <td style="background-color: #6464FF; width: 50px;">
                <{$male}>
            </td>
            <td>
                <{$m3}>
            </td>
        </tr>
        <tr class="odd">
            <td style="background-color: #FF0000; width: 50px;">
                <{$female}>
            </td>
            <td>
                <{$f4}>
            </td>
        </tr>
        <tr class="even">
            <td style="background-color: #0000FF; width: 50px;">
                <{$male}>
            </td>
            <td>
                <{$m4}>
            </td>
        </tr>
    </table>
