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
      $maxpower->setType('action');
      $maxpower->setSubType('numeric');
		$maxpower->setUnite('Amp');
      $maxpower->setConfiguration('minValue' , '0');
      $maxpower->setConfiguration('maxValue' , '32');
      $maxpower->save();

      // charging speed
      /*$speed = $this->getCmd(null, 'speed');
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
      $speed->save();*/

      // state of charge
      /*$chargestatus = $this->getCmd(null, 'chargestatus');
      if (!is_object($chargestatus)) {
         $chargestatus = new wallboxCmd();
         $chargestatus->setName(__('Statut de la charge', __FILE__));
      }
      $chargestatus->setLogicalId('chargestatus');
      $chargestatus->setEqLogic_id($this->getId());
      $chargestatus->setType('info');
      $chargestatus->setSubType('string');
      $chargestatus->save();*/

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

      // Charging Time
      $chargingtime = $this->getCmd(null, 'chargingtime');
      if (!is_object($chargingtime)) {
         $chargingtime = new wallboxCmd();
         $chargingtime->setName(__('Temps de charge', __FILE__));
      }
      $chargingtime->setLogicalId('chargingtime');
      $chargingtime->setEqLogic_id($this->getId());
      $chargingtime->setType('info');
      $chargingtime->setSubType('string');
      $chargingtime->save();

      // Energie consommée
      $energyconsumed = $this->getCmd(null, 'energyconsumed');
      if (!is_object($energyconsumed)) {
         $energyconsumed = new wallboxCmd();
         $energyconsumed->setName(__('Energie consommée', __FILE__));
      }
      $energyconsumed->setLogicalId('energyconsumed');
      $energyconsumed->setEqLogic_id($this->getId());
      $energyconsumed->setType('info');
      $energyconsumed->setSubType('numeric');
		$energyconsumed->setUnite('Kwh');
      $energyconsumed->setConfiguration('minValue' , '0');
      $energyconsumed->setConfiguration('maxValue' , '60');
      $energyconsumed->save();
      
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

      // Charge command action
      $chargecontrol = $this->getCmd(null, 'chargecontrol');
      if (!is_object($chargecontrol)) {
         $chargecontrol = new wallboxCmd();
         $chargecontrol->setName(__('Contrôle de la charge', __FILE__));
      }
      $chargecontrol->setEqLogic_id($this->getId());
      $chargecontrol->setLogicalId('chargecontrol');
      $chargecontrol->setType('action');
      $chargecontrol->setSubType('other');
      $chargecontrol->save();

      // Lock command action
      $lockcontrol = $this->getCmd(null, 'lockcontrol');
      if (!is_object($lockcontrol)) {
         $lockcontrol = new wallboxCmd();
         $lockcontrol->setName(__('Verouillage du chargeur', __FILE__));
      }
      $lockcontrol->setEqLogic_id($this->getId());
      $lockcontrol->setLogicalId('lockcontrol');
      $lockcontrol->setType('action');
      $lockcontrol->setSubType('other');
      $lockcontrol->save();

      // Amp command action
     /* $maxamp = $this->getCmd(null, 'maxamp');
      if (!is_object($maxamp)) {
         $maxamp = new wallboxCmd();
         $maxamp->setName(__('Amperage maximum', __FILE__));
      }
      $maxamp->setEqLogic_id($this->getId());
      $maxamp->setLogicalId('maxamp');
      $maxamp->setType('action');
      $maxamp->setSubType('numeric');
		$maxamp->setUnite('Amp');
      $maxamp->setConfiguration('minValue' , '1');
      $maxamp->setConfiguration('maxValue' , '32');
      $maxamp->save();*/

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
         log::add('wallbox', 'information', 'Authentication Success');
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

   // function to pause or resume charging
   public function defineChargingState($resume)
   {
      $baseurl = "https://api.wall-box.com/v3/";
      $chargerId = $this->getConfiguration("chargerid");
      log::add('wallbox', 'debug', 'Define charging state '. $chargerId);
      $jwt = $this->getWallboxToken();
      
      if($jwt != null && $chargerId != null){

         $data = '{"action":1}'; //resume id
         if(!$resume)
         {
            $data = '{"action":2}'; // pause id
         }

         $opts = array('http' =>
         array(
            'method'  => 'POST',
            'header'  => array('Authorization: Bearer '.$jwt,'Accept: application/json','Content-Type:application/json;charset=UTF-8'),
            'content' => http_build_query($data)
            )
         );
         
         $context  = stream_context_create($opts);
         
         $result = file_get_contents($baseurl.'chargers/'.$chargerId.'/remote-action', false, $context);
         log::add('wallbox', 'debug', 'defineChargingState '. $result);

         $objectresult = json_decode($result,true);
         return $objectresult;
      }
      else{
         throw new Exception("User is not authenticated");
      }
   }

   // function to lock/unlock a charger
   public function defineLockState($locked)
   {
      $baseurl = "https://api.wall-box.com/v2/";
      $chargerId = $this->getConfiguration("chargerid");
      log::add('wallbox', 'debug', 'Define lock state '. $chargerId);
      $jwt = $this->getWallboxToken();
      
      if($jwt != null && $chargerId != null){

         $data = '{"locked":1}'; //lock id
         if($locked == 1)
         {
            $data = '{"locked":0}'; // unlock id
         }
         log::add('wallbox', 'debug', 'defineLockState  data'. $data);

         
         $opts = array('http' =>
         array(
            'method'  => 'PUT',
            'header'  => array('Authorization: Bearer '.$jwt,'Accept: application/json','Content-Type:application/json;charset=UTF-8'),
            'content' => http_build_query($data)
            )
         );
         
         $context  = stream_context_create($opts);
         log::add('wallbox', 'debug', 'defineLockState '. $context);
         $result = file_get_contents($baseurl.'charger/'.$chargerId, false, $context);
         log::add('wallbox', 'debug', 'defineLockState '. $result);


         $objectresult = json_decode($result,true);
         return $objectresult;
      }
      else{
         throw new Exception("User is not authenticated");
      }
   }

   // function to define max amp of a charge
   public function defineMaxAmp($ampvalue)
   {
      $baseurl = "https://api.wall-box.com/v2/";
      $chargerId = $this->getConfiguration("chargerid");
      log::add('wallbox', 'debug', 'Define max amp state '. $chargerId);
      $jwt = $this->getWallboxToken();
      
      if($jwt != null && $chargerId != null){

         $data = '{ "maxChargingCurrent":'.$ampvalue.'}'; //resume id

         $opts = array('http' =>
         array(
            'method'  => 'PUT',
            'header'  => array(
               'Authorization: Bearer '.$jwt,
               'Accept: application/json',
               'Content-Type:application/json;charset=UTF-8'
            ),
            'content' => http_build_query($data)
            )
         );
         
         $context  = stream_context_create($opts);
         
         $result = file_get_contents($baseurl.'charger/'.$chargerId, false, $context);
         log::add('wallbox', 'debug', 'defineMaxAmp '. $result);
         $objectresult = json_decode($result,true);
         return $objectresult;
      }
      else{
         throw new Exception("User is not authenticated");
      }
   }

      // Utility
      public function utctolocal($date)
      {
         log::add('wallbox', 'debug', 'starting date conversion from UTC to local');
         $localtimezone = date_default_timezone_get();
         log::add('wallbox', 'debug', 'local timezone is defined on '.$localtimezone);
         $tm_tz_to = new DateTimeZone($localtimezone);
         $dt = new DateTime($date, new DateTimeZone('UTC'));
         $dt->setTimeZone(new DateTimeZone($tm_tz_to->getName()));
         $utc_time_from =$dt->format("d-m-Y H:i:s");
         log::add('wallbox', 'debug', 'Date converted '.$utc_time_from);
   
         return $utc_time_from;
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
         log::add('wallbox', 'info', 'execute refresh');
         $info = $this->getEqLogic()->getChargerStatus();
         $eqlogic->checkAndUpdateCmd('name', $info['name']);
         $eqlogic->checkAndUpdateCmd('lastsync', $this->getEqLogic()->utctolocal($info['last_sync']));

         $statusid=$info['status_id'];

         $eqlogic->checkAndUpdateCmd('status_id', $info['status_id']);
         $eqlogic->checkAndUpdateCmd('status', $this->statustotext($info['status_id']));
         
         //$eqlogic->checkAndUpdateCmd('speed', $info['charging_speed']);
         $eqlogic->checkAndUpdateCmd('maxpower', $info['max_available_power']);

         if($statusid == 194){
            $eqlogic->checkAndUpdateCmd('power', $info['charging_power']);
            $eqlogic->checkAndUpdateCmd('chargingtime', $this->sectohhmmss($info['charging_time']));// in second
            $eqlogic->checkAndUpdateCmd('energyconsumed',$info['added_energy']); // kwh
            $obj = $eqlogic->getCmd(null, 'energyconsumed');
            $obj->setIsVisible(1);
            $obj->save();
            $obj = $eqlogic->getCmd(null, 'chargingtime');
            $obj->setIsVisible(1);
            $obj->save();
            $obj = $eqlogic->getCmd(null, 'power');
            $obj->setIsVisible(1);
            $obj->save();
            /*$obj = $eqlogic->getCmd(null, 'chargecontrol');
            $obj->setIsVisible(1);
            $obj->save();*/
         }
         else
         {
            $obj = $eqlogic->getCmd(null, 'energyconsumed');
            $obj->setIsVisible(0);
            $obj->save();
            $obj = $eqlogic->getCmd(null, 'chargingtime');
            $obj->setIsVisible(0);
            $obj->save();
            $obj = $eqlogic->getCmd(null, 'power');
            $obj->setIsVisible(0);
            $obj->save();
            /*$obj = $eqlogic->getCmd(null, 'chargecontrol');
            $obj->setIsVisible(0);
            $obj->save();*/
         }


         return;
      }
      else if ($this->getLogicalId() == 'chargecontrol')
      {
         log::add('wallbox', 'info', 'execute chargecontrol');
         $info = $this->getEqLogic()->getChargerStatus();
         $statusid=$info['status_id'];

         if($statusid == 194)
         {
            // charging, we switch to pause
            $this->getEqLogic()->defineChargingState(false);
         }
         else if($statusid == 182)
         {
            // in pause, we resume charge
            $this->getEqLogic()->defineChargingState(true);
         }
      }
      else if($this->getLogicalId() == 'lockcontrol')
      {
         log::add('wallbox', 'info', 'execute lockcontrol');
         $info = $this->getEqLogic()->getChargerStatus();
         $statusid=$info['config_data']['locked'];

         log::add('wallbox', 'debug', 'statusid is '.$statusid);
            $this->getEqLogic()->defineLockState($statusid);

      }
      else if($this->getLogicalId() == 'maxpower')
      {
         log::add('wallbox', 'info', 'execute lockcontrol');
         $obj = $eqlogic->getCmd(null, 'maxpower');

         // charging, we switch to pause
         $this->getEqLogic()->defineMaxAmp($obj->getCmdValue());

      }
   }

   public function statustotext($statusid)
   {
      switch($statusid)
      {
         case 209:
            return 'Verrouillée';
            break;
         case 161:
            return 'En attente';
            break;
         case 194:
            return 'En charge';
            break;
         case 182:
            return 'En pause';
            break;
      }
   }

   public function sectohhmmss($seconds)
   {
      $seconds = round($seconds);
      return sprintf('%02d:%02d:%02d', ($seconds/ 3600),($seconds/ 60 % 60), $seconds% 60);
   }

   /*     * **********************Getteur Setteur*************************** */
}


