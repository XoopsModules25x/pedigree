<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>pedigree Administration Documentation</title>
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    <link rel="stylesheet" href="style.css" type="text/css">
</head>
<body bgcolor="#ffffff">
{{{{ Text that needs replacing is enclosed in quadruple braces, like this }}}}
<h1>pedigree
    Administration Documentation</h1>
[ This module was created by James Cotton. The latest version of the module
may be available at
<a href="http://www.dobermannvereniging.nl">http://www.dobermannvereniging.nl</a>]<br>
<br>
<h2>Table of Contents</h2>
<table border="0" class="toc">
    <tr>
        <td><A href="#about">About pedigree</A></td>
    </tr>
    <tr>
        <td><A href="#require">Requirements</A></td>
    </tr>
    <tr>
        <td><A href="#upgrade">Updgrading from a Previous Version</A></td>
    </tr>
    <tr>
        <td><A href="#install">Installing pedigree</A></td>
    </tr>
    <tr>
        <td><A href="#configure">Configuring</A></td>
    </tr>
    <tr>
        <td><A href="#blocks">Blocks in pedigree</A></td>
    </tr>
</table>
<P>
    <span style="font-weight: bold; font-size: 144%; "></BOLD></span></P>
<h2>About pedigree</h2>
<P>
<P></P>

<P>The pedigree
    {{{{ Fill in a description of what your module does. Remember, the reader may have never heard of your module,
    or the module it was based on, so don't start with "It does what the blazflazz module does, only better".
    Describe it first, then any comparisions }}}}
<h2><a name="require">Requirements</a></h2>
<P>pedigree was developed using the following versions.</P>
<table>
    <tr>
        <th>Software</th>
        <th>Version</th>
    </tr>
    <tr>
        <td>Xoops</td>
        <td>{{{{ Fill In Your Xoops Version }}}}</td>
    </tr>
    <tr>
        <td>PHP</td>
        <td>{{{{ Fill In Your Xoops Version }}}}</td>
    </tr>
</table>
<P>Your results will probably be best if you are using similar version numbers. Please report any version issues, so
    they can be corrected.
<h2><a name="upgrade">Upgrading {{{{ Fill In Your Xoops Version }}}} from Previous Versions</a></h2>

<P>{{{{ If there was a previous version, let people know what the upgrade will mean. Do they have to uninstall? Re-enter
    all data?}}}}</P>
<h2><a name="install">Installing pedigree</a></h2>
<P>pedigree is installed just like any other xoops module.</P>
<UL>
    <LI>
        Unzip the pedigree-X.XX.zip file into your modules directory.
    <LI>
        Go to the xoops admin/system admin/modules page (hitting refresh if
        necessary), and click on the install icon for pedigree.
    </LI>
</UL>
<P>You are now ready to configure pedigree.</P>
<h2><a name="configure">Configuring pedigree</a></h2>

<P>pedigree has a number of settings that control the display and operation of the
    pedigree.
<UL>
    <LI>Dummy Setting one<BR>
        {{{{ Fill
        in your settings for each configuration field.}}}}
    </LI>
</UL>
<h3> Other Configuration</h3>
<p>This section is general information about xoops and blocks, it is not specific
    to pedigree</p>
<h4>Block Visibility in Xoops</h4>
<P>To control block placement and visibility in xoops, go to /admin.php, and select
    "System Admin", and then select blocks. Check the blocks you want to be
    visible, and their positions</P>
<P>One of the more common questions asked in xoops is "Why can't people see my
    blocks?". The answer is you need to enable block visibility for anonymous
    users, if you want casual visitors to your site to see this module.
    This can be done under xoops administration/groups/anonymous users. Check next
    to every block you want visitors who have not signed in.
</P>
<P>You need to do this for module access, and block access, but NOT for
    administration.</P>
<h4>Block Titles</h4>
<P>The block titles are displayed at the top of the block. By default, xoops sets the block title to be the
    same as the block name. Go to System Administration/blocks and click on "Edit" next to the block whose title you
    want to change. </P>

<h2><a name="blocks">Blocks</a></h2>
pedigree includes the following blocks
<ul>
    <li>
        <a href="#b_titles">Block One</a></li>
</ul>
<h3><a name="b_titles"></a>Block
    One</A></h3>
<P>{{{{ Describe the block. Bear in mind that the person reading this may have just installed the block,
    and may not understand all aspects of the module. Try to make this description short, and self contained.}}}}</P>


<HR align=left color=#2f5376 SIZE=3>

</body>
</html>
