<?php
 /**
  * Default implementation of myswf configurator contract
  */
 namespace t0t1\mysfw\module;
 use t0t1\mysfw\frame;

 $this->_learn('frame\dna');
 $this->_learn('frame\contract\configurator');

 class configurator extends frame\dna implements frame\contract\configurator, frame\contract\dna {
  private $_repository; 

  protected function _get_ready(){
   $this->_repository = [];
  }

  /**
   * XXX draft
   */
  public function dump() {
   return print_r($this->_repository, true);
  }

  /** Overrides the generic behaviour implemented in dna **/
  public function define($c, $v, $cc = '_default_'){
   if(isset($this->_repository[$cc][$c])){
    throw $this->except("Configuration entry ($cc, $c) already exists");
   }
   $this->_repository[$cc][$c] = $v;
   return $this;
  }

  // XXX _default_ ?
  public function inform($c, $cc = '_default_'){
   if(isset($this->_repository[$cc][$c]))
    return $this->_repository[$cc][$c];
   return null;
  }

  public function configure($module) {
   if(! $defaults = $module->get_defaults()) return;
   $context       = $module->get_configuration_context();
   $custom_conf   = $module->get_custom_conf();
   $conf          = [];
   foreach($defaults as $entry => $value){
    if(isset($custom_conf[$entry])){
     self::define($entry, $custom_conf[$entry], $context);
    }else{
     if(! self::inform($entry, $context)){
      self::define($entry, $value, $context);
     }
    }
    $conf[$entry] = self::inform($entry, $context); // XXX TEMP DRAFT
   }
   $module->set_conf($conf);
  }
 }
