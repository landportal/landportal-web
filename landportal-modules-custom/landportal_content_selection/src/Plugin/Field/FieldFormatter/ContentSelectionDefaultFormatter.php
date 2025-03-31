<?php
/**
 * @file
 * Contains \Drupal\ContentSelection\Plugin\Field\FieldType\ContentSelectionDefaultFormatter.
 */

namespace Drupal\landportal_content_selection\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\node\Entity\Node;
use Drupal\Core\Url;
use Drupal;

/**
 * Plugin implementation of the 'ContentSelectionDefaultFormatter' formatter.
 *
 * @FieldFormatter(
 *   id = "ContentSelectionDefaultFormatter",
 *   label = @Translation("ContentSelection"),
 *   field_types = {
 *     "ContentSelection"
 *   }
 * )
 */
class ContentSelectionDefaultFormatter extends FormatterBase {

  /**
   * Define how the field type is showed.
   * 
   * Inside this method we can customize how the field is displayed inside 
   * pages.
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    $org_type_mul = [];
    foreach ($items as $delta => $item) {
      
      $organization = $item->label;
      $organization_node =  explode(" ", $organization);
      $org_get_id = end($organization_node);
      $org_get_id_trim = trim($org_get_id,"()");

      $arrayName = array($org_get_id_trim);

      if($item->label) {
        $nodes =  \Drupal\node\Entity\Node::loadMultiple($arrayName);
        foreach ($nodes as $node) {
          $title = $node->getTitle();
          $acronym = $node->get('field_acronym')->getString();
          $body = $node->body->value;
          $org_type = $node->get('field_organization_type')->getString();
          $org_type_mul = explode(",", $org_type);
        }

        $trim_body = substr($body,0,60);
//\Drupal::logger('String')->notice('trim'.serialize($string));

        $org_types_str = "";
        $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($org_type_mul);
        foreach ($terms as $term) {
          $term_url = \Drupal::service('path_alias.manager')->getAliasByPath('/taxonomy/term/' . $term->Id());
          $term_name = $term->getName();
          $org_types_str = $org_types_str . '<span class="org-relation"><a href="' . $term_url . '">' . $term_name . '</a></span>';
        }


        $node = Node::load($org_get_id_trim);
        $org_img_field = "";
        if(isset($node->field_image->entity)){
          $uri = $node->field_image->entity->getFileUri();
          $url = \Drupal::service('file_url_generator')->generateAbsoluteString($uri);
          $org_img_field = '<img src="'.$url.'">';
        }

        $urlss = Url::fromRoute('entity.node.canonical', ['node' => $org_get_id_trim])->toString();

        $elements[$delta]['label'] = [
          '#type' => 'item',
          '#prefix' => '<div class="org-affi-wrapper"><div class="org-affi-image"><a href="'.$urlss.'">'.$org_img_field . '</a></div> <div class="org-affi-title"><a href="'.$urlss.'">'.$title.'</a></div><div class="org-affi-acronym">'.$acronym.'</div>' . $org_types_str .'<span class="org-affi-body">'. $trim_body.'</span></div>',
        ];
      }
    }
    return $elements;
  }
  
} // class