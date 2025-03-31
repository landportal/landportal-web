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
 *   id = "schema_creativework_type",
 *   label = @Translation("@type"),
 *   description = @Translation("REQUIRED. The type of creativework."),
 *   name = "@type",
 *   group = "schema_creativework",
 *   weight = -10,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE,
 *   property_type = "type",
 *   tree_parent = {
 *     "CreativeWork",
 *   },
 *   tree_depth = -1,
 * )
 */
class SchemaCreativeWorkType extends SchemaNameBase {

}
