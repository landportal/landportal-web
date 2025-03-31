<?php

namespace Drupal\landportal_user_custom\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\user\Entity\User;

/**
 * @FieldFormatter(
 *   id = "custom_user_name_formatter",
 *   label = @Translation("Custom User Name Formatter"),
 *   field_types = {
 *     "entity_reference"
 *   }
 * )
 */
class UserNameFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      /** @var \Drupal\Core\Field\FieldItemInterface $item */
      $user_id = $item->target_id;
      $user = User::load($user_id);
      $formatted_user_info = [];
      if ($user) {
          // Retrieve the fields from the user entity.
          $first_name = $user->get('first_name')->value; 
          $last_name = $user->get('last_name')->value;   
          $title = $user->get('title')->value;           
          $user_name = $user->get('name')->value;

          // Check if username contains "hybridauth_Google_".
          $username_contains_invalid_pattern = strpos($user_name, 'hybridauth_Google_') !== false;

          // Format the user information.
          if (!empty($first_name) || !empty($last_name)) {
              // Show the title, first name, and last name if available.
              $formatted_user_info[] = $title . ' ' . $first_name . ' ' . $last_name;
          } elseif (!$username_contains_invalid_pattern) {
              // If both first name and last name are empty, and username doesn't contain the invalid pattern, show the username.
              $formatted_user_info[] = $user_name;
          }
          $user_full_name = implode(', ', $formatted_user_info);
          $elements[$delta] = [
            '#markup' => $user_full_name
          ];
      }

      /*
      if ($user) {
        $first_name = $user->get('first_name')->value;
        $last_name = $user->get('last_name')->value;
        $elements[$delta] = [
          '#markup' => trim($first_name . ' and ' . $last_name),
        ];
      }
      */

    }
    return $elements;
  }
}
