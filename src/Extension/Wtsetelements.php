<?php
/*
 * @package       Fields - WT RadicalMart Fields Set Elements
 * @version       1.0.0
 * @Author        Sergey Tolkachyov and Sergey Sergevnin - https://web-tolk.ru
 * @copyright     Copyright (c) 2025 Sergey Tolkachyov and Sergey Sergevnin. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

namespace Joomla\Plugin\RadicalMartFields\Wtsetelements\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Component\RadicalMart\Administrator\Helper\PluginsHelper;
use Joomla\Database\DatabaseDriver;
use Joomla\Event\SubscriberInterface;
use Joomla\Registry\Registry;
use SimpleXMLElement;

use function defined;

class Wtsetelements extends CMSPlugin implements SubscriberInterface
{

	/**
	 * Load the language file on instantiation.
	 *
	 * @var    bool
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $autoloadLanguage = true;

	/**
	 * Loads the application object.
	 *
	 * @var  CMSApplication
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $app = null;

	/**
	 * Loads the database object.
	 *
	 * @var  DatabaseDriver
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $db = null;

	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function getSubscribedEvents(): array
	{
		return [
			'onRadicalMartGetFieldType'          => 'onRadicalMartGetFieldType',
			'onRadicalMartGetFieldForm'          => 'onRadicalMartGetFieldForm',
			'onRadicalMartGetProductFieldXml'    => 'onRadicalMartGetProductFieldXml',
			'onRadicalMartGetProductsFieldValue' => 'onRadicalMartGetProductsFieldValue',
			'onRadicalMartGetProductFieldValue'  => 'onRadicalMartGetProductFieldValue',
			'onRadicalMartAfterGetFieldForm'     => 'onRadicalMartAfterGetFieldForm',
		];
	}

	/**
	 * Method to add field type to admin list.
	 *
	 * @param   string  $context  Context selector string.
	 * @param   object  $item     List item object.
	 *
	 * @return string|false Field type constant on success, False on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartGetFieldType($context = null, $item = null)
	{
		return 'PLG_WTSETELEMENTS_FIELD_TYPE';
		/**!!! Название типа поля на странице полей*/
	}

	/**
	 * ПРОКСИ-метод грузит форму с параметрами для поля из XML-файла.
	 * Параметры отображаются ПРИ СОЗДАНИИ/РЕДАКТИРОВАНИИ поля.
	 *
	 * @param   string|null    $context  Context selector string.
	 * @param   Form|null      $form     Form object.
	 * @param   Registry|null  $tmpData  Temporary form data.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartGetFieldForm(string $context = null, Form $form = null, Registry $tmpData = null)
	{
		if ($context !== 'com_radicalmart.field' || $tmpData->get('plugin') !== 'wtsetelements') {
			return;
		}

		$area = $tmpData->get('area');

		$methods = [
			'products' => 'loadFieldProductsForm', // область -> вызываемый метод в данном плагине
		];

		if (isset($methods[$area])) {
			$method = $methods[$area];
			if (method_exists($this, $method)) {
				$this->$method($form, $tmpData);
			}
		}
	}

	/**
	 * Method to load products field type form.
	 *
	 * @param   Form|null      $form     Form object.
	 * @param   Registry|null  $tmpData  Temporary form data.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function loadFieldProductsForm(Form $form = null, Registry $tmpData = null)
	{
		// Load global
		Form::addFormPath(JPATH_PLUGINS . '/radicalmart_fields/wtsetelements/forms');
		$form->loadFile('config');

		/**
		 * Программно присваиваем полям АТРИБУТЫ и их значения.
		 */

		$form->setFieldAttribute('display_variability', 'readonly', 'true', 'params');
		$form->removeField('display_variability_as', 'params');

		$form->setFieldAttribute('display_filter', 'readonly', 'true', 'params');
		$form->removeField('display_filter_as', 'params');
	}

	/**
	 * Method to change field form.
	 *
	 * @param   string|null    $context  Context selector string.
	 * @param   Form|null      $form     Form object.
	 * @param   Registry|null  $tmpData  Temporary form data.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartAfterGetFieldForm(string $context = null, Form $form = null, Registry $tmpData = null)
	{
		if ($context !== 'com_radicalmart.field' || $tmpData->get('plugin') !== 'wtsetelements') {
			return;
		}

		$area    = $tmpData->get('area');

		if (isset($methods[$area])) {
			$method = $methods[$area];
			if (method_exists($this, $method)) {
				$this->$method($form, $tmpData);
			}
		}
	}

	/**
	 * СОЗДАЁМ ПОЛЕ ПРОГРАММНО ИЛИ ГРУЗИМ ИЗ XML
	 * И ДОБАВЛЯЕМ ПОЛЕ В ФОРМУ ТОВАРА. ОТДАЁМ XML.
	 *
	 * @param   string|null    $context  Context selector string.
	 * @param   object|null    $field    Field data object.
	 * @param   Registry|null  $tmpData  Temporary form data.
	 *
	 * @return false|SimpleXMLElement SimpleXMLElement on success, False on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartGetProductFieldXml(
		string $context = null,
		object $field = null,
		Registry $tmpData = null
	) {
		if ($context !== 'com_radicalmart.product' || $field->plugin !== 'wtsetelements') {
			return false;
		}

		$fieldsXml = new SimpleXMLElement('<field />');
		$fieldsXml->addAttribute('name', $field->alias);
		$fieldsXml->addAttribute('label', $field->title);
		$fieldsXml->addAttribute('type', 'list');

		$fieldsXml->addAttribute('parentclass', 'stack');
		$multiple = $field->params->get('multiple', '0') == '1' ? 'true' : 'false';
		$fieldsXml->addAttribute('multiple', $multiple);

		if ($field->params->get('select_view', 'standard') == 'fancy_select') {
			$fieldsXml->addAttribute('layout', 'joomla.form.field.list-fancy-select');
		}

		if (!empty($field->options)) {
			foreach ($field->options as $option) {
				$selectOption = $fieldsXml->addChild('option', $option['text']);
				$selectOption->addAttribute('value', $option['value']);
			}
		}

		return $fieldsXml;
	}

	/**
	 * РЕНДЕРИМ ЗНАЧЕНИЕ ПОЛЯ ДЛЯ КАТЕГОРИИ ТОВАРОВ
	 *
	 * Method to add field value to products list.
	 *
	 * @param   string|null  $context  Context selector string.
	 * @param   object|null  $field    Field data object.
	 * @param   mixed        $value    Field value.
	 *
	 * @return  string|false  Field html value.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartGetProductsFieldValue(string $context = null, object $field = null, $value = null)
	{
		if (
			!in_array($context, ['com_radicalmart.category', 'com_radicalmart.products']) ||
			$field->plugin !== 'wtsetelements' ||
			(int) $field->params->get('display_products', 0) === 0
		) {
			return false;
		}

		$layout = $field->params->get('display_products_as', 'default');
		$value  = $this->getFieldValue($field, $value, $layout);

		return $value;
	}


	/**
	 * РЕНДЕРИМ ЗНАЧЕНИЕ ПОЛЯ ДЛЯ СТРАНИЦЫ ТОВАРА
	 *
	 * @param   string        $context  Context selector string.
	 * @param   object        $field    Field data object.
	 * @param   array|string  $value    Field value.
	 *
	 * @return  string  Field html value.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public function onRadicalMartGetProductFieldValue($context = null, $field = null, $value = null)
	{
		if ($context !== 'com_radicalmart.product' || $field->plugin !== 'wtsetelements' || (int) $field->params->get(
			'display_product',
			1
		) === 0) {
			return false;
		}

		$layout = $field->params->get('display_product_as', 'default');
		$value  = $this->getFieldValue($field, $value, $layout);

		return $value;
	}
	/**
	 * ПОЛУЧАЕМ РЕНДЕР ПОЛЯ ДЛЯ СПИСКА ТОВАРОВ ИЛИ СТРАНИЦЫ ТОВАРА
	 *
	 * @param   object|null  $field   Field data object.
	 * @param   mixed        $value   Field value.
	 * @param   string       $layout  Layout name.
	 *
	 * @return  string|false  Field string values on success, False on failure.
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected function getFieldValue(object $field = null, $value = null, string $layout = 'default')
	{
		if (empty($field) || empty($value)) {
			return false;
		}

		$html = LayoutHelper::render(
			'plugins.radicalmart_fields.wtsetelements.' . $layout,
			['field' => $field, 'values' => $value]
		);

		return $html;
	}
}
