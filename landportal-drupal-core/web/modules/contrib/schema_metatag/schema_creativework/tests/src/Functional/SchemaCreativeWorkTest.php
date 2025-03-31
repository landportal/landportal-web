<?php

namespace Drupal\Tests\schema_creativework\Functional;

use Drupal\Tests\schema_metatag\Functional\SchemaMetatagTagsTestBase;

/**
 * Tests that each of the Schema Metatag CreativeWorks tags work correctly.
 *
 * @group schema_metatag
 * @group schema_creativework
 */
class SchemaCreativeWorkTest extends SchemaMetatagTagsTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['schema_creativework'];

  /**
   * {@inheritdoc}
   */
  public $moduleName = 'schema_creativework';

  /**
   * {@inheritdoc}
   */
  public $groupName = 'schema_creativework';

}
