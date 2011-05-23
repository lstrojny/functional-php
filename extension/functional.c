#include "php_functional.h"

#include <stdio.h>
#include "php.h"
#include "zend_API.h"
#include "standard/info.h"


PHP_MINIT_FUNCTION(functional)
{
	return SUCCESS;
}


PHP_MSHUTDOWN_FUNCTION(functional)
{
	return SUCCESS;
}


PHP_MINFO_FUNCTION(functional)
{
	php_info_print_table_start();
	php_info_print_table_header(2, "Functional PHP", "enabled");
	php_info_print_table_row(2, "version", FUNCTIONAL_VERSION);
	php_info_print_table_end();
}

zend_module_entry functional_module_entry = {
	STANDARD_MODULE_HEADER,
	"Functional PHP",
	NULL,
	PHP_MINIT(functional),
	PHP_MSHUTDOWN(functional),
	NULL,
	NULL,
	PHP_MINFO(functional),
	FUNCTIONAL_VERSION,
	STANDARD_MODULE_PROPERTIES
};


#if COMPILE_DL_FUNCTIONAL
ZEND_GET_MODULE(functional)
#endif
