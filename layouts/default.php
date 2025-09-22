<?php
/**
 * @package    Fields - WT RadicalMart Fields Set Elements
 * @version       1.0.1
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

if (is_string($values)) {
    $values = [$values];
}

?>
<ul class="list-unstyled">
    <?php foreach ($field->options as $option) : ?>
        <?php if (in_array($option['value'], $values)) : ?>
            <li>
                <?php
                if(!empty($option['image'])) {
                    $src = $option['image'];
                    $img_attribs = [
                        'class' => 'wtsetelements wtsetelements-image',
                        'title' => htmlspecialchars($option['text']),
                    ];
                    echo HTMLHelper::image($src, htmlspecialchars($option['text']), $img_attribs);
                }
                ?>
                <?php if(!empty($option['text'])) :?>
                    <span class="wtsetelements wtsetelements-header"><?php echo $option['text']; ?></span>
                <?php endif; ?>

                <?php if(!empty($option['desctext'])) :?>
                    <span class="wtsetelements wtsetelements-desc"><?php echo $option['desctext']; ?></span>
                <?php endif; ?>
            </li>
        <?php endif ?>
    <?php endforeach ?>
</ul>
