#!/bin/sh
#
# @package cms
# @author Dmitri Lakachauskis <lakiboy83@gmail.com>
#
# Examples:
#
#   $ ./etc/setup.sh

# -----------------
# --- Constants ---
# -----------------

SGL_SVNURL=http://svn.seagullproject.org/svn/seagull/branches/0.6-bugfix
CMS_PATH=`pwd`

# ----------------
# --- Binaries ---
# ----------------

SVN=`which svn`
CHMOD=`which chmod`
LN=`which ln`

# -----------------
# --- Functions ---
# -----------------

usage()
{
    echo ""
    echo "You must have the SGL_PATH environment variable set."
    echo "To set it type the following:"
    echo "$ export SGL_PATH=/path/to/my/sgl"
}

check_for_sglpath()
{
    if [ -z $SGL_PATH ] ; then
        usage
        exit 1
    fi
}

export_sgl_files()
{
    # files in root
    $SVN export $SGL_SVNURL/COPYING.txt $CMS_PATH/COPYING.txt
    $SVN export $SGL_SVNURL/INSTALL.txt $CMS_PATH/INSTALL.txt
    $SVN export $SGL_SVNURL/VERSION.txt $CMS_PATH/VERSION.txt

    # files in etc
    $SVN export $SGL_SVNURL/etc/sequence.my.sql $CMS_PATH/etc/sequence.my.sql
    $SVN export $SGL_SVNURL/etc/default.conf.dist.ini $CMS_PATH/etc/default.conf.dist.ini

    # files in lib
#    $SVN export $SGL_SVNURL/lib/SGL.php $CMS_PATH/lib/SGL.php

    # files in www
    $SVN export $SGL_SVNURL/www/setup.php $CMS_PATH/www/setup.php
    $SVN export $SGL_SVNURL/www/optimizer.php $CMS_PATH/www/optimizer.php
    $SVN export $SGL_SVNURL/www/index.php $CMS_PATH/www/index.php

    # files in www/js
    $SVN export $SGL_SVNURL/www/js/SGL.js $CMS_PATH/www/js/SGL.js
    $SVN export $SGL_SVNURL/www/js/SGL2.js $CMS_PATH/www/js/SGL2.js

    # files in www/themes
    $SVN export $SGL_SVNURL/www/themes/csshelpers.php $CMS_PATH/www/themes/csshelpers.php
}

commit_sgl_files()
{
   $SVN add $CMS_PATH/COPYING.txt
   $SVN add $CMS_PATH/INSTALL.txt
   $SVN add $CMS_PATH/VERSION.txt
   $SVN add $CMS_PATH/etc/sequence.my.sql
   $SVN add $CMS_PATH/etc/default.conf.dist.ini
   $SVN add $CMS_PATH/www/setup.php
   $SVN add $CMS_PATH/www/index.php
   $SVN add $CMS_PATH/www/optimizer.php
   $SVN add $CMS_PATH/www/themes/csshelpers.php
   $SVN add $CMS_PATH/www/js/SGL.js
   $SVN add $CMS_PATH/www/js/SGL2.js

#   $SVN ci -m "added core SGL exported files"
}

ensure_writable_resources()
{
    $CHMOD -R 777 $CMS_PATH/var
    $CHMOD 777 $CMS_PATH/www
}

symlink_resources()
{
    cd $CMS_PATH/lib
    $LN -s $SGL_PATH/lib/SGL.php
    cd $CMS_PATH
    $LN -s $CMS_PATH/www/themes/simplecms/default/adminNavPrimary.html $CMS_PATH/modulesSCMS/default/templates/adminNavPrimary.html
    $LN -s $CMS_PATH/www/themes/simplecms/default/adminNavSecondary.html $CMS_PATH/modulesSCMS/default/templates/adminNavSecondary.html
}

# ------------
# --- Main ---
# ------------

check_for_sglpath
ensure_writable_resources

# Below 2 steps are needed only for cms-based project setup, no for cms itself.
#
#export_sgl_files
#commit_sgl_files

symlink_resources