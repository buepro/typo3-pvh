=============================================
View helper reference
=============================================

Format
======

Trim
----

Description
~~~~~~~~~~~

`See vhs:format.trim <https://viewhelpers.fluidtypo3.org/fluidtypo3/vhs/5.0.1/Format/Trim.html>`__

Usage examples
~~~~~~~~~~~~~~

::

   {content -> pvh:format.trim()}
   {content -> pvh:format.trim(characters: 'ab')}
   {pvh:format.trim(content: someContent)}
   {pvh:format.trim(content: someContent, characters: 'ab')}

Eliminate
---------

Description
~~~~~~~~~~~

`See vhs:format.eliminate <https://viewhelpers.fluidtypo3.org/fluidtypo3/vhs/5.0.1/Format/Eliminate.html>`__

Usage examples
~~~~~~~~~~~~~~

::

   {content -> pvh:format.eliminate(whitespace: true)}
   {pvh:format.eliminate(content: someContent, whitespace: true)}

Replace
---------

Description
~~~~~~~~~~~

`See vhs:format.replace <https://viewhelpers.fluidtypo3.org/fluidtypo3/vhs/5.0.1/Format/Replace.html>`__

Usage examples
~~~~~~~~~~~~~~

::

   {content -> pvh:format.replace(substring: 'foo', replacement: 'bar')}
   {pvh:format.replace(content: someContent, substring: 'foo', replacement: 'bar')}

Iterator
========

Random
------

Description
~~~~~~~~~~~

Select random elements from a coma separated list, array, traversable or query
result.

+-----------+-------------------------------------------------------+----------+
| Parameter | Description                                           | Default  |
+===========+=======================================================+==========+
| subject   | The subject Traversable/Array instance from which to  |          |
|           | select random elements.                               |          |
+-----------+-------------------------------------------------------+----------+
| count     | Number of randomly selected elements to be returned.  | 1        |
+-----------+-------------------------------------------------------+----------+
| shuffle   | Shuffle the selected elements.                        | true     |
+-----------+-------------------------------------------------------+----------+
| as        | Template variable name to assign. If not specified    |          |
|           | the ViewHelper returns the variable instead.          |          |
+-----------+-------------------------------------------------------+----------+

Usage examples
~~~~~~~~~~~~~~

::

   {pp:iterator.random(subject: someArray, count: 3, as: 'randoms')}
   {someArray -> pp:iterator.random(count: 3, as: 'randoms')}
   {pp:iterator.random(subject: someArray, count: 3) -> f:variable(name: 'randoms')}

Merge
-----

Description
~~~~~~~~~~~

`See vhs:iterator.merge <https://viewhelpers.fluidtypo3.org/fluidtypo3/vhs/5.0.1/Iterator/Merge.html>`__

Usage examples
~~~~~~~~~~~~~~

::

   {pvh:iterator.merge(a: a, b: b, useKeys: useKeys, as: 'merged')}
   {pvh:iterator.merge(a: a, b: b, useKeys: useKeys) -> f:format.json()}

Variable
========

Get
---

Description
~~~~~~~~~~~

`See vhs:format.trim <https://viewhelpers.fluidtypo3.org/fluidtypo3/vhs/5.0.1/Variable/Get.html>`__

Usage examples
~~~~~~~~~~~~~~

::

   {pvh:variable.get(name: 'my.template.var.{index}')}
