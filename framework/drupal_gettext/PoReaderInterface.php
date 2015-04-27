<?php

/**
 * @file
 * Definition of Drupal\Component\Gettext\PoReaderInterface.
 */

namespace framework\gettext;

use framework\gettext\PoMetadataInterface;

/**
 * Shared interface definition for all Gettext PO Readers.
 */
interface PoReaderInterface extends PoMetadataInterface {

  /**
   * Reads and returns a PoItem (source/translation pair).
   *
   * @return \Drupal\Component\Gettext\PoItem
   *   Wrapper for item data instance.
   */
  public function readItem();

}
