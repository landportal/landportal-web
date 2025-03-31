<?php
namespace Drupal\landportal_rdf\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use SimpleXMLElement;
use Drupal\Core\Link;
use Drupal\taxonomy\Entity\Term;
use Drupal\Core\Url;
use DOMDocument;
use DOMAttr;
use DateTime;
use Drupal\Core\Path\AliasStorageInterface;
use Symfony\Component\HttpFoundation\Request;

class LandportalRdfController extends ControllerBase { //class start

  public function myPage() { //myPage() function start
    $current_path = \Drupal::service('path.current')->getPath();
    $current_path_arr = explode('/', $current_path);
    $current_path_nid = $current_path_arr['2'];
    $node = Node::load($current_path_nid);
    
    $uid = $node->getOwnerId();
    $user = \Drupal\user\Entity\User::load($uid);
    $username = $user->getDisplayName();
    //$variables['$username'] = $username;

    $username = strtolower(str_replace(" ", "-", $username));
    $host = \Drupal::request()->getSchemeAndHttpHost();

    $title_val = $node->title->value;
    $uuid = $node->uuid->value;
    $bundle = $node->type->target_id;
    $bundle_hyph = str_replace('_', '-', $bundle);

    $node_form  = \Drupal\node\Entity\Node::load($current_path_nid);
    $content = \Drupal::service('entity.form_builder')->getForm($node_form);

    $comments = $node->comments->comment_count;
    // Get the URL object for the node.
    $url_object = Url::fromRoute('entity.node.canonical', ['node' => $node_form->id()]);

    // Get the path alias from the URL object.
    $path_alias = $url_object->toString();
    

    // Updated date of node
    $updated_date = $content['changed']['#value'];
    $datetime = new \DateTime();
    $datetime->setTimestamp($updated_date);
    $datetime->setTimezone(new \DateTimeZone('Europe/Rome'));
    $modified_date = $datetime->format('c');

    //Get time zone from Updated date and put in created date.
    $time_zone = explode('+', $modified_date);

    //created date of node
    $date_value = $content['created']['widget'][0]['value']['#value'];
    $date_only = $date_value['date'];
    $time_only = $date_value['time'];
    $created_date = $date_only.'T'.$time_only.'+'.$time_zone[1];

    $bundle_capt = ucwords(str_replace('_', ' ', $bundle));
    $bundle_captizle = str_replace(' ', '', $bundle_capt);
 
    if ($bundle == 'organization') {
      include_once 'node/organization.php';
    }
    if($bundle == 'country_narrative'){
      include_once 'node/country_narrative.php';
    }
    if($bundle == 'project'){
      include_once 'node/project.php';
    }
    if($bundle == 'news'){
      include_once 'node/news.php';
    }
    if($bundle == 'blog_post'){
      include_once 'node/blog_post.php';
    }
    if($bundle == 'landlibrary_resource'){
      include_once 'node/landlibrary_resource.php';
    }
    if($bundle == 'issues'){
      include_once 'node/issues.php';
    }
    if($bundle == 'event'){
      include_once 'node/event.php';
    }
    if($bundle == 'blog_post'){
      include_once 'node/blog_post.php';
    }
    if($bundle == 'campaign'){
      include_once 'node/campaign.php';
    }
    if($bundle == 'debate'){
      include_once 'node/debate.php';
    }
    if($bundle == 'impact_story'){
      include_once 'node/impact_story.php';
    }
    if($bundle == 'dataset'){
      include_once 'node/dataset.php';
    }
    if($bundle == 'dataset'){
      include_once 'node/dataset.php';
    }
    if($bundle == 'landing_page'){
      include_once 'node/landing_page.php';
    }
    if($bundle == 'sdgi'){
      include_once 'node/sdgi.php';
    }    
    if($bundle == 'webinar_series'){
      include_once 'node/webinar_series.php';
    }
    if($bundle == 'sliders'){
      include_once 'node/sliders.php';
    }
    


    exit();

  }//myPage() function end
}//class end
?>