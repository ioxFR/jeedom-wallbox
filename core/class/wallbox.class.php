<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

/* * ***************************Includes********************************* */
require_once __DIR__  . '/../../../../core/php/core.inc.php';

class wallbox extends eqLogic {
   /*     * *************************Attributs****************************** */
   /*
   * Permet de définir les possibilités de personnalisation du widget (en cas d'utilisation de la fonction 'toHtml' par exemple)
   * Tableau multidimensionnel - exemple: array('custom' => true, 'custom::layout' => false)
   public static $_widgetPossibility = array();
   */
   
   /*     * ***********************Methode static*************************** */
   
   // Fonction exécutée automatiquement toutes les minutes par Jeedom
   public static function cron() {
      $username = config::byKey("username", "wallbox");
      $password = config::byKey("password", "wallbox");
      if($username == null || $password == null){
         throw new Exception("You must configure username and password to be able to use this plugin");
      }
      foreach (self::byType('wallbox') as $wallbox) {//parcours tous les équipements du plugin
         if ($wallbox->getIsEnable() == 1) {//vérifie que l'équipement est actif
             $cmd = $wallbox->getCmd(null, 'refresh');//retourne la commande "refresh si elle existe
             if (!is_object($cmd)) {//Si la commande n'existe pas
               continue; //continue la boucle
             }
             $cmd->execCmd(); // la commande existe on la lance
         }
     }
   }
   
   
   
   // Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
   public static function cron5() {
      $username = config::byKey("username", "wallbox");
      $password = config::byKey("password", "wallbox");
      if($username == null || $password == null){
         throw new Exception("You must configure username and password to be able to use this plugin");
      }
      foreach (self::byType('wallbox') as $wallbox) {//parcours tous les équipements du plugin
         if ($wallbox->getIsEnable() == 1) {//vérifie que l'équipement est actif
             $cmd = $wallbox->getCmd(null, 'refresh');//retourne la commande "refresh si elle existe
             if (!is_object($cmd)) {//Si la commande n'existe pas
               continue; //continue la boucle
             }
             $cmd->execCmd(); // la commande existe on la lance
         }
     }
   }
   
   
   /*
   * Fonction exécutée automatiquement toutes les 10 minutes par Jeedom
   public static function cron10() {
   }
   */
   
   /*
   * Fonction exécutée automatiquement toutes les 15 minutes par Jeedom
   public static function cron15() {
   }
   */
   
   /*
   * Fonction exécutée automatiquement toutes les 30 minutes par Jeedom
   public static function cron30() {
   }
   */
   
   /*
   * Fonction exécutée automatiquement toutes les heures par Jeedom
   public static function cronHourly() {
   }
   */
   
   /*
   * Fonction exécutée automatiquement tous les jours par Jeedom
   public static function cronDaily() {
   }
   */
   
   
   
   /*     * *********************Méthodes d'instance************************* */
   
   // Fonction exécutée automatiquement avant la création de l'équipement 
   public function preInsert() {
      $username = config::byKey("username", "wallbox");
$password = config::byKey("password", "wallbox");
if($username == null || $password == null){
   throw new Exception("You must configure username and password to be able to use this plugin");
}
   }
   
   // Fonction exécutée automatiquement après la création de l'équipement 
   public function postInsert() {
      
   }
   
   // Fonction exécutée automatiquement avant la mise à jour de l'équipement 
   public function preUpdate() {
      
   }
   
   // Fonction exécutée automatiquement après la mise à jour de l'équipement 
   public function postUpdate() {
      
   }
   
   // Fonction exécutée automatiquement avant la sauvegarde (création ou mise à jour) de l'équipement 
   public function preSave() {
      
   }
   
