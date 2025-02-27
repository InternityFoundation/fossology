<?php
/*
Copyright (C) 2022, Siemens AG
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
*/

namespace Fossology\Lib\Util;

class PurlOperations
{
  /**
   * Implemented following specs at https://github.com/package-url/purl-spec/blob/a748c36ad415c8aeffe2b8a4a5d8a50d16d6d85f/PURL-SPECIFICATION.rst#how-to-parse-a-purl-string-in-its-components
   * @todo Replace with https://packagist.org/packages/package-url/packageurl-php
   * @param string $input pURL as a string
   * @return array Each part of pURL as key and corresponding values
   */
  public static function fromString($input)
  {
    $scheme = null;
    $type = null;
    $namespace = null;
    $name = null;
    $version = null;
    $qualifiers = null;
    $subpath = null;
    $split = explode("#", $input);
    if (count($split) > 1) {
      $subpath = trim($split[1], " /");
      $subpaths = explode("/", $subpath);
      $subpath = [];
      foreach ($subpaths as $sp) {
        if ($sp != "." || $sp != "..") {
          $subpath[] = urldecode($sp);
        }
      }
      $subpath = implode("/", $subpath);
    }
    $split = explode("?", $split[0]);
    if (count($split) > 1) {
      $qualifiers = [];
      $parts = explode("&", $split[1]);
      foreach ($parts as $part) {
        $pair = explode("=", $part);
        if (empty($pair[1])) {
          continue;
        }
        $qualifiers[$pair[0]] = urldecode($pair[1]);
      }
    }
    $split = explode(":", $split[0]);
    $scheme = strtolower($split[0]);
    $split = explode("/", trim($split[1], " /"));
    $type = strtolower($split[0]);
    $split = explode("@", implode("/", array_slice($split, 1)));
    if (count($split) > 1) {
      $version = urldecode($split[1]);
    }
    $split = explode("/", $split[0]);
    $splitClone = array_values($split);
    $name = end($splitClone);
    $name = urldecode($name);
    $namespace = [];
    for ($i = 0; $i < count($split) - 1; $i++) {
      $namespace[] = urldecode($split[$i]);
    }
    $namespace = implode("/", $namespace);
    if (empty($namespace)) {
      $namespace = null;
    }
    return [
      "scheme" => $scheme,
      "type" => $type,
      "namespace" => $namespace,
      "name" => $name,
      "version" => $version,
      "qualifiers" => $qualifiers,
      "subpath" => $subpath
    ];
  }
}
