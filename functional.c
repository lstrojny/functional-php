/**
 * Copyright (C) 2011 by Lars Strojny <lstrojny@php.net>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
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
ZEND_BEGIN_ARG_INFO(arginfo_function_drop, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_each, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_first, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_group, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_last, 2)
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
ZEND_BEGIN_ARG_INFO(arginfo_functional_pluck, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, propertyName)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_reduce, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
	ZEND_ARG_INFO(0, initialValue)
ZEND_END_ARG_INFO()

static const zend_function_entry functional_functions[] = {
	ZEND_NS_FENTRY("Functional", all,			ZEND_FN(functional_all),			arginfo_functional_all,				0)
	ZEND_NS_FENTRY("Functional", any,			ZEND_FN(functional_any),			arginfo_functional_any,				0)
	ZEND_NS_FENTRY("Functional", drop_first,	ZEND_FN(functional_drop_first),		arginfo_function_drop,				0)
	ZEND_NS_FENTRY("Functional", drop_last,		ZEND_FN(functional_drop_last),		arginfo_function_drop,				0)
	ZEND_NS_FENTRY("Functional", first,			ZEND_FN(functional_first),			arginfo_functional_first,			0)
	ZEND_NS_FENTRY("Functional", group,			ZEND_FN(functional_group),			arginfo_functional_group,			0)
	ZEND_NS_FENTRY("Functional", each,			ZEND_FN(functional_each),			arginfo_functional_each,			0)
	ZEND_NS_FENTRY("Functional", invoke,		ZEND_FN(functional_invoke),			arginfo_functional_invoke,			0)
	ZEND_NS_FENTRY("Functional", last,			ZEND_FN(functional_last),			arginfo_functional_last,			0)
	ZEND_NS_FENTRY("Functional", map,			ZEND_FN(functional_map),			arginfo_functional_map,				0)
	ZEND_NS_FENTRY("Functional", none,			ZEND_FN(functional_none),			arginfo_functional_none,			0)
	ZEND_NS_FENTRY("Functional", pluck,			ZEND_FN(functional_pluck),			arginfo_functional_pluck,			0)
	ZEND_NS_FENTRY("Functional", reduce_left,	ZEND_FN(functional_reduce_left),	arginfo_functional_reduce,			0)
	ZEND_NS_FENTRY("Functional", reduce_right,	ZEND_FN(functional_reduce_right),	arginfo_functional_reduce,			0)
	ZEND_NS_FENTRY("Functional", reject,		ZEND_FN(functional_reject),			arginfo_functional_reject,			0)
	ZEND_NS_FENTRY("Functional", select,		ZEND_FN(functional_select),			arginfo_functional_select,			0)
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
	php_info_print_table_row(2, "Registered Namespace", "Functional");
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
		!(Z_TYPE_P(collection) == IS_OBJECT && instanceof_function(Z_OBJCE_P(collection), zend_ce_traversable TSRMLS_CC)) \
	) { \
		zend_error(E_WARNING, "Functional\\%s() expects parameter 1 to be array or instance of Traversable", function); \
		RETURN_NULL(); \
	}
#define FUNCTIONAL_ITERATOR_PREPARE \
		ce = Z_OBJCE_P(collection); \
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
#define FUNCTIONAL_DECLARE_FCALL_INFO_CACHE zend_fcall_info_cache fci_cache = empty_fcall_info_cache;
#define FUNCTIONAL_DECLARE(arg_num)	zend_fcall_info fci = empty_fcall_info; \
	FUNCTIONAL_DECLARE_FCALL_INFO_CACHE \
	FUNCTIONAL_DECLARE_EX(arg_num)
#define FUNCTIONAL_DECLARE_EX(arg_num) zval *collection, **args[arg_num], *retval_ptr = NULL, *key; \
	HashPosition pos; \
	zend_object_iterator *iter; \
	zend_class_entry *ce; \
	uint string_key_len, hash_key_type; \
	ulong int_key; \
	char *string_key;
#define FUNCTIONAL_PREPARE_ARGS args[1] = &key; \
	args[2] = &collection;
#define FUNCTIONAL_PREPARE_CALLBACK(arg_num) fci.params = args; \
	fci.param_count = arg_num; \
	fci.no_separation = 0; \
	fci.retval_ptr_ptr = &retval_ptr;
#define FUNCTIONAL_ITERATOR_ITERATE_BEGIN \
		while (iter->funcs->valid(iter TSRMLS_CC) == SUCCESS) { \
			if (EG(exception)) { \
				goto done; \
			} \
			zend_user_it_get_current_data(iter, &args[0] TSRMLS_CC);
#define FUNCTIONAL_ARRAY_ITERATE_BEGIN FUNCTIONAL_ARRAY_ITERATE_BEGIN_EX {
#define FUNCTIONAL_ARRAY_ITERATE_BEGIN_EX while (!EG(exception) && zend_hash_get_current_data_ex(Z_ARRVAL_P(collection), (void **)&args[0], &pos) == SUCCESS)
#define FUNCTIONAL_ARRAY_ITERATE_END \
			zend_hash_move_forward_ex(Z_ARRVAL_P(collection), &pos); \
		}
#define FUNCTIONAL_ITERATOR_ITERATE_END \
			iter->funcs->move_forward(iter TSRMLS_CC); \
			if (EG(exception)) { \
				goto done; \
			} \
		}
#define FUNCTIONAL_ITERATOR_PREPARE_KEY FUNCTIONAL_PREPARE_KEY(zend_user_it_get_current_key(iter, &string_key, &string_key_len, &int_key TSRMLS_CC))
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
#define FUNCTIONAL_ARRAY_CALL_BACK_EX_END		FUNCTIONAL_CALL_BACK_EX_END(break)
#define FUNCTIONAL_ITERATOR_CALL_BACK			FUNCTIONAL_CALL_BACK(goto done)
#define FUNCTIONAL_ITERATOR_CALL_BACK_EX_END	FUNCTIONAL_CALL_BACK_EX_END(goto done)
#define FUNCTIONAL_CALL_BACK(on_failure)		FUNCTIONAL_CALL_BACK_EX_BEGIN FUNCTIONAL_CALL_BACK_EX_END(on_failure)
#define FUNCTIONAL_CALL_BACK_EX_BEGIN			if (FUNCTIONAL_CALL_BACK_CALL) {
#define FUNCTIONAL_CALL_BACK_EX_END(on_failure) } else { \
				/* zval_dtor(return_value); */ \
				on_failure; \
			}
