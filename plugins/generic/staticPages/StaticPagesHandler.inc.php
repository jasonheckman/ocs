<?php

/**
 * @file StaticPagesHandler.inc.php
 *
 * Copyright (c) 2000-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.staticPages
 * @class StaticPagesHandler
 *
 * Find the content and display the appropriate page
 *
 */

import('classes.handler.Handler');

class StaticPagesHandler extends Handler {
	function index( $args ) {
		Request::redirect(null, null, null, 'view', Request::getRequestedOp());
	}

	function view ($args) {
		if (count($args) > 0 ) {
			AppLocale::requireComponents(LOCALE_COMPONENT_PKP_COMMON, LOCALE_COMPONENT_APPLICATION_COMMON, LOCALE_COMPONENT_PKP_USER);
			$conference =& Request::getConference();
			$conferenceId = $conference->getId();
			$path = $args[0];

			$staticPagesPlugin =& PluginRegistry::getPlugin('generic', STATIC_PAGES_PLUGIN_NAME);
			$templateMgr =& TemplateManager::getManager();

			$staticPagesDao =& DAORegistry::getDAO('StaticPagesDAO');
			$staticPage = $staticPagesDao->getStaticPageByPath($conferenceId, $path);

			if ( !$staticPage ) {
				Request::redirect(null, null, 'index');
			}

			// and assign the template vars needed
			$templateMgr->assign('title', $staticPage->getStaticPageTitle());
			$templateMgr->assign('content',  $staticPage->getStaticPageContent());
			$templateMgr->display($staticPagesPlugin->getTemplatePath().'content.tpl');
		}
	}
}

?>
