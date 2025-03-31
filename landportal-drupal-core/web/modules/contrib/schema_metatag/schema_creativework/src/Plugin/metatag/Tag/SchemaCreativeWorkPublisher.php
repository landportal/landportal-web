<?php

namespace Drupal\schema_creativework\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'publisher' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_creativework_publisher",
 *   label = @Translation("publisher"),
 *   description = @Translation("REQUIRED BY GOOGLE. Publisher of the creativework."),
 *   name = "publisher",
 *   group = "schema_creativework",
 *   weight = 6,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE,
 *   property_type = "organization",
 *   tree_parent = {
 *     "Person",
 *     "Organization",
 *   },
 *   tree_depth = 0,
 * )
 */
class SchemaCreativeWorkPublisher extends SchemaNameBase {

}
