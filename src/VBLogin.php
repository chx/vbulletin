<?php

namespace Drupal\vbulletin;

define('CSRF_PROTECTION', false);

class VBLogin {

  public static function init($vbpath) {
    if (!class_exists('\vB5_Autoloader')) {
      require_once($vbpath . '/includes/vb5/autoloader.php');
      \vB5_Autoloader::register($vbpath);
      \vB5_Frontend_Application::init('config.php');
    }
  }

  /**
   * This method logs a user into a vBulletin instance.
   *
   * Warning: there is no authentication whatsoever. This is to be used only
   * when a third party system does authentication.
   *
   * @param $username
   */
  public static function loginWithoutAuthentication($username) {
    \vB::getDbAssertor()->delete('session', array('sessionhash' => \vB::getCurrentSession()->get('dbsessionhash')));
    $username = \vB_String::htmlSpecialCharsUni($username);
    $userinfo = \vB::getDbAssertor()->getRow('user', array('username' => $username));
    $auth = array_intersect_key($userinfo, array_flip(['userid', 'lastvisit', 'lastactivity']));
    $loginInfo = \vB_User::processNewLogin($auth);
    \vB5_Auth::setLoginCookies($loginInfo);
  }

}
