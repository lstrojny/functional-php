#include "php_functional.h"

#include <stdio.h>
#include "php.h"
#include "standard/info.h"
#include "spl/spl_iterators.h"
#include "zend_interfaces.h"

ZEND_BEGIN_ARG_INFO(arginfo_functional_all, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_any, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_each, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_detect, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_map, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_none, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_reject, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_select, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_invoke, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, methodName)
	ZEND_ARG_INFO(0, arguments)
ZEND_END_ARG_INFO()

const zend_function_entry functional_functions[] = {
	ZEND_NS_FE("Functional", all, arginfo_functional_all)
	ZEND_NS_FE("Functional", any, arginfo_functional_any)
	ZEND_NS_FE("Functional", detect, arginfo_functional_detect)
	ZEND_NS_FE("Functional", each, arginfo_functional_each)
	ZEND_NS_FE("Functional", invoke, arginfo_functional_invoke)
	ZEND_NS_FE("Functional", map, arginfo_functional_map)
	ZEND_NS_FE("Functional", none, arginfo_functional_none)
	ZEND_NS_FE("Functional", reject, arginfo_functional_reject)
	ZEND_NS_FE("Functional", select, arginfo_functional_select)
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

#define FUNCTIONAL_ITERATOR_PREPARE \
		zend_object_iterator *iter; \
		zend_class_entry *ce = Z_OBJCE_P(collection); \
		iter = ce->get_iterator(ce, collection, 0 TSRMLS_CC); \
		if (EG(exception)) { \
			goto done; \
		} \
		if (iter->funcs->rewind) { \
			iter->funcs->rewind(iter TSRMLS_CC); \
			if (EG(exception)) { \
				goto done; \
			} \
		}

#define FUNCTIONAL_ARRAY_PREPARE zend_hash_internal_pointer_reset_ex(Z_ARRVAL_P(collection), &pos);

#define FUNCTIONAL_DECLARATION zval *collection; \
	HashPosition pos; \
	zend_fcall_info fci = empty_fcall_info; \
	zend_fcall_info_cache fci_cache = empty_fcall_info_cache; \
	zval **args[3]; \
	zval *retval_ptr; \
	int hash_key_type; \
	zval *key; \
	ulong int_key; \
	char *string_key; \
	uint string_key_len;

#define FUNCTIONAL_PREPARE_ARGS args[1] = &key; \
	args[2] = &collection;

#define FUNCTIONAL_PREPARE_CALLBACK fci.params = args; \
	fci.param_count = 3; \
	fci.no_separation = 0; \
	fci.retval_ptr_ptr = &retval_ptr;


#define FUNCTIONAL_ITERATOR_ITERATE_BEGIN \
		while (iter->funcs->valid(iter TSRMLS_CC) == SUCCESS) { \
			if (EG(exception)) { \
				goto done; \
			} \
			zend_user_it_get_current_data(iter, &args[0]);

#define FUNCTIONAL_ARRAY_ITERATE_BEGIN while (!EG(exception) && zend_hash_get_current_data_ex(Z_ARRVAL_P(collection), (void **)&args[0], &pos) == SUCCESS) {

#define FUNCTIONAL_ARRAY_ITERATE_END \
			zend_hash_move_forward_ex(Z_ARRVAL_P(collection), &pos); \
		}

#define FUNCTIONAL_ITERATOR_ITERATE_END \
			iter->funcs->move_forward(iter TSRMLS_CC); \
			if (EG(exception)) { \
				goto done; \
			} \
		}

#define FUNCTIONAL_ITERATOR_PREPARE_KEY FUNCTIONAL_PREPARE_KEY(zend_user_it_get_current_key(iter, &string_key, &string_key_len, &int_key))

#define FUNCTIONAL_ARRAY_PREPARE_KEY FUNCTIONAL_PREPARE_KEY(zend_hash_get_current_key_ex(Z_ARRVAL_P(collection), &string_key, &string_key_len, &int_key, 0, &pos))

#define FUNCTIONAL_PREPARE_KEY(get_key) \
			MAKE_STD_ZVAL(key); \
			hash_key_type = get_key; \
			php_functional_prepare_array_key(hash_key_type, &key, &args[0], string_key, string_key_len, int_key);

#define FUNCTIONAL_ITERATOR_DONE \
	done: \
		if (iter) { \
			iter->funcs->dtor(iter TSRMLS_CC); \
		}

#define FUNCTIONAL_ARRAY_CALL_BACK				FUNCTIONAL_CALL_BACK(RETURN_FALSE)
#define FUNCTIONAL_ARRAY_CALL_BACK_EX_END		FUNCTIONAL_CALL_BACK_EX_END(goto done)

#define FUNCTIONAL_ITERATOR_CALL_BACK			FUNCTIONAL_CALL_BACK(goto done)
#define FUNCTIONAL_ITERATOR_CALL_BACK_EX_END	FUNCTIONAL_CALL_BACK_EX_END(goto done)

