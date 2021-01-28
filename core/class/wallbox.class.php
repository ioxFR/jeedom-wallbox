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
   
   /*
   * Fonction exécutée automatiquement toutes les minutes par Jeedom
   public static function cron() {
   }
   */
   
   /*
   * Fonction exécutée automatiquement toutes les 5 minutes par Jeedom
   public static function cron5() {
   }
   */
   
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
      $info = $this->getCmd(null, 'charger');
      if (!is_object($info)) {
         $info = new wallboxCmd();
         $info->setName(__('Informations', __FILE__));
      }
      $info->setLogicalId('charger');
      $info->setEqLogic_id($this->getId());
      $info->setType('info');
      $info->setSubType('string');
      $info->save();
      
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
      //$username = $this->getConfiguration("username");
      //$password = $this->getConfiguration("password");
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
   
   // Function to get charger status
   public function getChargerStatus($chargerId){
      $baseurl = "https://api.wall-box.com/";
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
         //$objectresult = json_decode($result,true);
        log::add('wallbox', 'debug', 'charger result '. $result);
         return $result;
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
        $chargerid = $this->getConfiguration("chargerid");
            $info = $this->getEqLogic()->getChargerStatus($chargerid);
       $eqlogic->checkAndUpdateCmd('charger', $info);
            return;
        }

      }
      
      /*     * **********************Getteur Setteur*************************** */
   }
   
   
   