#define FUNCTIONAL_CALL_BACK_CALL zend_call_function(&fci, &fci_cache TSRMLS_CC) == SUCCESS && !EG(exception)
#define FUNCTIONAL_INVOKE_INNER(on_failure) \
			if (!zend_is_callable_ex(method, &**args[0], IS_CALLABLE_CHECK_SILENT, &callable, 0, &fci_cache, &error TSRMLS_CC)) { \
				ZVAL_NULL(null_value); \
				retval_ptr = null_value; \
			} else if (call_user_function_ex(EG(function_table), &*args[0], method, &retval_ptr, arguments_len, method_args, 0, NULL TSRMLS_CC) == SUCCESS) { \
				if (EG(exception)) { \
					on_failure; \
				} \
			} else { \
				ZVAL_NULL(null_value); \
				retval_ptr = null_value; \
			} \
			if (return_value_used) { \
				php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key); \
			}
#define FUNCTIONAL_PLUCK_INNER(on_failure) \
			if (Z_TYPE_P(*args[0]) == IS_OBJECT) { \
				retval_ptr = zend_read_property(scope, &**args[0], property_name, property_name_len, 1 TSRMLS_CC); \
				if (EG(exception)) { \
					on_failure; \
				} \
			} else { \
				ZVAL_NULL(null_value); \
				retval_ptr = null_value; \
			} \
			php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key); \

