<?php
$conf['db']['type'] = 'mysql_SGL';
$conf['db']['host'] = 'localhost';
$conf['db']['protocol'] = 'unix';
$conf['db']['socket'] = '';
$conf['db']['port'] = '3306';
$conf['db']['user'] = 'root';
$conf['db']['pass'] = '';
$conf['db']['name'] = 'seagull_cms_trunk';
$conf['db']['postConnect'] = 'set names utf8';
$conf['db']['mysqlDefaultStorageEngine'] = false;
$conf['db']['charset'] = '';
$conf['db']['collation'] = 'utf8_general_ci';
$conf['db']['sepTableForEachSequence'] = false;
$conf['db']['prefix'] = '';
$conf['site']['outputUrlHandler'] = 'SGL_UrlParser_SefStrategy';
$conf['site']['inputUrlHandlers'] = 'Horde_Routes';
$conf['site']['name'] = 'SimpleCms';
$conf['site']['showLogo'] = 'logo.png';
$conf['site']['description'] = 'SimpleCms test site';
$conf['site']['keywords'] = 'simplecms, test, site';
$conf['site']['compression'] = '';
$conf['site']['outputBuffering'] = '';
$conf['site']['banIpEnabled'] = '';
$conf['site']['denyList'] = '';
$conf['site']['allowList'] = '';
$conf['site']['tidyhtml'] = '';
$conf['site']['blocksEnabled'] = true;
$conf['site']['safeDelete'] = '';
$conf['site']['frontScriptName'] = 'index.php';
$conf['site']['defaultModule'] = 'default';
$conf['site']['defaultManager'] = 'default';
$conf['site']['defaultArticleViewType'] = '1';
$conf['site']['defaultParams'] = '';
$conf['site']['templateEngine'] = 'flexy';
$conf['site']['wysiwygEditor'] = 'fckeditor';
$conf['site']['extendedLocale'] = '';
$conf['site']['localeCategory'] = 'LC_ALL';
$conf['site']['adminGuiTheme'] = 'default_admin';
$conf['site']['defaultTheme'] = 'simplecms';
$conf['site']['masterTemplate'] = 'master.html';
$conf['site']['masterLayout'] = 'layout-navtop-3col.css';
$conf['site']['filterChain'] = '
    SGL_Task_Init,
    SGL_Task_SetupORM,
    SGL_Task_StripMagicQuotes,
    SGL_Task_DiscoverClientOs,
    SGL_Task_ResolveManager,
    SGL_Task_CreateSession,
    SGL_Task_SetupLangSupport3,
    SGL_Task_SetupLocale,
    SGL_Task_AuthenticateRequest,
    SGL_Task_DetectAdminMode,
    SGL_Task_MaintenanceModeIntercept,
    SGL_Task_DetectSessionDebug,
    SGL_Task_SetupPerms,

    SGL_Task_BuildHeaders,
    SGL_Task_BuildView,
    SGL_Task_InitHelpers,
    SGL_Task_SetupBlocks,
    SGL_Task_SetupGui2,
    SGL_Task_SetupWysiwyg,
    SGL_Task_BuildOutputData,

    SGL_MainProcess
