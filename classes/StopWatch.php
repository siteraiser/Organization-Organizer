<?php class StopWatch {
//from github https://gist.github.com/phybros/5766062
  private static $startTimes = array();
  public static function start($timerName = 'default'){
    self::$startTimes[$timerName] = microtime(true);
  }
  public static function elapsed($timerName = 'default'){
    return microtime(true) - self::$startTimes[$timerName];
  }
}