   public function postSave() {
      // Charging power
      $power = $this->getCmd(null, 'power');
      if (!is_object($power)) {
         $power = new wallboxCmd();
         $power->setName(__('Puissance', __FILE__));
      }
      $power->setLogicalId('power');
      $power->setEqLogic_id($this->getId());
      $power->setType('info');
      $power->setSubType('numeric');
		$power->setUnite('Amp');
      $power->setConfiguration('minValue' , '0');
      $power->setConfiguration('maxValue' , '32');
      $power->save();

      // max power available
      $maxpower = $this->getCmd(null, 'maxpower');
      if (!is_object($maxpower)) {
         $maxpower = new wallboxCmd();
         $maxpower->setName(__('Puissance maximum', __FILE__));
      }
      $maxpower->setLogicalId('maxpower');
      $maxpower->setEqLogic_id($this->getId());
      $maxpower->setType('info');
      $maxpower->setSubType('numeric');
		$maxpower->setUnite('Amp');
      $power->setConfiguration('minValue' , '0');
      $power->setConfiguration('maxValue' , '32');
      $maxpower->save();

      // charging speed
      $speed = $this->getCmd(null, 'speed');
      if (!is_object($speed)) {
         $speed = new wallboxCmd();
         $speed->setName(__('Vitesse de charge', __FILE__));
      }
      $speed->setLogicalId('speed');
      $speed->setEqLogic_id($this->getId());
      $speed->setType('info');
      $speed->setSubType('numeric');
      $power->setConfiguration('minValue' , '0');
      $power->setConfiguration('maxValue' , '32');
		$speed->setUnite('Amp/h');
      $speed->save();

      // state of charge
      $chargestatus = $this->getCmd(null, 'chargestatus');
      if (!is_object($chargestatus)) {
         $chargestatus = new wallboxCmd();
         $chargestatus->setName(__('Statut de la charge', __FILE__));
      }
      $chargestatus->setLogicalId('chargestatus');
      $chargestatus->setEqLogic_id($this->getId());
      $chargestatus->setType('info');
      $chargestatus->setSubType('string');
      $chargestatus->save();

      // last sync
      $lastsync = $this->getCmd(null, 'lastsync');
      if (!is_object($lastsync)) {
         $lastsync = new wallboxCmd();
         $lastsync->setName(__('Dernière Synchronisation', __FILE__));
      }
      $lastsync->setLogicalId('lastsync');
      $lastsync->setEqLogic_id($this->getId());
      $lastsync->setType('info');
      $lastsync->setSubType('string');
      $lastsync->save();
      
      //status
      $status = $this->getCmd(null, 'status');
      if (!is_object($status)) {
         $status = new wallboxCmd();
         $status->setName(__('Statut', __FILE__));
      }
      $status->setLogicalId('status');
      $status->setEqLogic_id($this->getId());
      $status->setType('info');
      $status->setSubType('string');
      $status->save();
      
      // Name
      $name = $this->getCmd(null, 'name');
      if (!is_object($name)) {
         $name = new wallboxCmd();
         $name->setName(__('Name', __FILE__));
      }
      $name->setLogicalId('name');
      $name->setEqLogic_id($this->getId());
      $name->setType('info');
      $name->setSubType('string');
      $name->save();
      
      // Refresh action
      $refresh = $this->getCmd(null, 'refresh');
      if (!is_object($refresh)) {
         $refresh = new wallboxCmd();
         $refresh->setName(__('Rafraichir', __FILE__));
      }
      $refresh->setEqLogic_id($this->getId());
      $refresh->setLogicalId('refresh');
      $refresh->setType('action');
      $refresh->setSubType('other');
      $refresh->save();

      // TODO: CRON Configuration
     /* $cron = cron::byClassAndFunction('weather', 'updateWeatherData', array('weather_id' => intval($this->getId())));
      if (!is_object($cron)) {
          $cron = new cron();
          $cron->setClass('weather');
          $cron->setFunction('updateWeatherData');
          $cron->setOption(array('weather_id' => intval($this->getId())));
      }
      $cron->setSchedule($this->getConfiguration('refreshCron', CRON));
      */
   }
   
   // Fonction exécutée automatiquement après la sauvegarde (création ou mise à jour) de l'équipement 
   
   
   // Fonction exécutée automatiquement avant la suppression de l'équipement 
   public function preRemove() {
      
   }
   
   // Fonction exécutée automatiquement après la suppression de l'équipement 
   public function postRemove() {
      
   }
   
   // Function to get authentication JWT based on basic auth
   public function getWallboxToken(){     
      $baseurl = "https://api.wall-box.com/";
      log::add('wallbox', 'debug', 'in authentication ' );
      // AUTHENTICATION
      $username = config::byKey("username", "wallbox");
      $password = config::byKey("password", "wallbox");
      
      // We encode in base64
      $authenticationencoded = base64_encode($username.":".$password);
      // we do the request to get token
      $opts = array('http' =>
      array(
         'method'  => 'GET',
         'header'  => 'Authorization: Basic '.$authenticationencoded
         )
      );
      
      $context  = stream_context_create($opts);
      
      $result = file_get_contents($baseurl.'auth/token/user', false, $context);
      $objectresult = json_decode($result,true);
      
      
      if($objectresult['status'] == "200"){
         log::add('wallbox', 'debug', 'Authentication ' . json_decode($result,true));
         $token = $objectresult['jwt'];
         return $token;
      }
      throw new Exception($objectresult);
   }
   
