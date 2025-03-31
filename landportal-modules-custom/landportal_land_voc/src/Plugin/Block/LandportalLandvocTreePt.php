<?php

namespace Drupal\landportal_land_voc\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Land Portal Land Vocabulary' block.
 *
 * @Block(
 *   id = "landportal_landvoc_tree_pt",
 *   admin_label = @Translation("LandVoc tree (pt)"),
 * )
 */
class LandportalLandvocTreePt extends BlockBase
{

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        // Define the HTML content for the block.
        $content = [];

        // Add the static HTML content to the array.
        $content['static_content'] = [
            '#markup' => '
                <div id="body-pt">                            
                </div>',
        ];

        // Attach the library to the render array.
        $content['#attached']['library'][] = 'landportal_land_voc/landportal_land_voc_library';
        $content['#attached']['library'][] = 'landportal_land_voc/landportal_land_voc_library_pt';


        // Return the HTML content as a render array.
        return $content;
    }
}