#define FUNCTIONAL_CALL_BACK(on_failure)		FUNCTIONAL_CALL_BACK_EX_BEGIN FUNCTIONAL_CALL_BACK_EX_END(on_failure)
#define FUNCTIONAL_CALL_BACK_EX_BEGIN			if (FUNCTIONAL_CALL_BACK_CALL) {
#define FUNCTIONAL_CALL_BACK_EX_END(on_failure) } else { \
				zval_dtor(return_value); \
				on_failure; \
			}
#define FUNCTIONAL_CALL_BACK_CALL zend_call_function(&fci, &fci_cache TSRMLS_CC) == SUCCESS && !EG(exception)

void php_functional_prepare_array_key(int hash_key_type, zval **key, zval ***value, char *string_key, uint string_key_len, int int_key)
{
	switch (hash_key_type) {
		case HASH_KEY_IS_LONG:
			Z_TYPE_PP(key) = IS_LONG;
			Z_LVAL_PP(key) = int_key;
			break;

		case HASH_KEY_IS_STRING:
			ZVAL_STRINGL(*key, string_key, string_key_len - 1, 1);
			break;
	}
}

void php_functional_append_array_value(int hash_key_type, zval **return_value, zval **value, char *string_key, uint string_key_len, int int_key)
{
	zval_add_ref(return_value);
	zval_add_ref(value);
	switch (hash_key_type) {
		case HASH_KEY_IS_LONG:
			zend_hash_index_update(Z_ARRVAL_PP(return_value), int_key, (void *)value, sizeof(zval *), NULL);
			break;

		case HASH_KEY_IS_STRING:
			zend_hash_update(Z_ARRVAL_PP(return_value), string_key, string_key_len, (void *)value, sizeof(zval *), NULL);
			break;
	}
}


ZEND_FUNCTION(each)
{
	FUNCTIONAL_DECLARATION

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "each")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

ZEND_FUNCTION(any)
{
	FUNCTIONAL_DECLARATION

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "any")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK

	RETVAL_FALSE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETVAL_TRUE;
					break;
				}
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETVAL_TRUE;
					goto done;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

ZEND_FUNCTION(all)
{
	FUNCTIONAL_DECLARATION

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "all")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					RETURN_FALSE;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					RETVAL_FALSE;
					goto done;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

ZEND_FUNCTION(detect)
{
	FUNCTIONAL_DECLARATION

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "detect")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETURN_ZVAL(*args[0], 1, 0);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETVAL_ZVAL(*args[0], 1, 0);
					goto done;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}


ZEND_FUNCTION(map)
{
	FUNCTIONAL_DECLARATION

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "map")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK

	if (return_value_used) {
		array_init(return_value);
	}

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (return_value_used) {
					php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (return_value_used) {
					php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

ZEND_FUNCTION(none)
{
	FUNCTIONAL_DECLARATION

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "none")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETURN_FALSE;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETVAL_FALSE;
					goto done;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

ZEND_FUNCTION(reject)
{
	FUNCTIONAL_DECLARATION

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "reject")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK

	if (return_value_used) {
		array_init(return_value);
	}

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (return_value_used && !zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (return_value_used && !zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

ZEND_FUNCTION(select)
{
	FUNCTIONAL_DECLARATION

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "select")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK

	if (return_value_used) {
		array_init(return_value);
	}

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (return_value_used && zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (return_value_used && zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

ZEND_FUNCTION(invoke)
{
	FUNCTIONAL_DECLARATION

	int arguments_len = 0, element, r;
	zval *method, *null_value, ***method_args;
	HashTable *arguments = NULL;
	char *callable, *error;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z/z/|H", &collection, &method, &arguments) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "invoke")

	array_init(return_value);

	convert_to_string(method);

	MAKE_STD_ZVAL(null_value);

	if (arguments) {
		arguments_len = zend_hash_num_elements(arguments);
		method_args = (zval ***) safe_emalloc(sizeof(zval **), arguments_len, 0);
		for (zend_hash_internal_pointer_reset(arguments);
			zend_hash_get_current_data(arguments, (void **) &(method_args[element])) == SUCCESS;
			zend_hash_move_forward(arguments)
		) {
			element++;
		}
	}

	if (Z_TYPE_P(collection) == IS_ARRAY) {
		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			if (!zend_is_callable_ex(method, &**args[0], IS_CALLABLE_CHECK_SILENT, &callable, 0, &fci_cache, &error TSRMLS_CC)) {
				ZVAL_NULL(null_value);
				php_functional_append_array_value(hash_key_type, &return_value, &null_value, string_key, string_key_len, int_key);
			} else if ((r = call_user_function_ex(EG(function_table), &*args[0], method, &retval_ptr, arguments_len, method_args, 0, NULL TSRMLS_CC)) == SUCCESS) {
				php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key);
			}
			printf("%s::%s() returned %d\n", Z_OBJ_CLASS_NAME_P(*args[0]), Z_STRVAL_P(method), r);
		FUNCTIONAL_ARRAY_ITERATE_END
	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

	/**
	 * Variations:
	 *
	 * Traversable/array
	 *  - DONE: Argument handling
	 *  - DONE: Error messages
	 *
	 * Does something with return value?
	 *	- DONE: Decision to break
	 *	- Collect variables in array
	 *	- Return element
	 *
	 * Return value
	 *	- DONE: Return array untouched
	 *	- Return new array
	 */