';
$conf['site']['globalJavascriptFiles'] = 'simplecms/js/jquery.js,simplecms/js/jquery/ui/effects.core.js,simplecms/js/jquery/ui/effects.highlight.js,js/SGL2.js';
$conf['site']['globalJavascriptOnReadyDom'] = '';
$conf['site']['globalJavascriptOnload'] = '';
$conf['site']['globalJavascriptOnUnload'] = '';
$conf['site']['customOutputClassName'] = 'SimpleCms_Output';
$conf['site']['customRebuildTasks'] = 'SimpleCms_Task_LoadData,SGL_Task_CreateModeratorUser';
$conf['site']['maintenanceMode'] = '';
$conf['site']['adminKey'] = 'foo';
$conf['site']['rolesHaveAdminGui'] = 'SGL_ADMIN,SGL_ROLE_MODERATOR';
$conf['site']['broadcastMessage'] = '';
$conf['site']['loginTarget'] = 'user2^login2';
$conf['site']['logoutTarget'] = 'login';
$conf['site']['serverTimeOffset'] = 'UTC';
$conf['site']['baseUrl'] = 'http://localhost/cms/trunk/www';
$conf['path']['additionalIncludePath'] = '';
$conf['path']['moduleDirOverride'] = 'modulesSCMS';
$conf['path']['uploadDirOverride'] = '';
$conf['path']['tmpDirOverride'] = '';
$conf['path']['pathToCustomConfigFile'] = '';
$conf['path']['installRoot'] = '/Users/demian/Sites/cms/trunk';
$conf['path']['webRoot'] = '/Users/demian/Sites/cms/trunk/www';
$conf['cookie']['path'] = '/';
$conf['cookie']['domain'] = '';
$conf['cookie']['secure'] = '';
$conf['cookie']['name'] = 'SCMSSESSID';
$conf['cookie']['rememberMeEnabled'] = '1';
$conf['session']['maxLifetime'] = '0';
$conf['session']['extended'] = '0';
$conf['session']['singleUser'] = '';
$conf['session']['handler'] = 'file';
$conf['session']['allowedInUri'] = '1';
$conf['session']['savePath'] = '';
$conf['session']['permsRetrievalMethod'] = 'getPermsByUser';
$conf['cache']['enabled'] = '';
$conf['cache']['libCacheEnabled'] = '';
$conf['cache']['lifetime'] = '86400';
$conf['cache']['cleaningFactor'] = '0';
$conf['cache']['readControl'] = '1';
$conf['cache']['writeControl'] = '1';
$conf['cache']['javascript'] = '';
$conf['debug']['authorisationEnabled'] = '1';
$conf['debug']['sessionDebugAllowed'] = false;
$conf['debug']['customErrorHandler'] = '1';
$conf['debug']['production'] = '';
$conf['debug']['showBacktrace'] = '';
$conf['debug']['profiling'] = '';
$conf['debug']['emailAdminThreshold'] = 'PEAR_LOG_EMERG';
$conf['debug']['showBugReporterLink'] = '1';
$conf['debug']['enableDebugBlock'] = '';
$conf['debug']['showUntranslated'] = '1';
$conf['debug']['dataObject'] = '0';
$conf['debug']['infoBlock'] = '';
$conf['translation']['tablePrefix'] = 'translation';
$conf['translation']['addMissingTrans'] = false;
$conf['translation']['fallbackLang'] = 'en_utf_8';
$conf['translation']['container'] = 'file';
$conf['translation']['installedLanguages'] = 'en_utf_8,lv_utf_8,ru_utf_8';
$conf['translation']['languageAutoDiscover'] = '1';
$conf['translation']['defaultLangBC'] = '';
$conf['navigation']['enabled'] = '1';
$conf['navigation']['renderer'] = 'SimpleRenderer';
$conf['navigation']['driver'] = 'ArrayDriver';
$conf['log']['enabled'] = '1';
$conf['log']['type'] = 'file';
$conf['log']['name'] = 'var/log/php_log.txt';
$conf['log']['priority'] = 'PEAR_LOG_DEBUG';
$conf['log']['ident'] = 'Seagull';
$conf['log']['ignoreRepeated'] = '';
$conf['log']['paramsUsername'] = '';
$conf['log']['paramsPassword'] = '';
$conf['log']['showErrors'] = '1';
$conf['mta']['backend'] = 'mail';
$conf['mta']['sendmailPath'] = '/usr/sbin/sendmail';
$conf['mta']['sendmailArgs'] = '-t -i';
$conf['mta']['smtpHost'] = '127.0.0.1';
$conf['mta']['smtpLocalHost'] = 'seagullproject.org';
$conf['mta']['smtpPort'] = '25';
$conf['mta']['smtpAuth'] = '0';
$conf['mta']['smtpUsername'] = '';
$conf['mta']['smtpPassword'] = '';
$conf['email']['admin'] = 'support@simplecms.net';
$conf['email']['support'] = 'support@simplecms.net';
$conf['email']['info'] = 'support@simplecms.net';
$conf['popup']['winHeight'] = '500';
$conf['popup']['winWidth'] = '600';
$conf['censor']['mode'] = 'SGL_CENSOR_DISABLE';
$conf['censor']['replaceString'] = '*censored*';
$conf['censor']['badWords'] = 'your,bad,words,here';
$conf['p3p']['policies'] = '1';
$conf['p3p']['policyLocation'] = '';
$conf['p3p']['compactPolicy'] = 'CUR ADM OUR NOR STA NID';
$conf['tuples']['version'] = '0.6.7
';
$conf['table']['block'] = 'block';
$conf['table']['block_role'] = 'block_role';
$conf['table']['block_assignment'] = 'block_assignment';
$conf['table']['module'] = 'module';
$conf['table']['sequence'] = 'sequence';
$conf['table']['uri_alias'] = 'uri_alias';
$conf['table']['section'] = 'section';
$conf['table']['login'] = 'login';
$conf['table']['organisation'] = 'organisation';
$conf['table']['organisation_type'] = 'organisation_type';
$conf['table']['org_preference'] = 'org_preference';
$conf['table']['permission'] = 'permission';
$conf['table']['preference'] = 'preference';
$conf['table']['role'] = 'role';
$conf['table']['route'] = 'route';
$conf['table']['role_permission'] = 'role_permission';
$conf['table']['user'] = 'usr';
$conf['table']['user_permission'] = 'user_permission';
$conf['table']['user_preference'] = 'user_preference';
$conf['table']['user_session'] = 'user_session';
$conf['table']['user_cookie'] = 'user_cookie';
$conf['table']['media'] = 'media';
$conf['table']['media_type'] = 'media_type';
$conf['table']['media_mime'] = 'media_mime';
$conf['table']['media_type-mime'] = 'media_type-mime';
$conf['table']['content'] = 'content';
$conf['table']['content_type'] = 'content_type';
$conf['table']['attribute'] = 'attribute';
$conf['table']['attribute_data'] = 'attribute_data';
$conf['table']['attribute_type'] = 'attribute_type';
$conf['table']['attribute_list'] = 'attribute_list';
$conf['table']['page'] = 'page';
$conf['table']['category'] = 'category';
$conf['table']['content-category'] = 'content-category';
$conf['table']['category-media'] = 'category-media';
$conf['table']['content-content'] = 'content-content';
$conf['localConfig']['moduleName'] = 'default';
?>