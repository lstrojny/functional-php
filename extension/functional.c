#include "php_functional.h"

#include <stdio.h>
#include "php.h"
#include "standard/info.h"

ZEND_BEGIN_ARG_INFO(arginfo_functional_each, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()

const zend_function_entry functional_functions[] = {
	ZEND_NS_FE("Functional", each, arginfo_functional_each)
	{NULL, NULL, NULL}
};

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
	"functional",
	functional_functions,
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

ZEND_FUNCTION(each)
{
	zval *hash;
	HashPosition pos;
	zend_fcall_info fci = empty_fcall_info;
	zend_fcall_info_cache fci_cache = empty_fcall_info_cache;
	zval **args[3];
	zval *retval_ptr;

	zval *key;
	ulong num_key;
	char *string_key;
	uint string_key_len;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "af", &hash, &fci, &fci_cache) == FAILURE) {
		return;
	}

	args[1] = &key;
	args[2] = &hash;

	fci.params = args;
	fci.param_count = 3;
	fci.no_separation = 0;
	fci.retval_ptr_ptr = &retval_ptr;

	zend_hash_internal_pointer_reset_ex(Z_ARRVAL_P(hash), &pos);

	array_init(return_value);

	while (!EG(exception) && zend_hash_get_current_data_ex(Z_ARRVAL_P(hash), (void **)&args[0], &pos) == SUCCESS) {

		MAKE_STD_ZVAL(key);
		zval_add_ref(args[0]);

		switch (zend_hash_get_current_key_ex(Z_ARRVAL_P(hash), &string_key, &string_key_len, &num_key, 0, &pos)) {
			case HASH_KEY_IS_LONG:
				Z_TYPE_P(key) = IS_LONG;
				Z_LVAL_P(key) = num_key;
				zend_hash_index_update(Z_ARRVAL_P(return_value), num_key, args[0], sizeof(zval *), NULL);
				break;

			case HASH_KEY_IS_STRING:
				ZVAL_STRINGL(key, string_key, string_key_len - 1, 1);
				zend_hash_update(Z_ARRVAL_P(return_value), string_key, string_key_len, args[0], sizeof(zval *), NULL);
				break;
		}


		if (zend_call_function(&fci, &fci_cache TSRMLS_CC) == SUCCESS) {
			zval_ptr_dtor(&retval_ptr);
		} else {
			break;
		}

		zend_hash_move_forward_ex(Z_ARRVAL_P(hash), &pos);
	}
}