void php_functional_prepare_array_key(int hash_key_type, zval **key, zval ***value, char *string_key, uint string_key_len, int int_key)
{
	switch (hash_key_type) {
		case HASH_KEY_IS_LONG:
			Z_TYPE_PP(key) = IS_LONG;
			Z_LVAL_PP(key) = int_key;
			break;

		case HASH_KEY_IS_STRING:
			ZVAL_STRINGL(*key, string_key, string_key_len - 1, 0);
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


PHP_FUNCTION(functional_each)
{
	FUNCTIONAL_DECLARE(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "each")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

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

PHP_FUNCTION(functional_any)
{
	FUNCTIONAL_DECLARE(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "any")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

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

PHP_FUNCTION(functional_all)
{
	FUNCTIONAL_DECLARE(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "all")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					RETURN_FALSE;
				}
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
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


PHP_FUNCTION(functional_map)
{
	FUNCTIONAL_DECLARE(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "map")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

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
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
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

PHP_FUNCTION(functional_none)
{
	FUNCTIONAL_DECLARE(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "none")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETURN_FALSE;
				}
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
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

PHP_FUNCTION(functional_reject)
{
	FUNCTIONAL_DECLARE(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "reject")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

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
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
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

PHP_FUNCTION(functional_select)
{
	FUNCTIONAL_DECLARE(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "select")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

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
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
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

PHP_FUNCTION(functional_invoke)
{
	FUNCTIONAL_DECLARE_EX(3)
	FUNCTIONAL_DECLARE_FCALL_INFO_CACHE

	int arguments_len = 0, method_name_len, element = 0;
	zval *method, *null_value, ***method_args = NULL;
	HashTable *arguments = NULL;
	char *callable, *error, *method_name;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zs|H", &collection, &method_name, &method_name_len, &arguments) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "invoke")

	array_init(return_value);

	MAKE_STD_ZVAL(null_value);
	MAKE_STD_ZVAL(method);
	ZVAL_STRINGL(method, method_name, method_name_len, 1);

	if (arguments) {
		arguments_len = zend_hash_num_elements(arguments);
		method_args = (zval ***) safe_emalloc(sizeof(zval **), arguments_len, 0);
		zend_hash_internal_pointer_reset(arguments);
		while (element < arguments_len && zend_hash_get_current_data(arguments, (void **) &(method_args[element])) == SUCCESS) {
			zend_hash_move_forward(arguments);
			element++;
		}
	}

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_INVOKE_INNER(break)
			/** printf("%s::%s() returned\n", Z_OBJ_CLASS_NAME_P(*args[0]), Z_STRVAL_P(method)); */
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_INVOKE_INNER(goto done)
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}

	if (method_args) {
		efree(method_args);
	}
}

PHP_FUNCTION(functional_pluck)
{
	FUNCTIONAL_DECLARE_EX(3)
	char *property_name;
	int property_name_len;
	zend_class_entry *scope;
	zval *null_value;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zs", &collection, &property_name, &property_name_len)) {
		RETURN_NULL();
	}
	FUNCTIONAL_COLLECTION_PARAM(collection, "pluck")

	array_init(return_value);
	MAKE_STD_ZVAL(null_value);

	scope = EG(scope);

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_PLUCK_INNER(break)
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_PLUCK_INNER(goto done)
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_reduce_left)
{
	FUNCTIONAL_DECLARE(4)
	zval *initial = NULL;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf|z", &collection, &fci, &fci_cache, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "reduce_left")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(4)
	if (!initial) {
		MAKE_STD_ZVAL(initial);
		ZVAL_NULL(initial);
	}
	args[3] = &initial;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			MAKE_COPY_ZVAL(args[3], initial);
			zval_add_ref(args[3]);
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				initial = retval_ptr;
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			MAKE_COPY_ZVAL(args[3], initial);
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				initial = retval_ptr;
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}

	RETURN_ZVAL(initial, 0, 0);
}

PHP_FUNCTION(functional_reduce_right)
{
	FUNCTIONAL_DECLARE(4)
	zval *initial = NULL;
	zend_llist reversed;
	zend_llist_position reverse_pos;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf|z", &collection, &fci, &fci_cache, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "reduce_right")
	FUNCTIONAL_PREPARE_ARGS
	if (!initial) {
		MAKE_STD_ZVAL(initial);
		ZVAL_NULL(initial);
	}

	FUNCTIONAL_PREPARE_CALLBACK(4)
	args[3] = &initial;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		zend_hash_internal_pointer_end_ex(Z_ARRVAL_P(collection), &pos);
		FUNCTIONAL_ARRAY_ITERATE_BEGIN_EX {
			FUNCTIONAL_ARRAY_PREPARE_KEY
			MAKE_COPY_ZVAL(args[3], initial);
			zval_add_ref(args[3]);
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				initial = retval_ptr;
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
			zend_hash_move_backwards_ex(Z_ARRVAL_P(collection), &pos);
		}

		if (retval_ptr) {
			RETURN_ZVAL(retval_ptr, 0, 0);
		} else {
			RETURN_ZVAL(initial, 0, 0);
		}

	} else {
		zend_llist_init(&reversed, sizeof(zval), NULL, 0);

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			zval_add_ref(args[0]);
			zend_llist_prepend_element(&reversed, &*args[0]);
			zval_add_ref(args[1]);
			zend_llist_prepend_element(&reversed, &*args[1]);
		FUNCTIONAL_ITERATOR_ITERATE_END

		for (args[1] = (zval **)zend_llist_get_first_ex(&reversed, &reverse_pos);
			args[1];
			args[1] = (zval **)zend_llist_get_next_ex(&reversed, &reverse_pos)) {
			args[0] = (zval **)zend_llist_get_next_ex(&reversed, &reverse_pos);

			MAKE_COPY_ZVAL(args[3], initial);
			zval_add_ref(args[3]);
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				initial = retval_ptr;
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		}
		zend_llist_clean(&reversed);
		zend_llist_destroy(&reversed);

		if (retval_ptr) {
			RETURN_ZVAL(retval_ptr, 0, 0);
		} else {
			RETURN_ZVAL(initial, 0, 0);
		}

		FUNCTIONAL_ITERATOR_DONE
	}
}

PHP_FUNCTION(functional_first)
{
	FUNCTIONAL_DECLARE(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "first")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETURN_ZVAL(*args[0], 1, 0);
				}
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
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


PHP_FUNCTION(functional_last)
{
	FUNCTIONAL_DECLARE(3);

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection, "last")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	RETVAL_NULL();

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETVAL_ZVAL(*args[0], 1, 0);
				}
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETVAL_ZVAL(*args[0], 1, 0);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}

}

