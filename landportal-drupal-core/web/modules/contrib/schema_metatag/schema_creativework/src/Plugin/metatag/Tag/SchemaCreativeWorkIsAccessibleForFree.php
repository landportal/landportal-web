<?php

namespace Drupal\schema_creativework\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'schema_creativework_is_accessible_for_free' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_creativework_is_accessible_for_free",
 *   label = @Translation("isAccessibleForFree"),
 *   description = @Translation("RECOMMENDED BY GOOGLE. Use for <a href="":url"">Paywalled content</a>.", arguments = {
 *     ":url" = "https://developers.google.com/search/docs/data-types/paywalled-content",
 *   }),
 *   name = "isAccessibleForFree",
 *   group = "schema_creativework",
 *   weight = 4,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE,
 *   property_type = "boolean",
 *   tree_parent = {},
 *   tree_depth = -1,
 * )
 */
class SchemaCreativeWorkIsAccessibleForFree extends SchemaNameBase {

}
