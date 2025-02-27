<?php
/***************************************************************
 Copyright (C) 2018 Siemens AG
 Author: Gaurav Mishra <mishra.gaurav@siemens.com>

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License
 version 2 as published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License along
 with this program; if not, write to the Free Software Foundation, Inc.,
 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 ***************************************************************/
/**
 * @dir
 * @brief Controllers for REST requests
 * @file
 * @brief Base controller for REST calls
 */

namespace Fossology\UI\Api\Controllers;

use Psr\Container\ContainerInterface;
use Fossology\UI\Api\Helper\RestHelper;
use Fossology\UI\Api\Helper\DbHelper;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @class RestController
 * @brief Base controller for REST calls
 */
class RestController
{
  /**
   * @var ContainerInterface $container
   * Slim container
   */
  protected $container;

  /**
   * @var RestHelper $restHelper
   * Rest helper object in use
   */
  protected $restHelper;

  /**
   * @var DbHelper $dbHelper
   * DB helper object in use
   */
  protected $dbHelper;

  /**
   * Constructor for base controller
   * @param ContainerInterface $container
   */
  public function __construct($container)
  {
    $this->container = $container;
    $this->restHelper = $this->container->get('helper.restHelper');
    $this->dbHelper = $this->restHelper->getDbHelper();
  }

  /**
   * @brief Parse request body as JSON and return associative PHP array.
   *
   * If request is of type application/json, read the body content and parse
   * with json_decode(). Otherwise, use slim's getParsedBody().
   *
   * @param ServerRequestInterface $request Request to parse
   * @return array|null Parsed JSON, or null on error
   */
  protected function getParsedBody(ServerRequestInterface $request)
  {
    if (strcasecmp($request->getHeaderLine('Content-Type'),
        "application/json") === 0) {
      $content = $request->getBody()->getContents();
      return json_decode($content, true);
    } else {
      // application/x-www-form-urlencoded or multipart/form-data
      return $request->getParsedBody();
    }
  }
}
