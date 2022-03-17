#!/usr/bin/env bash

#=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*
#
# Creates changelog file for a release
# ====================================
#
# Usage
# -----
#
# 1. Change to project directory
# 2. Run: ./Build/scripts/ccl.sh fromVersion targetVersion
#
# For escaping sequences see as well https://i.stack.imgur.com/NfH6K.png
#
#=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*=*

#
# Usage: write_commit startCommit endCommit selector
#
function write_commits {
  git log "$1".."$2" --pretty="* %s (%cd, %h by %an)" --date=format:%d.%m.%Y --abbrev-commit --grep $3
}

target=./Documentation/Changelog/$2.rst

echo ".. include:: /Includes.rst.txt

.. highlight:: none

====================================
Changelog for release $2
====================================

Features
========
$(write_commits "$1" "$2" "\\[FEATURE\\]")

Bugfixes
========
$(write_commits "$1" "$2" "\\[BUGFIX\\]")

Breaking changes
================
$(write_commits "$1" "$2" "\\[!!!\\]")

Reference
=========

.. highlight:: shell

Generated by:

git log $1..$2 --pretty=\"* %s (%cd, %h by %an)\" --date=format:%d.%m.%Y --abbrev-commit --grep $3

**Note:** The above list contains just commits marked with [FEATURE], [BUGFIX] and [!!!]. Complementary commits are
available at \`Github <https://github.com/buepro/typo3-pvh/commits/main)>\`__.

" > "$target"


