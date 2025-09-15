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
<div class="row row-cols-2 row-cols-md-4 row-gap-4">
<?php
foreach($field->options as $option) : ?>
    <?php if(in_array($option['value'], $values)) : ?>
        <div class="col">
            <div class="card h-100 border-0">
                <div class="card-header p-0 bg-transparent mb-3">
                    <div class="row align-items-center">
                        <div class="icon-header col col-3 flex-fill">
                            <?php
                                $src = $option['image'];
                                foreach ($values as $value):
                                    $img_attribs = [
                                        'class' => 'img w-100 h-auto',
                                    ];
                                endforeach;
                                echo HTMLHelper::image($src, htmlspecialchars($option['text']), $img_attribs);
                            ?>
                        </div>
                        <div class="title-header col col-9 flex-grow-1">
                            <h5 class="fs-6 mb-0 fw-bolder"><?php echo $option['text']; ?></h5>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 h-100">
                    <p class="mb-0" style="font-size: 14px"><?php echo $option['desctext']; ?></p>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endforeach; ?>

