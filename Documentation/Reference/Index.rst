=============================================
View helper reference
=============================================

Format
======

Trim
----

Description
~~~~~~~~~~~

`See vhs format.trim <https://viewhelpers.fluidtypo3.org/fluidtypo3/vhs/5.0.1/Format/Trim.html>`__

Usage examples
~~~~~~~~~~~~~~

::

   {content -> pvh:format.trim()}
   {content -> pvh:format.trim(characters: 'ab')}
   {pvh:format.trim(content: someContent)}
   {pvh:format.trim(content: someContent, characters: 'ab')}
