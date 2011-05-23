#ifndef PHP_FUNCTIONAL_H
#define PHP_FUNCTIONAL_H
#define FUNCTIONAL_VERSION "0.0.1"

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif
#include "php.h"
#include "zend_API.h"
#include "zend_compile.h"

extern zend_module_entry functional_module_entry;
#define phpext_functional_ptr &functional_module_entry

PHP_MINIT_FUNCTION(functional);
PHP_MSHUTDOWN_FUNCTION(functional);
PHP_MINFO_FUNCTION(functional);

#ifdef ZTS
#define FUNCTIONAL(v) TSRMG(functional_globals_id, zend_functional_globals *, v)
#else
#define FUNCTIONAL(v) (functional_globals.v)
#endif

#ifdef ZTS
#include "TSRM.h"
#endif

#ifndef TRUE
#define TRUE 1
#define FALSE 0
#endif


#endif
