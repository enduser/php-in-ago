<?php

class In_Ago {
  
  protected $timezone = 'America/New_York';
  protected $useAbout = true;
  protected $formats = array(
    'second'          => array('%s second', '%s seconds')
  , 'minute'          => array('%s minute', '%s minutes')
  , 'day'             => array('%s day', '%s days')
  , 'week'            => array('%s week', '%s weeks')
  , 'month'           => array('%s month', '%s months')
  , 'year'            => array('%s year', '%s years')
  , 'about'           => 'about %s'
  , 'in'              => 'in %s'
  , 'ago'             => '%s ago'
  );
  
  public function setTimezone( $str )
  {
    $this->timezone = $str;
  }
  
  public function setFormat( $key, $val )
  {
    $this->formats[$key] = $val;
    return $this;
  }
  
  public function setFormats( $formats )
  {
    $this->formats = $formats;
    return $this;
  }
  
  public function format( $date )
  {
    $date = $this->getTime( $date );
    $now = new DateTime(null, new DateTimeZone( $this->timezone ) );
    
    // lets get the difference
    $seconds = (int) $date->format('U') - (int) $now->format('U');
    $future = $seconds > 0;
    
    $duration = $this->formatDate( (int)$seconds );
    if( $this->useAbout ) $duration = sprintf( $this->formats['about'], $duration );
    return sprintf( $this->formats[$future?'in':'ago'], $duration );
  }
  
  protected function getTime( $date )
  {
    if( $date instanceof DateTime ){
      $date->setTimeZone( new DateTimeZone( $this->timezone ) );
    }
    else {
      $date = new DateTime( $date, new DateTimeZone( $this->timezone ) );
    }
    return $date;
  }
  
  protected function formatDate( $seconds )
  {
    $breaks = array(
      60                  => 'second'
    , 60*60               => 'minute'
    , 60*60*24            => 'day'
    , 60*60*24*7          => 'week'
    , 60*60*24*30         => 'month'
    , 60*60*24*365        => 'year'
    );
    
    foreach( $breaks as $break => $format ){
      if( $seconds < $break ) break;
    }
    
    switch( $format ){
      case 'second':
        $duration = $seconds;
        break;
      case 'minute':
        $duration = round( $seconds / 60, 0 );
        break;
      case 'hour':
        $duration = round( $seconds / 60 / 60, 0 );
        break;
      case 'day':
        $duration = round( $seconds / 60 / 60 / 24, 0 );
        break;
      case 'week':
        $duration = round( $seconds / 60 / 60 / 24 / 7, 0 );
        break;
      case 'month':
        $duration = round( $seconds / 60 / 60 / 24 / 30, 0 );
        break;
      case 'year':
        $duration = round( $seconds / 60 / 60 / 24 / 365, 0 );
        break;
    }
    
    return sprintf( $this->formats[$format][$duration==1?0:1], $duration );
    
  }
}