PHP_FUNCTION(functional_drop_first)
{
	FUNCTIONAL_DECLARE(3);
	bool drop = true;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	array_init(return_value);

	FUNCTIONAL_COLLECTION_PARAM(collection, "drop_first")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			if (drop) {
				FUNCTIONAL_CALL_BACK_EX_BEGIN
					if (!zend_is_true(retval_ptr)) {
						drop = false;
					} else {
						zend_hash_move_forward_ex(Z_ARRVAL_P(collection), &pos);
						continue;
					}
				FUNCTIONAL_ARRAY_CALL_BACK_EX_END
			}
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			if (drop) {
				FUNCTIONAL_CALL_BACK_EX_BEGIN
					if (!zend_is_true(retval_ptr)) {
						drop = false;
					} else {
						iter->funcs->move_forward(iter TSRMLS_CC);
						continue;
					}
				FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
			}
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_drop_last)
{
	FUNCTIONAL_DECLARE(3);

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	array_init(return_value);

	FUNCTIONAL_COLLECTION_PARAM(collection, "drop_last")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					break;
				}
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					goto done;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

PHP_FUNCTION(functional_group)
{
	FUNCTIONAL_DECLARE(3);
	zval *group, **group_ptr = NULL;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	array_init(return_value);

	FUNCTIONAL_COLLECTION_PARAM(collection, "drop_last")
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				switch (Z_TYPE_P(retval_ptr)) {
					case IS_STRING:
					case IS_NULL:
						printf("null or string: %s\n", Z_STRVAL_P(retval_ptr));
						if (zend_hash_find(Z_ARRVAL_P(return_value), Z_STRVAL_P(retval_ptr), Z_STRLEN_P(retval_ptr) + 1, (void **)&group_ptr) == FAILURE) {
							printf("new init\n");
							MAKE_STD_ZVAL(group);
							array_init(group);
							zend_hash_update(Z_ARRVAL_P(return_value), Z_STRVAL_P(retval_ptr), Z_STRLEN_P(retval_ptr) + 1, &group, sizeof(zval *), NULL);
						} else {
							group = *group_ptr;
						}
						printf("after str group\n");
						break;

					case IS_LONG:
					case IS_DOUBLE:
					case IS_BOOL:
						if (zend_hash_index_find(Z_ARRVAL_P(return_value), Z_LVAL_P(retval_ptr), (void **)&group_ptr) == FAILURE) {
							printf("create long idx\n");
							MAKE_STD_ZVAL(group);
							array_init(group);
							zend_hash_index_update(Z_ARRVAL_P(return_value), Z_LVAL_P(retval_ptr), &group, sizeof(zval *), NULL);
						} else {
							group = *group_ptr;
						}
						printf("after long idx\n");
						break;

					default:
						break;
						// ERROR HANDLING
				}
			php_functional_append_array_value(hash_key_type, &group, args[0], string_key, string_key_len, int_key);
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					goto done;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}

}
