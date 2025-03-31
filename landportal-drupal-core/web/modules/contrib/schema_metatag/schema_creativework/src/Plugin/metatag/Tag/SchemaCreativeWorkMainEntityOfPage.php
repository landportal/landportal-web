<?php

namespace Drupal\schema_creativework\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'schema_creativework_main_entity_of_page' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_creativework_main_entity_of_page",
 *   label = @Translation("mainEntityOfPage"),
 *   description = @Translation("RECOMMENDED BY GOOGLE. The canonical URL of the creativework page. Specify mainEntityOfPage when the creativework is the primary topic of the creativework page."),
 *   name = "mainEntityOfPage",
 *   group = "schema_creativework",
 *   weight = 10,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE,
 *   property_type = "url",
 *   tree_parent = {},
 *   tree_depth = -1,
 * )
 */
class SchemaCreativeWorkMainEntityOfPage extends SchemaNameBase {

}
