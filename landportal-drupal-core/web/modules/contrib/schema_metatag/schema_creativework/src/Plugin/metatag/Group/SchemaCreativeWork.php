<?php

namespace Drupal\schema_creativework\Plugin\metatag\Group;

use Drupal\schema_metatag\Plugin\metatag\Group\SchemaGroupBase;

/**
 * Provides a plugin for the 'CreativeWork' meta tag group.
 *
 * @MetatagGroup(
 *   id = "schema_creativework",
 *   label = @Translation("Schema.org: CreativeWork"),
 *   description = @Translation("See Schema.org definitions for this Schema type at <a href="":url"">:url</a>. Also see <a href="":url2"">Google's requirements</a>.", arguments = {
 *     ":url" = "https://schema.org/CreativeWork",
 *     ":url2" = "https://developers.google.com/search/docs/data-types/creativework",
 *   }),
 *   weight = 10
 * )
 */
class SchemaCreativeWork extends SchemaGroupBase {

}
