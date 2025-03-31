<?php

namespace Drupal\schema_creativework\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'datePublished' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_creativework_date_published",
 *   label = @Translation("datePublished"),
 *   description = @Translation("REQUIRED BY GOOGLE. Date the creativework was published."),
 *   name = "datePublished",
 *   group = "schema_creativework",
 *   weight = 3,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE,
 *   property_type = "date",
 *   tree_parent = {},
 *   tree_depth = -1,
 * )
 */
class SchemaCreativeWorkDatePublished extends SchemaNameBase {

}
