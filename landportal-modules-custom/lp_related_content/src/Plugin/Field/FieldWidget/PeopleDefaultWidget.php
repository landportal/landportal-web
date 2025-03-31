<?php

namespace Drupal\lp_related_content\Plugin\Field\FieldWidget;

use Drupal\Core\Entity\Element\EntityAutocomplete;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Plugin implementation of the 'lp_related_content_people_default' widget.
 *
 * @FieldWidget(
 *     id="lp_related_content_people_default",
 *     module="lp_related_content",
 *     label=@Translation("Default"),
 *     field_types={
 *         "lp_related_content_people"
 *     }
 * )
 */
class PeopleDefaultWidget extends WidgetBase {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    /*
    $default_value = NULL;

    // Build a "label (swid)' value that can be parse for storage.
    if (!empty($items[$delta]->swid)) {
      $default_value = sprintf('%s (%d)', $items[$delta]->fullname, $items[$delta]->swid);
    }
    */
    $auth = base64_encode("landportal:landportal");
    $context = stream_context_create([
        "http" => [
            "header" => "Authorization: Basic $auth"
        ]
    ]);
    //Get Json Url for Content Types names' Select List.
    //$json = file_get_contents('https://landportal.org/content-type-names', false);
    $json = file_get_contents('/var/www/html/web/modules/custom/lp_related_content/js/content-types-names.json', false);
    $obj = json_decode($json, true);
    $machine_name = $obj['nodes'];
    
    $arr_data_type_options = array();
    $arr_data_type_options['all'] = t('--- SELECT ---');
    
    foreach($machine_name as $key => $machine_names){
      $val = $machine_names['node']['machine_name'];
      $arr_data_type_options[$val] = $machine_names['node']['type'];  
    }

    //Get Json Url for Region.
    //$country_url = file_get_contents('https://landportal.org/project-regions', false);
    $country_url = file_get_contents('/var/www/html/web/modules/custom/lp_related_content/js/project-regions.json', false);
    $object = json_decode($country_url, true);
    $country_name = $object['nodes'];
      
    $arr_country_data = array();
    $arr_country_data['all'] = t('--- SELECT ---');

    foreach($country_name as $key => $country_names){
      $var1 = $country_names['node']['tid'];
      $arr_country_data[$var1] = $country_names['node']['name'];
    }

    $element['#attached']['library'][] = 'lp_related_content/lp-related-content';
    $element['data_type'] = [
      '#type' => 'select',
      '#options' => $arr_data_type_options,
    ];

    $element['#attached']['library'][] = 'lp_related_content/lp-related-content';
    $element['regional'] = [
      '#title' => $this->t('Select Country'),
      '#type' => 'select',
      '#options' => $arr_country_data,
     ];

    $element['data'] = [
      '#title' => $this->t('Select related content'),
      '#type' => 'textfield',
      '#autocomplete_route_name' => 'lp_related_content.people.autocomplete',
      '#autocomplete_route_parameters' => array('type' => 'all', 'regional' => 'all'),
      '#placeholder' => t('Write title of content here'),
      '#maxlength' => '1232',
      '#description' => t('For Adding Another Item, Please Wait! For 5 Second.'),
      '#element_validate' => [
        [static::class, 'validate'],
      ],  
    ];
    $element['#attached']['library'][] = 'lp_related_content/sweat-alert-lib';
    $element['#attached']['library'][] = 'lp_related_content/lp-related-content-edit';
    $element['data_container'] = [
      '#title' => $this->t('Container1'),
      '#type' => 'container',
      '#attributes' => array(
        'class' => 'accommodation',
      ),
      '#empty_value' => '',
      '#markup' => '<h2>X</h2><a href=""></a><p></p>',
    ];

    $element['#attached']['library'][] = 'lp_related_content/lp-related-content-style';
    $element['fullname'] = [
      '#title' => $this->t('Related Content Type'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->fullname) ? $items[$delta]->fullname : null,
    ];

    $element['swid'] = [
      '#title' => $this->t('Related Content Id'),
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->swid) ? $items[$delta]->swid : null,
    ];    

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    $item = NULL;

    foreach ($values as $delta => &$item) {
      $item['delta'] = $delta;

      // Take "label (entity id)', match the ID from inside the parentheses.
      // @see \Drupal\Core\Entity\Element\EntityAutocomplete::extractEntityIdFromAutocompleteInput
      if (preg_match('/(.+\\s)\\(([^\\)]+)\\)/', $item['data'], $matches)) {
        $item['fullname'] = trim($matches[1]);
        $item['swid'] = trim($matches[2]);
      }
    }

    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public static function validate($element, FormStateInterface $form_state) {
    \Drupal::logger('lp_related_content')->notice("testing1");
    $value = $element['#value'];
    $value = "4";
    $id = EntityAutocomplete::extractEntityIdFromAutocompleteInput($value);

    if (empty($id)) {
      $form_state->setValueForElement($element, '');

      return;
    }

    $response = \Drupal::httpClient()->get('https://swapi.dev/api/people/' . $id);

    if ($response->getStatusCode() !== 200) {
      $form_state->setError($element, t('This Related content does not exists.'));
    }

    $character = json_decode($response->getBody()->getContents(), TRUE);

    if (!isset($character['url'], $character['name'])) {
      $form_state->setError($element, t('This Related content does not exists.'));
    }
  }

}
