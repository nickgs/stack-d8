<?php

/**
 * @file
 * The PHP page that serves all page requests on a Drupal installation.
 *
 * All Drupal code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt files in the "core" directory.
 */

use Drupal\Core\DrupalKernel;
use Symfony\Component\HttpFoundation\Request;

$autoloader = require_once 'autoload.php';

$kernel = new DrupalKernel('prod', $autoloader);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);

//make sure we have the database functions we need.
include_once __DIR__ . '/core/includes/database.inc';
//redirect to installer if we aren't yet installed and we are on stackstarter
if (isset($_SERVER['STACKSTARTER_ENV']) && function_exists('db_table_exists') && !db_table_exists('sessions') && $_SERVER['SCRIPT_NAME'] != '/core/install.php') {
  include_once __DIR__ . '/core/includes/install.inc';
  install_goto('core/install.php');
}

$response->send();

$kernel->terminate($request, $response);
