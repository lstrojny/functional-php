#include "php_functional.h"

#include <stdio.h>
#include "php.h"
#include "standard/info.h"
#include "spl/spl_iterators.h"
#include "zend_interfaces.h"

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

#define FUNCTIONAL_COLLECTION_PARAM(collection, function) \
	if ( \
		Z_TYPE_P(collection) != IS_ARRAY && \
		!(Z_TYPE_P(collection) == IS_OBJECT && instanceof_function(Z_OBJCE_P(collection), zend_ce_traversable)) \
	) { \
		zend_error(E_WARNING, "Functional\\%s() expects parameter 1 to be array or instance of Traversable", function); \
		RETURN_NULL(); \
	}


void php_functional_prepare_array_key(int hash_key_type, zval **key, zval ***value, char *string_key, uint string_key_len, int num_key, zval **return_value)
{
	switch (hash_key_type) {
		case HASH_KEY_IS_LONG:
			Z_TYPE_PP(key) = IS_LONG;
			Z_LVAL_PP(key) = num_key;
			zend_hash_index_update(Z_ARRVAL_PP(return_value), num_key, *value, sizeof(zval *), NULL);
			break;

		case HASH_KEY_IS_STRING:
			ZVAL_STRINGL(*key, string_key, string_key_len - 1, 1);
			zend_hash_update(Z_ARRVAL_PP(return_value), string_key, string_key_len, *value, sizeof(zval *), NULL);
			break;
	}
}


ZEND_FUNCTION(each)
{
	zval *collection;
	HashPosition pos;
	zend_fcall_info fci = empty_fcall_info;
	zend_fcall_info_cache fci_cache = empty_fcall_info_cache;
	zval **args[3];
	zval *retval_ptr;
	int is_array = 0;
	int hash_key_type;

	zval *key;
	ulong num_key;
	char *string_key;
	uint string_key_len;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "each")

	if (Z_TYPE_P(collection) == IS_ARRAY) {
		is_array = 1;
	}

	/**
	 * Variations:
	 *
	 * Traversable/array
	 *  - DONE: Argument handling
	 *  - DONE: Error messages
	 *
	 * Does something with return value?
	 *	- Decision to break
	 *	- Collect variables in array
	 *	- Return element
	 *
	 * Return value
	 *	- Return array untouched
	 *	- Return new array
	 */

	args[1] = &key;
	args[2] = &collection;

	fci.params = args;
	fci.param_count = 3;
	fci.no_separation = 0;
	fci.retval_ptr_ptr = &retval_ptr;

	array_init(return_value);

	if (is_array) {
		zend_hash_internal_pointer_reset_ex(Z_ARRVAL_P(collection), &pos);


		while (!EG(exception) && zend_hash_get_current_data_ex(Z_ARRVAL_P(collection), (void **)&args[0], &pos) == SUCCESS) {

			MAKE_STD_ZVAL(key);
			zval_add_ref(args[0]);

			hash_key_type = zend_hash_get_current_key_ex(Z_ARRVAL_P(collection), &string_key, &string_key_len, &num_key, 0, &pos);
			php_functional_prepare_array_key(hash_key_type, &key, &args[0], string_key, string_key_len, num_key, &return_value);

			if (zend_call_function(&fci, &fci_cache TSRMLS_CC) == SUCCESS && !EG(exception)) {
				zval_ptr_dtor(&retval_ptr);
			} else {
				zval_dtor(return_value);
				RETURN_NULL();
			}

			zend_hash_move_forward_ex(Z_ARRVAL_P(collection), &pos);
		}
	} else {
		zend_object_iterator   *iter;
		zend_class_entry       *ce = Z_OBJCE_P(collection);

		iter = ce->get_iterator(ce, collection, 0 TSRMLS_CC);

		if (EG(exception)) {
			goto done;
		}

		if (iter->funcs->rewind) {
			iter->funcs->rewind(iter TSRMLS_CC);
			if (EG(exception)) {
				goto done;
			}
		}

		while (iter->funcs->valid(iter TSRMLS_CC) == SUCCESS) {
			if (EG(exception)) {
				goto done;
			}

			MAKE_STD_ZVAL(key);

			zend_user_it_get_current_data(iter, &args[0]);

			hash_key_type = zend_user_it_get_current_key(iter, &string_key, &string_key_len, &num_key);
			php_functional_prepare_array_key(hash_key_type, &key, &args[0], string_key, string_key_len, num_key, &return_value);

			if (zend_call_function(&fci, &fci_cache TSRMLS_CC) == SUCCESS && !EG(exception)) {
				zval_ptr_dtor(&retval_ptr);
			} else {
				zval_dtor(return_value);
				RETURN_NULL();
			}

			iter->funcs->move_forward(iter TSRMLS_CC);
			if (EG(exception)) {
				goto done;
			}
		}

	done:
		if (iter) {
			iter->funcs->dtor(iter TSRMLS_CC);
		}
	}
}


