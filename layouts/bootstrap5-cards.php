<?php
/**
 * @package       Fields - WT RadicalMart Fields Set Elements
 * @version       1.0.0
 * @Author        Sergey Tolkachyov, https://web-tolk.ru
 * @copyright     Copyright (C) 2024 Sergey Tolkachyov
 * @license       GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

use Joomla\CMS\HTML\HTMLHelper;

defined('_JEXEC') or die;


extract($displayData);

/**
 * Layout variables
 * -----------------
 *
 * @var  object $field  Field data object.
 * @var  array  $values Field values.
 *
 */

?>
<div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xxl-5 row-gap-3 row-gap-lg-4">
    <?php
    if (is_string($values)) {
        $values = [$values];
    }
    foreach ($field->options as $option) : ?>
        <?php if (in_array($option['value'], $values)) : ?>
            <div class="col">
                <div class="card h-100 border-0">
                    <div class="card-header p-0 bg-transparent mb-3">
                        <div class="row align-items-center">
                            <?php if(!empty($src = $option['image'])):?>
                                <div class="icon-header col-3 flex-fill">
                                    <?php
                                    $src = $option['image'];
                                    $img_attribs = [
                                            'class' => 'img w-100 h-auto',
                                            'title' => htmlspecialchars($option['text']),
                                        ];
                                    echo HTMLHelper::image($src, htmlspecialchars($option['text']), $img_attribs);
                                    ?>
                                </div>
                            <?php endif; ?>
                            <?php if(!empty($option['text'])): ?>
                                <div class="title-header col-9 flex-grow-1">
                                    <h5 class="fs-6 mb-0 fw-bolder"><?php
                                        echo $option['text']; ?></h5>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if(!empty($option['desctext'])) :?>
                        <div class="card-body p-0 h-100">
                            <p class="mb-0"><?php echo $option['desctext']; ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif ?>
    <?php endforeach; ?>
</div>
