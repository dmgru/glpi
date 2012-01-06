<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '..');
include (GLPI_ROOT . "/inc/includes.php");

Session::checkRight("phone", "r");

if (empty($_GET["id"])) {
   $_GET["id"] = "";
}
if (!isset($_GET["sort"])) {
   $_GET["sort"] = "";
}
if (!isset($_GET["order"])) {
   $_GET["order"] = "";
}
if (!isset($_GET["withtemplate"])) {
   $_GET["withtemplate"] = "";
}

$phone = new Phone();

if (isset($_POST["add"])) {
   $phone->check(-1,'w',$_POST);

   $newID=$phone->add($_POST);
   Event::log($newID, "phones", 4, "inventory",
              sprintf(__('%1$s adds the item %2%s'), $_SESSION["glpiname"], $_POST["name"]));
   Html::back();

} else if (isset($_POST["delete"])) {
   $phone->check($_POST["id"],'d');
   $phone->delete($_POST);

   Event::log($_POST["id"], "phones", 4, "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s deletes the item'), $_SESSION["glpiname"]));
   $phone->redirectToList();

} else if (isset($_POST["restore"])) {
   $phone->check($_POST["id"],'d');

   $phone->restore($_POST);
   Event::log($_POST["id"], "phones", 4, "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s restores the item'), $_SESSION["glpiname"]));
   $phone->redirectToList();

} else if (isset($_REQUEST["purge"])) {
   $phone->check($_REQUEST["id"],'d');

   $phone->delete($_REQUEST,1);
   Event::log($_REQUEST["id"], "phones", 4, "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges the item'), $_SESSION["glpiname"]));
   $phone->redirectToList();

} else if (isset($_POST["update"])) {
   $phone->check($_POST["id"],'w');

   $phone->update($_POST);
   Event::log($_POST["id"], "phones", 4, "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates the item'), $_SESSION["glpiname"]));
   Html::back();

} else if (isset($_GET["unglobalize"])) {
   $phone->check($_GET["id"],'w');

   Computer_Item::unglobalizeItem($phone);
   Event::log($_GET["id"], "phones", 4, "inventory",
               //TRANS: %s is the user login
               sprintf(__('%s sets unitary management'), $_SESSION["glpiname"]));

   Html::redirect($CFG_GLPI["root_doc"]."/front/phone.form.php?id=".$_GET["id"]);

} else {
   Html::header(Phone::getTypeName(2), $_SERVER['PHP_SELF'], 'inventory', 'phone');
   $phone->showForm($_GET["id"], array('withtemplate' => $_GET["withtemplate"]));
   Html::footer();
}
?>