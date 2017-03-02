<h1>Getting started with Seagull</h1>
<div class="message"><?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'msgGet'))) echo $t->msgGet();?></div>

<h6>Fixed layouts with top navigation</h6>
<ul>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navtop-1col.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navtop-1col.css"));?>">1 column</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navtop-1col.css")) { ?><li><strong>1 column</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navtop-2col_localleft.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navtop-2col_localleft.css"));?>">2 columns: left + content</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navtop-2col_localleft.css")) { ?><li><strong>2 columns: left + content</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navtop-2col_subright.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navtop-2col_subright.css"));?>">2 columns: content + right</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navtop-2col_subright.css")) { ?><li><strong>2 columns: content + right</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navtop-3col.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navtop-3col.css"));?>">3 columns: left + content + right</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navtop-3col.css")) { ?><li><strong>3 columns: left + content + right</strong></li><?php }?>
</ul>
<h6>Fluid layout with top navigation</h6>
<ul>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navtop-1col_fluid.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navtop-1col_fluid.css"));?>">1 column</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navtop-1col_fluid.css")) { ?><li><strong>1 column</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navtop-2col_localleft_fluid.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navtop-2col_localleft_fluid.css"));?>">2 columns: left + content</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navtop-2col_localleft_fluid.css")) { ?><li><strong>2 columns: left + content</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navtop-2col_subright_fluid.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navtop-2col_subright_fluid.css"));?>">2 columns: content + right</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navtop-2col_subright_fluid.css")) { ?><li><strong>2 columns: content + right</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navtop-3col_fluid.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navtop-3col_fluid.css"));?>">3 columns: left + content + right</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navtop-3col_fluid.css")) { ?><li><strong>3 columns: left + content + right</strong></li><?php }?>
</ul>
<h6>Fixed layouts with left navigation</h6>
<ul>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navleft-1col.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navleft-1col.css"));?>">1 column</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navleft-1col.css")) { ?><li><strong>1 column</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navleft-2col.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navleft-2col.css"));?>">2 columns: content + right</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navleft-2col.css")) { ?><li><strong>2 columns: content + right</strong></li><?php }?>
</ul>
<h6>Fixed layouts with right navigation</h6>
<ul>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navright-1col.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navright-1col.css"));?>">1 column</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navright-1col.css")) { ?><li><strong>1 column</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navright-2col.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navright-2col.css"));?>">2 columns: left + content</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navright-2col.css")) { ?><li><strong>2 columns: left + content</strong></li><?php }?>
</ul>
<h6>Fluid layouts with left navigation</h6>
<ul>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navleft-1col_fluid.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navleft-1col_fluid.css"));?>">1 column</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navleft-1col_fluid.css")) { ?><li><strong>1 column</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navleft-2col_fluid.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navleft-2col_fluid.css"));?>">2 columns: content + right</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navleft-2col_fluid.css")) { ?><li><strong>2 columns: content + right</strong></li><?php }?>
</ul>
<h6>Fluid layouts with right navigation</h6>
<ul>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navright-1col_fluid.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navright-1col_fluid.css"));?>">1 column</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navright-1col_fluid.css")) { ?><li><strong>1 column</strong></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if (!$t->isEqual($t->masterLayout,"layout-navright-2col_fluid.css")) { ?><li><a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","default","default","","masterLayout|layout-navright-2col_fluid.css"));?>">2 columns: left + content</a></li><?php }?>
    <?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'isEqual'))) if ($t->isEqual($t->masterLayout,"layout-navright-2col_fluid.css")) { ?><li><strong>2 columns: left + content</strong></li><?php }?>
</ul>
<div class="hr"><!-- ie6 fix, do not remove --></div>

<p>
    This page is a static template for you to replace with your own content,
    you can find it here:
</p>
<pre class="codeExample">
[install-dir]/modules/default/templates/home.html
</pre>

<p>
    You can now <a href="<?php if ($this->options['strict'] || (isset($t) && method_exists($t, 'makeUrl'))) echo htmlspecialchars($t->makeUrl("","login","user"));?>">login as admin</a>
    and start creating your own content.
</p>
<div class="hr"><!-- ie6 fix, do not remove --></div>

<p>
    The Seagull framework is an OO
    <a href="http://php.net" title="PHP">
        <acronym title="PHP: Hypertext Preprocessor">PHP</acronym></a>
    framework, with core components
    <acronym title="Berkeley Software Distribution">BSD</acronym> licensed,
    that has the following design goals:
</p>
<ul>
    <li>independence of data, logic &amp; presentation layers</li>
    <li>extensible component architecture</li>
    <li>reduction of repetitive programming tasks</li>
    <li>simplifying data access</li>
    <li>comprehensive error handling</li>
    <li>module workflow routines</li>
    <li>form handling without the donkey work</li>
    <li>component reuse</li>
    <li>authentication management</li>
    <li>
        integration with
        <acronym title="PHP Extension and Application Repository">PEAR</acronym>
        libraries
    </li>
    <li>
        <acronym title="PHP: Hypertext Preprocessor">PHP</acronym>
        coding standards
    </li>
    <li>
        platform/<acronym title="PHP: Hypertext Preprocessor">PHP</acronym>
        version/browser independence
    </li>
    <li>self-generating documentation</li>
    <li>quality end user docs</li>
</ul>

<p>
    Seagull works 'out of the box' and is simple to install and configure,
    please follow the instructions in INSTALL.txt.
    Developer info is availabe in the
    <a href="http://trac.seagullproject.org" title="Project docs">project docs</a>.
</p>
<p>
    There are some default modules that come with the framework,
    these handle tasks related to:
</p>
<ul>
    <li>user/group management</li>
    <li>content management</li>
    <li>document management</li>
    <li>category management</li>
    <li>messaging</li>
    <li>navigation</li>
</ul>
