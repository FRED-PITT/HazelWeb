<?php

/*
 * Copyright 2015 by the University of Pittsburgh
*
* David Galloway
*/

class ConstantsMobile {
  
  public static function getSiteBase() {
    return 'http://' . $_SERVER["HTTP_HOST"] . '/HazelWeb';
  }
  
  const DOJO_CDM = '//ajax.googleapis.com/ajax/libs/dojo/1.10.4/';
  
  const WHERE_IS_DOJO = '../js/vendor/';
  //const WHERE_IS_FREDWEB_API = 'http://localhost/fredweb/api/';
  const WHERE_IS_FREDWEB_API = 'http://fred.publichealth.pitt.edu/fredweb/api/';
   
  const DEFAULT_TZ = 'America/New_York';
  const MYSQL_TIMESTAMP_FORMAT = 'Y-m-d G:i:s';

  const FEEDBACK_MAILTO = 'ddg5@pitt.edu';

}

?>