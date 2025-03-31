=== Related Block ===


== Description ==

MyDrupalModule is a custom Drupal 9 & 10 module that [briefly describe the purpose or functionality of Related Block module].

== Installation ==
1. Install the module as usual.

== Funtionality ==
This module provide the autocomplete field for block reference.
It is a Block reference module.
In this module you can add any view from the site and put it in the node.
Just create this field from "manage field" and click on "Landportal Related Blocks".

== Precautions ==
I have create this custom field type module for 'SDGi' content type only. If you want to create this field in another content type then you have to write little code in the 'landportal_related_block.module' file. e.g

on line no# 7 in .module file 
add this  
"|| $node->getType() == 'YOUR_CONTENT_TYPE_NAME')".
e.g
  if ($node->getType() == 'sdgi' || $node->getType() == 'YOUR_CONTENT_TYPE_NAME') {

  }



