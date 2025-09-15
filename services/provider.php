<?php
/**
 * @package       Fields - WT RadicalMart Fields Set Elements
 * @version       1.0.0
 * @Author        Sergey Tolkachyov and Sergey Sergevnin - https://web-tolk.ru
 * @copyright     Copyright (c) 2025 Sergey Tolkachyov and Sergey Sergevnin. All rights reserved.
 * @license       GNU/GPL3 http://www.gnu.org/licenses/gpl-3.0.html
 * @since         1.0.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\RadicalMartFields\Wtsetelements\Extension\Wtsetelements;

return new class implements ServiceProviderInterface {

	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function register(Container $container)
	{
		$container->set(
			PluginInterface::class,
			function (Container $container) {
				$plugin  = PluginHelper::getPlugin('radicalmart_fields', 'related');
				$subject = $container->get(DispatcherInterface::class);

				$plugin = new Wtsetelements($subject, (array) $plugin);
				$plugin->setApplication(Factory::getApplication());

				return $plugin;
			}
		);
	}
};
