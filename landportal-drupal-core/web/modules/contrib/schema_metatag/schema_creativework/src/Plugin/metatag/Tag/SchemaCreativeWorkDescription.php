<?php

namespace Drupal\schema_creativework\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'schema_creativework_description' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_creativework_description",
 *   label = @Translation("description"),
 *   description = @Translation("RECOMMENDED BY GOOGLE. A description of the item."),
 *   name = "description",
 *   group = "schema_creativework",
 *   weight = 1,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE,
 *   property_type = "text",
 *   tree_parent = {},
 *   tree_depth = -1,
 * )
 */
class SchemaCreativeWorkDescription extends SchemaNameBase {

}
