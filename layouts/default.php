<?php
/*
 * @package     RadicalMart Fields Standard Plugin
 * @subpackage  plg_radicalmart_fields_standard
 * @version     1.2.5
 * @author      RadicalMart Team - radicalmart.ru
 * @copyright   Copyright (c) 2024 RadicalMart. All rights reserved.
 * @license     GNU/GPL license: https://www.gnu.org/copyleft/gpl.html
 * @link        https://radicalmart.ru/
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;


extract($displayData);

/**
 * Layout variables
 * -----------------
 *
 * @var  object $field Field data object.
 * @var  array $values Field values.
 *
 */
?>
<ul class="list-unstyled">
<?php
foreach($field->options as $option) : ?>
    <?php if(in_array($option['value'], $values)) : ?>
          <li>
            <?php
                $src = $option['image'];
                foreach ($values as $value):
                    $img_attribs = [
                       'class' => 'img w-100 h-auto wtsetelements wtsetelements-image',
                       ];
                endforeach;
                echo HTMLHelper::image($src, htmlspecialchars($option['text']), $img_attribs);
                ?>
                <span class="wtsetelements wtsetelements-header"><?php echo $option['text']; ?></span>
                <span class="wtsetelements wtsetelements-desc"><?php echo $option['desctext']; ?></span>
          </li>
    <?php endif ?>
<?php endforeach ?>