   // Function to get a list of chargers
   public function getChargerList(){
      $baseurl = "https://api.wall-box.com/";
      log::add('wallbox', 'debug', 'start charger list');
      $jwt = $this->getWallboxToken();
      
      log::add('wallbox', 'debug', 'jwt '. $jwt);
      if($jwt != null){
         $opts = array('http' =>
         array(
            'method'  => 'GET',
            'header'  => 'Authorization: Bearer '.$jwt
            )
         );
         
         $context  = stream_context_create($opts);
         
         $result = file_get_contents($baseurl.'v3/chargers/groups', false, $context);
         $objectresult = json_decode($result,true);

         // We return only struct of chargers
         $chargers = $objectresult['result']['groups'][0]['chargers'];
         log::add('wallbox', 'information', 'charger list '. $chargers);
         return $chargers;
      }
      else{
         throw new Exception("User is not authenticated");
      }
   }
   // Function to get charger status
   public function getChargerStatus(){
      $baseurl = "https://api.wall-box.com/";
      $chargerId = $this->getConfiguration("chargerid");
      log::add('wallbox', 'debug', 'start for charger '. $chargerId);
      $jwt = $this->getWallboxToken();
      
      log::add('wallbox', 'debug', 'jwt '. $jwt);
      if($jwt != null && $chargerId != null){
         $opts = array('http' =>
         array(
            'method'  => 'GET',
            'header'  => 'Authorization: Bearer '.$jwt
            )
         );
         
         $context  = stream_context_create($opts);
         
         $result = file_get_contents($baseurl.'chargers/status/'.$chargerId, false, $context);
         $objectresult = json_decode($result,true);
         return $objectresult;
      }
      else{
         throw new Exception("User is not authenticated");
      }
   }
   
   /*
   * Non obligatoire : permet de modifier l'affichage du widget (également utilisable par les commandes)
   public function toHtml($_version = 'dashboard') {
      
   }
   */
   
   /*
   * Non obligatoire : permet de déclencher une action après modification de variable de configuration
   public static function postConfig_<Variable>() {
   }
   */
   
   /*
   * Non obligatoire : permet de déclencher une action avant modification de variable de configuration
   public static function preConfig_<Variable>() {
   }
   */
   
   /*     * **********************Getteur Setteur*************************** */
}

class wallboxCmd extends cmd {
   /*     * *************************Attributs****************************** */
   
   /*
   public static $_widgetPossibility = array();
   */
   
   /*     * ***********************Methode static*************************** */
   
   
   /*     * *********************Methode d'instance************************* */
   
   /*
   * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
   public function dontRemoveCmd() {
      return true;
   }
   */
   
   
   
   // Exécution d'une commande  
   public function execute($_options = null) {
      $eqlogic = $this->getEqLogic(); //récupère l'éqlogic de la commande $this
      
      if ($this->getLogicalId() == 'refresh') {
         
         $info = $this->getEqLogic()->getChargerStatus();
         $eqlogic->checkAndUpdateCmd('name', $info['name']);
         $eqlogic->checkAndUpdateCmd('lastsync', utctolocal($info['last_sync']));
         $eqlogic->checkAndUpdateCmd('status', $info['status_description']);
         $eqlogic->checkAndUpdateCmd('power', $info['charging_power']);
         $eqlogic->checkAndUpdateCmd('speed', $info['charging_speed']);
         $eqlogic->checkAndUpdateCmd('chargestatus', $info['state_of_charge']);
         $eqlogic->checkAndUpdateCmd('maxpower', $info['max_available_power']);
         
         return;
      }
      
   }

   // Utility
   public function utctolocal($date)
   {
      $time = new DateTime($date, new DateTimeZone('UTC'));
      $tm_tz_from = $timeZone;
      $tm_tz_to = new DateTimeZone(date_default_timezone_get());
      $dt = new DateTime($date, new DateTimeZone($tm_tz_from));
      $dt->setTimeZone(new DateTimeZone($tm_tz_to));
      $utc_time_from =$dt->format("d-m-Y h:i:s");

      return $utc_time_from;
   }
   
   /*     * **********************Getteur Setteur*************************** */
}


