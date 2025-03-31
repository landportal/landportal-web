<?php

namespace Drupal\schema_creativework\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Provides a plugin for the 'learningresourcetype' meta tag.
 *
 * - 'id' should be a globally unique id.
 * - 'name' should match the Schema.org element name.
 * - 'group' should match the id of the group that defines the Schema.org type.
 *
 * @MetatagTag(
 *   id = "schema_creativework_learningresourcetype",
 *   label = @Translation("Learning Resource Type"),
 *   description = @Translation("The predominant type or kind characterizing the learning resource. For example, 'presentation', 'handout'."),
 *   name = "learningResourceType",
 *   group = "schema_creativework",
 *   weight = 0,
 *   type = "string",
 *   secure = FALSE,
 *   multiple = FALSE,
 *   property_type = "text",
 *   tree_parent = {},
 *   tree_depth = -1,
 * )
 */
class SchemaCreativeWorkLearningResourceType extends SchemaNameBase {

}
