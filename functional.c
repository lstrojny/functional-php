/**
 * Copyright (C) 2011-2015 by Lars Strojny <lstrojny@php.net>, Max Beutel <me@maxbeutel.de>
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
#include "zend.h"

ZEND_BEGIN_ARG_INFO(arginfo_functional_every, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_some, 2)
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
ZEND_BEGIN_ARG_INFO(arginfo_functional_partition, 2)
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
ZEND_BEGIN_ARG_INFO_EX(arginfo_functional_invoke, 0, 0, 2)
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
ZEND_BEGIN_ARG_INFO(arginfo_functional_flatten, 1)
	ZEND_ARG_INFO(0, collection)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_math, 1)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, initial)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO_EX(arginfo_functional_unique, 0, 0, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
	ZEND_ARG_INFO(0, strict)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_maximum, 1)
	ZEND_ARG_INFO(0, collection)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_minimum, 1)
	ZEND_ARG_INFO(0, collection)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_first_index_of, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, value)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_last_index_of, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, value)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_bool_funcs, 1)
	ZEND_ARG_INFO(0, collection)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO_EX(arginfo_functional_contains, 0, 0, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, value)
	ZEND_ARG_INFO(0, strict)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO_EX(arginfo_functional_invoke_first, 0, 0, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, methodName)
	ZEND_ARG_INFO(0, arguments)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO_EX(arginfo_functional_invoke_last, 0, 0, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, methodName)
	ZEND_ARG_INFO(0, arguments)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO_EX(arginfo_functional_zip, 0, 0, 1)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, ...)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO(arginfo_functional_tail, 2)
	ZEND_ARG_INFO(0, collection)
	ZEND_ARG_INFO(0, callback)
ZEND_END_ARG_INFO()
ZEND_BEGIN_ARG_INFO_EX(arginfo_functional_invoke_if, 0, 0, 2)
	ZEND_ARG_INFO(0, object)
	ZEND_ARG_INFO(0, methodName)
	ZEND_ARG_INFO(0, arguments)
	ZEND_ARG_INFO(0, defaultValue)
ZEND_END_ARG_INFO()

static const zend_function_entry functional_functions[] = {
	ZEND_NS_FENTRY("Functional", every,          ZEND_FN(functional_every),          arginfo_functional_every,           0)
	ZEND_NS_FENTRY("Functional", some,           ZEND_FN(functional_some),           arginfo_functional_some,            0)
	ZEND_NS_FENTRY("Functional", drop_first,     ZEND_FN(functional_drop_first),     arginfo_function_drop,              0)
	ZEND_NS_FENTRY("Functional", drop_last,      ZEND_FN(functional_drop_last),      arginfo_function_drop,              0)
	ZEND_NS_FENTRY("Functional", first,          ZEND_FN(functional_first),          arginfo_functional_first,           0)
	ZEND_NS_FALIAS("Functional", head,           functional_first,                   arginfo_functional_first             )
	ZEND_NS_FENTRY("Functional", group,          ZEND_FN(functional_group),          arginfo_functional_group,           0)
	ZEND_NS_FENTRY("Functional", each,           ZEND_FN(functional_each),           arginfo_functional_each,            0)
	ZEND_NS_FENTRY("Functional", invoke,         ZEND_FN(functional_invoke),         arginfo_functional_invoke,          0)
	ZEND_NS_FENTRY("Functional", last,           ZEND_FN(functional_last),           arginfo_functional_last,            0)
	ZEND_NS_FENTRY("Functional", map,            ZEND_FN(functional_map),            arginfo_functional_map,             0)
	ZEND_NS_FENTRY("Functional", none,           ZEND_FN(functional_none),           arginfo_functional_none,            0)
	ZEND_NS_FENTRY("Functional", partition,      ZEND_FN(functional_partition),      arginfo_functional_partition,       0)
	ZEND_NS_FENTRY("Functional", pluck,          ZEND_FN(functional_pluck),          arginfo_functional_pluck,           0)
	ZEND_NS_FENTRY("Functional", reduce_left,    ZEND_FN(functional_reduce_left),    arginfo_functional_reduce,          0)
	ZEND_NS_FENTRY("Functional", reduce_right,   ZEND_FN(functional_reduce_right),   arginfo_functional_reduce,          0)
	ZEND_NS_FENTRY("Functional", reject,         ZEND_FN(functional_reject),         arginfo_functional_reject,          0)
	ZEND_NS_FENTRY("Functional", select,         ZEND_FN(functional_select),         arginfo_functional_select,          0)
	ZEND_NS_FALIAS("Functional", filter,         functional_select,                  arginfo_functional_select            )
	ZEND_NS_FENTRY("Functional", flatten,        ZEND_FN(functional_flatten),        arginfo_functional_flatten,         0)
	ZEND_NS_FENTRY("Functional", average,        ZEND_FN(functional_average),        arginfo_functional_math,            0)
	ZEND_NS_FENTRY("Functional", sum,            ZEND_FN(functional_sum),            arginfo_functional_math,            0)
	ZEND_NS_FENTRY("Functional", difference,     ZEND_FN(functional_difference),     arginfo_functional_math,            0)
	ZEND_NS_FENTRY("Functional", product,        ZEND_FN(functional_product),        arginfo_functional_math,            0)
	ZEND_NS_FENTRY("Functional", ratio,          ZEND_FN(functional_ratio),          arginfo_functional_math,            0)
	ZEND_NS_FENTRY("Functional", unique,         ZEND_FN(functional_unique),         arginfo_functional_unique,          0)
	ZEND_NS_FENTRY("Functional", maximum,        ZEND_FN(functional_maximum),        arginfo_functional_maximum,         0)
	ZEND_NS_FENTRY("Functional", minimum,        ZEND_FN(functional_minimum),        arginfo_functional_minimum,         0)
	ZEND_NS_FENTRY("Functional", first_index_of, ZEND_FN(functional_first_index_of), arginfo_functional_first_index_of,  0)
	ZEND_NS_FENTRY("Functional", last_index_of,  ZEND_FN(functional_last_index_of),  arginfo_functional_last_index_of,   0)
	ZEND_NS_FENTRY("Functional", true,           ZEND_FN(functional_true),           arginfo_functional_bool_funcs,      0)
	ZEND_NS_FENTRY("Functional", false,          ZEND_FN(functional_false),          arginfo_functional_bool_funcs,      0)
	ZEND_NS_FENTRY("Functional", truthy,         ZEND_FN(functional_truthy),         arginfo_functional_bool_funcs,      0)
	ZEND_NS_FENTRY("Functional", falsy,          ZEND_FN(functional_falsy),          arginfo_functional_bool_funcs,      0)
	ZEND_NS_FENTRY("Functional", contains,       ZEND_FN(functional_contains),       arginfo_functional_contains,        0)
	ZEND_NS_FENTRY("Functional", invoke_first,   ZEND_FN(functional_invoke_first),   arginfo_functional_invoke_first,    0)
	ZEND_NS_FENTRY("Functional", invoke_last,    ZEND_FN(functional_invoke_last),    arginfo_functional_invoke_last,     0)
	ZEND_NS_FENTRY("Functional", zip,            ZEND_FN(functional_zip),            arginfo_functional_zip,             0)
	ZEND_NS_FENTRY("Functional", tail,           ZEND_FN(functional_tail),           arginfo_functional_tail,            0)
	ZEND_NS_FENTRY("Functional", invoke_if,      ZEND_FN(functional_invoke_if),      arginfo_functional_invoke_if,       0)
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

#define FUNCTIONAL_COLLECTION_PARAM(collection) \
	FUNCTIONAL_COLLECTION_PARAM_EX(collection, 1)
#define FUNCTIONAL_COLLECTION_PARAM_EX(collection, position) \
	if ((FUNCTIONAL_NOT_ITERABLE(collection))) { \
		zend_error(E_WARNING, "%s() expects parameter %d to be array or instance of Traversable", get_active_function_name(TSRMLS_C), position); \
		RETURN_NULL(); \
	}
#define FUNCTIONAL_PROPERTY_NAME_PARAM(property) \
	if (Z_TYPE_P(property) != IS_STRING && Z_TYPE_P(property) != IS_DOUBLE && Z_TYPE_P(property) != IS_LONG && Z_TYPE_P(property) != IS_NULL) { \
		zend_error(E_WARNING, "%s() expects parameter 2 to be a valid property name or array index, %s given", get_active_function_name(TSRMLS_C), zend_get_type_by_const(Z_TYPE_P(property))); \
		RETURN_NULL(); \
	}
#define FUNCTIONAL_NOT_ITERABLE(arg) \
		Z_TYPE_P(arg) != IS_ARRAY && \
		!(Z_TYPE_P(arg) == IS_OBJECT && instanceof_function(Z_OBJCE_P(arg), zend_ce_traversable TSRMLS_CC))
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
#define FUNCTIONAL_DECLARE_FCI(arg_num)	zend_fcall_info fci = empty_fcall_info; \
	FUNCTIONAL_DECLARE_FCALL_INFO_CACHE \
	FUNCTIONAL_DECLARE_EX(arg_num)
#define FUNCTIONAL_DECLARE_EX(arg_num)	FUNCTIONAL_DECLARE(arg_num) \
	uint string_key_len, hash_key_type; \
	ulong int_key; \
	zval *retval_ptr = NULL, *key = NULL; \
	char *string_key = NULL;
#define FUNCTIONAL_DECLARE(arg_num) zval *collection, **args[arg_num]; \
	HashPosition pos; \
	zend_object_iterator *iter; \
	zend_class_entry *ce;
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
#if PHP_VERSION_ID >= 50500
#define FUNCTIONAL_ITERATOR_PREPARE_KEY { \
		zval tmp_key; \
		zend_user_it_get_current_key(iter, &tmp_key TSRMLS_CC); \
		if (Z_TYPE(tmp_key) == IS_STRING) { \
			hash_key_type = HASH_KEY_IS_STRING; \
			string_key = Z_STRVAL(tmp_key); \
			string_key_len = Z_STRLEN(tmp_key) + 1; \
		} else { \
			hash_key_type = HASH_KEY_IS_LONG; \
			int_key = Z_LVAL(tmp_key); \
		} \
		FUNCTIONAL_PREPARE_KEY(hash_key_type) \
	}
#else
#define FUNCTIONAL_ITERATOR_PREPARE_KEY FUNCTIONAL_PREPARE_KEY(zend_user_it_get_current_key(iter, &string_key, &string_key_len, &int_key TSRMLS_CC))
#endif
#define FUNCTIONAL_ARRAY_PREPARE_KEY FUNCTIONAL_PREPARE_KEY(zend_hash_get_current_key_ex(Z_ARRVAL_P(collection), &string_key, &string_key_len, &int_key, 0, &pos))
#define FUNCTIONAL_PREPARE_KEY(get_key) MAKE_STD_ZVAL(key); \
			hash_key_type = get_key; \
			php_functional_prepare_array_key(hash_key_type, &key, &args[0], string_key, string_key_len, int_key);
#define FUNCTIONAL_ARRAY_FREE_KEY efree(key);
#define FUNCTIONAL_ITERATOR_FREE_KEY FUNCTIONAL_ARRAY_FREE_KEY; if (hash_key_type == HASH_KEY_IS_STRING) efree(string_key);
#define FUNCTIONAL_ITERATOR_DONE done: \
	if (iter) { \
		iter->funcs->dtor(iter TSRMLS_CC); \
	}
#define FUNCTIONAL_ARRAY_CALL_BACK				FUNCTIONAL_CALL_BACK(RETURN_FALSE)
#define FUNCTIONAL_ARRAY_CALL_BACK_EX_END		FUNCTIONAL_CALL_BACK_EX_END(break)
#define FUNCTIONAL_ITERATOR_CALL_BACK			FUNCTIONAL_CALL_BACK(goto done)
#define FUNCTIONAL_ITERATOR_CALL_BACK_EX_END	FUNCTIONAL_CALL_BACK_EX_END(goto done)
#define FUNCTIONAL_CALL_BACK(on_failure)		FUNCTIONAL_CALL_BACK_EX_BEGIN FUNCTIONAL_CALL_BACK_EX_END(on_failure)
#define FUNCTIONAL_CALL_BACK_EX_BEGIN			if (FUNCTIONAL_CALL_BACK_CALL) {
#define FUNCTIONAL_CALL_BACK_EX_END(on_failure) if (retval_ptr) { \
			zval_ptr_dtor(&retval_ptr); \
			retval_ptr = NULL; \
		} \
	} else { \
		if (retval_ptr) { \
			zval_ptr_dtor(&retval_ptr); \
			retval_ptr = NULL; \
		} \
		on_failure; \
	}
#define FUNCTIONAL_CALL_BACK_CALL zend_call_function(&fci, &fci_cache TSRMLS_CC) == SUCCESS && !EG(exception)
#define FUNCTIONAL_INVOKE_INNER(on_failure) \
	if (!zend_is_callable_ex(method, &**args[0], IS_CALLABLE_CHECK_SILENT, &callable, 0, &fci_cache, &error TSRMLS_CC)) { \
		MAKE_STD_ZVAL(retval_ptr); \
		ZVAL_NULL(retval_ptr); \
	} else if (call_user_function_ex(EG(function_table), &*args[0], method, &retval_ptr, arguments_len, method_args, 1, NULL TSRMLS_CC) == SUCCESS) { \
		if (EG(exception)) { \
			on_failure; \
		} \
	} else { \
		MAKE_STD_ZVAL(retval_ptr); \
		ZVAL_NULL(retval_ptr); \
	} \
	efree(callable); \
	php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key);
#define FUNCTIONAL_INVOKE_FIRST_INNER(end) if (Z_TYPE_P(&**args[0]) == IS_OBJECT && zend_is_callable_ex(method, &**args[0], IS_CALLABLE_CHECK_SILENT, &callable, 0, &fci_cache, &error TSRMLS_CC)) { \
		if (call_user_function_ex(EG(function_table), &*args[0], method, &retval_ptr, arguments_len, method_args, 1, NULL TSRMLS_CC) == SUCCESS) { \
			if (EG(exception)) { \
				end; \
			} \
			*return_value = *retval_ptr; \
			zval_copy_ctor(return_value); \
		} \
		efree(callable); \
		end; \
	}
#define FUNCTIONAL_INVOKE_LAST_INNER if (Z_TYPE_P(&**args[0]) == IS_OBJECT && zend_is_callable_ex(method, &**args[0], IS_CALLABLE_CHECK_SILENT, &callable, 0, &fci_cache, &error TSRMLS_CC)) { \
		last_invokable_callback_found = 1; \
		last_invokable_obj = &*args[0]; \
		last_invokable_method = method; \
		last_invokable_arguments_len = arguments_len; \
		last_invokable_method_args = method_args; \
	}
#define FUNCTIONAL_INVOKE_LAST(on_error) if (last_invokable_callback_found) { \
		if (call_user_function_ex(EG(function_table), last_invokable_obj, last_invokable_method, &retval_ptr, last_invokable_arguments_len, last_invokable_method_args, 1, NULL TSRMLS_CC) == SUCCESS) { \
			if (EG(exception)) { \
				on_error; \
			} \
			*return_value = *retval_ptr; \
			zval_copy_ctor(return_value); \
		} \
	}
#define FUNCTIONAL_INVOKE_STRATEGY_ALL 0
#define FUNCTIONAL_INVOKE_STRATEGY_FIRST 1
#define FUNCTIONAL_INVOKE_STRATEGY_LAST -1
#if PHP_VERSION_ID >= 50400
#define FUNCTIONAL_HAS_PROPERTY(obj, value, property) obj->has_property(value, property, 0, NULL TSRMLS_CC)
#else
#define FUNCTIONAL_HAS_PROPERTY(obj, value, property) obj->has_property(value, property, 0 TSRMLS_CC)
#endif
#if PHP_VERSION_ID >= 50400
#define FUNCTIONAL_READ_PROPERTY(obj, value, property) obj->read_property(value, property, BP_VAR_IS, NULL TSRMLS_CC);
#else
#define FUNCTIONAL_READ_PROPERTY(obj, value, property) obj->read_property(value, property, BP_VAR_IS TSRMLS_CC);
#endif
#define FUNCTIONAL_PLUCK_INNER(on_failure, suffix) \
	if (numeric && Z_TYPE_PP(args[0]) == IS_ARRAY) { \
		if (zend_hash_index_find(HASH_OF(*args[0]), h, (void **)&hash_value) == SUCCESS) { \
			retval_ptr = *hash_value; \
		} else { \
			goto null_##suffix; \
		} \
	} else if (Z_TYPE_PP(args[0]) == IS_OBJECT) { \
		if (Z_OBJ_HT_PP(args[0])->has_property && FUNCTIONAL_HAS_PROPERTY(Z_OBJ_HT_PP(args[0]), &**args[0], property)) { \
			retval_ptr = FUNCTIONAL_READ_PROPERTY(Z_OBJ_HT_P(*args[0]), &**args[0], property); \
		} else if (Z_OBJ_HT_PP(args[0])->read_dimension && instanceof_function_ex(Z_OBJCE_PP(args[0]), zend_ce_arrayaccess, 1 TSRMLS_CC)) { \
			retval_ptr = Z_OBJ_HT_PP(args[0])->read_dimension(&**args[0], property, BP_VAR_R TSRMLS_CC); \
		} else { \
			goto null_##suffix; \
		} \
		if (EG(exception)) { \
			on_failure; \
		} \
	} else if (Z_TYPE_PP(args[0]) == IS_ARRAY) { \
		if (h == 0) { \
			h = zend_get_hash_value(Z_STRVAL_P(property), Z_STRLEN_P(property) + 1); \
		} \
		if (zend_hash_quick_find(HASH_OF(*args[0]), Z_STRVAL_P(property), Z_STRLEN_P(property) + 1, h, (void **)&hash_value) == SUCCESS) { \
			retval_ptr = *hash_value; \
		} else { \
			goto null_##suffix; \
		} \
	} else { \
		null_##suffix: \
		MAKE_STD_ZVAL(retval_ptr); \
		ZVAL_NULL(retval_ptr); \
	} \
	php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key);
#define FUNCTIONAL_MATH(sym, in) FUNCTIONAL_MATH_EX(sym, in, ;)
#define FUNCTIONAL_MATH_EX(sym, in, cb) FUNCTIONAL_COLLECTION_PARAM(collection) \
	if (initial) { \
		RETVAL_ZVAL(initial, 1, 0); \
	} else { \
		ZVAL_LONG(return_value, in); \
	} \
	if (Z_TYPE_P(collection) == IS_ARRAY) { \
		FUNCTIONAL_ARRAY_PREPARE \
		FUNCTIONAL_ARRAY_ITERATE_BEGIN \
			FUNCTIONAL_MATH_CALC(sym, cb) \
		FUNCTIONAL_ARRAY_ITERATE_END \
	} else { \
		FUNCTIONAL_ITERATOR_PREPARE \
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN \
			FUNCTIONAL_MATH_CALC(sym, cb) \
		FUNCTIONAL_ITERATOR_ITERATE_END \
		FUNCTIONAL_ITERATOR_DONE \
	}
#define FUNCTIONAL_MATH_CALC(sym, cb) el = **args[0]; \
	type = FAILURE; \
	switch (Z_TYPE(el)) { \
		case IS_LONG: \
			lval = Z_LVAL(el); \
			type = IS_LONG; \
			break; \
		case IS_STRING: \
			type = is_numeric_string(Z_STRVAL(el), Z_STRLEN(el), &lval, &dval, 0); \
			break; \
		case IS_DOUBLE: \
			dval = Z_DVAL(el); \
			type = IS_DOUBLE; \
			break; \
	} \
	if (type > 0) { \
		if (type == IS_DOUBLE && Z_TYPE_P(return_value) != IS_DOUBLE) { \
			convert_to_double(return_value); \
		} \
		if (type == IS_LONG && Z_TYPE_P(return_value) == IS_LONG) { \
			dval = (double)Z_LVAL_P(return_value) sym (double)lval; \
			if ((double)(long)dval == dval && dval >= (double)LONG_MIN && dval <= (double)LONG_MAX) { \
				Z_LVAL_P(return_value) = (long)dval; \
			} else { \
				convert_to_double(return_value); \
				Z_DVAL_P(return_value) = dval; \
			} \
		} else { \
			if (type == IS_LONG) { \
				dval = (double)lval; \
			} \
			Z_DVAL_P(return_value) sym##= dval; \
		} \
		cb; \
	}
#define FUNCTIONAL_UNIQUE_INNER(CALL_BACK_END) \
	if (ZEND_FCI_INITIALIZED(fci)) { \
		FUNCTIONAL_CALL_BACK_EX_BEGIN \
			if (php_functional_in_array(indexes, retval_ptr, strict TSRMLS_CC) == 0) { \
				php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key); \
				php_functional_append_array_value(hash_key_type, &indexes, &retval_ptr, string_key, string_key_len, int_key); \
			} \
		CALL_BACK_END \
	} else { \
		if (php_functional_in_array(indexes, *args[0], strict TSRMLS_CC) == 0) { \
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key); \
			php_functional_append_array_value(hash_key_type, &indexes, args[0], string_key, string_key_len, int_key); \
		} \
	}
#define FUNCTIONAL_IS_NUMERIC_PP(arg) FUNCTIONAL_IS_NUMERIC_P(*arg)
#define FUNCTIONAL_IS_NUMERIC_P(arg) FUNCTIONAL_IS_NUMERIC(*arg)
#define FUNCTIONAL_IS_NUMERIC(arg) (Z_TYPE(arg) == IS_LONG || Z_TYPE(arg) == IS_DOUBLE || (Z_TYPE(arg) == IS_STRING && is_numeric_string(Z_STRVAL(arg), Z_STRLEN(arg), NULL, NULL, 0)))

void inline php_functional_prepare_array_key(int hash_key_type, zval **key, zval ***value, char *string_key, uint string_key_len, int int_key)
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

void inline php_functional_append_array_value(int hash_key_type, zval **return_value, zval **value, char *string_key, uint string_key_len, int int_key)
{
	zval_add_ref(value);
	if (hash_key_type == HASH_KEY_IS_LONG) {
		zend_hash_index_update(Z_ARRVAL_PP(return_value), int_key, (void *)value, sizeof(zval *), NULL);
	} else if (hash_key_type == HASH_KEY_IS_STRING) {
		zend_hash_update(Z_ARRVAL_PP(return_value), string_key, string_key_len, (void *)value, sizeof(zval *), NULL);
	}
}


PHP_FUNCTION(functional_each)
{
	FUNCTIONAL_DECLARE_FCI(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK
			FUNCTIONAL_ARRAY_FREE_KEY
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_some)
{
	FUNCTIONAL_DECLARE_FCI(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
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
			FUNCTIONAL_ARRAY_FREE_KEY
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
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_every)
{
	FUNCTIONAL_DECLARE_FCI(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					FUNCTIONAL_ARRAY_FREE_KEY
					zval_ptr_dtor(&retval_ptr);
					RETURN_FALSE;
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					FUNCTIONAL_ITERATOR_FREE_KEY
					zval_ptr_dtor(&retval_ptr);
					RETVAL_FALSE;
					goto done;
				}
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}


PHP_FUNCTION(functional_map)
{
	FUNCTIONAL_DECLARE_FCI(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	array_init(return_value);

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key);
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				php_functional_append_array_value(hash_key_type, &return_value, &retval_ptr, string_key, string_key_len, int_key);
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_none)
{
	FUNCTIONAL_DECLARE_FCI(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					FUNCTIONAL_ARRAY_FREE_KEY
					zval_ptr_dtor(&retval_ptr);
					RETURN_FALSE;
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					FUNCTIONAL_ITERATOR_FREE_KEY
					RETVAL_FALSE;
					goto done;
				}
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_reject)
{
	FUNCTIONAL_DECLARE_FCI(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	array_init(return_value);

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_select)
{
	FUNCTIONAL_DECLARE_FCI(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	array_init(return_value);

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}



PHP_FUNCTION(functional_pluck)
{
	FUNCTIONAL_DECLARE_EX(3)
	int numeric = 0;
	ulong h = 0;
	zval *property,
		**hash_value;
	zend_class_entry *calling_scope;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zz", &collection, &property)) {
		RETURN_NULL();
	}
	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PROPERTY_NAME_PARAM(property)

	array_init(return_value);

	switch (Z_TYPE_P(property)) {
		case IS_LONG:
			numeric = 1;
			break;

		case IS_NULL:
			convert_to_string(property);
			break;

		default:
			ZEND_HANDLE_NUMERIC_EX(Z_STRVAL_P(property), Z_STRLEN_P(property) + 1, h, numeric = 1);
			break;
	}

	calling_scope = EG(scope);
	EG(scope) = NULL;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_PLUCK_INNER(break, array)
			FUNCTIONAL_ARRAY_FREE_KEY
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_PLUCK_INNER(goto done, iter)
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}

	EG(scope) = calling_scope;
}

PHP_FUNCTION(functional_reduce_left)
{
	FUNCTIONAL_DECLARE_FCI(4)
	zval *initial, *result = NULL;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf|z", &collection, &fci, &fci_cache, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(4)

	if (ZEND_NUM_ARGS() > 2) {
		ALLOC_ZVAL(result);
		MAKE_COPY_ZVAL(&initial, result);
	} else {
		MAKE_STD_ZVAL(result);
		ZVAL_NULL(result);
	}

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			args[3] = &result;
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				zval_ptr_dtor(&result);
				result = retval_ptr;
				zval_add_ref(&result);
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			args[3] = &result;
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				zval_ptr_dtor(&result);
				result = retval_ptr;
				zval_add_ref(&result);
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}

	RETURN_ZVAL(result, 1, 1);
}

PHP_FUNCTION(functional_reduce_right)
{
	FUNCTIONAL_DECLARE_FCI(4)
	zval *initial, *result = NULL;
	zend_llist reversed;
	zend_llist_position reverse_pos;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf|z", &collection, &fci, &fci_cache, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	if (ZEND_NUM_ARGS() > 2) {
		ALLOC_ZVAL(result);
		MAKE_COPY_ZVAL(&initial, result);
	} else {
		MAKE_STD_ZVAL(result);
		ZVAL_NULL(result);
	}

	FUNCTIONAL_PREPARE_CALLBACK(4)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		zend_hash_internal_pointer_end_ex(Z_ARRVAL_P(collection), &pos);
		FUNCTIONAL_ARRAY_ITERATE_BEGIN_EX {
			FUNCTIONAL_ARRAY_PREPARE_KEY
			args[3] = &result;
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				zval_ptr_dtor(&result);
				result = retval_ptr;
				zval_add_ref(&result);
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
			zend_hash_move_backwards_ex(Z_ARRVAL_P(collection), &pos);
		}

		RETURN_ZVAL(result, 1, 1);

	} else {
		zend_llist_init(&reversed, sizeof(zval), NULL, 0);

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			zval_add_ref(args[0]);
			zend_llist_prepend_element(&reversed, &*args[0]);
			zend_llist_prepend_element(&reversed, &*args[1]);
		FUNCTIONAL_ITERATOR_ITERATE_END

		for (args[1] = (zval **)zend_llist_get_first_ex(&reversed, &reverse_pos);
			args[1];
			args[1] = (zval **)zend_llist_get_next_ex(&reversed, &reverse_pos)) {
			args[0] = (zval **)zend_llist_get_next_ex(&reversed, &reverse_pos);

			args[3] = &result;
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				zval_ptr_dtor(&result);
				result = retval_ptr;
				zval_add_ref(&result);
				zval_ptr_dtor(args[0]);
				zval_ptr_dtor(args[1]);
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		}
		zend_llist_clean(&reversed);
		zend_llist_destroy(&reversed);

		FUNCTIONAL_ITERATOR_DONE
		RETURN_ZVAL(result, 1, 1);
	}
}

PHP_FUNCTION(functional_first)
{
	FUNCTIONAL_DECLARE_FCI(3)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|f!", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS

	if (ZEND_FCI_INITIALIZED(fci)) {
		FUNCTIONAL_PREPARE_CALLBACK(3)
	}

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (!ZEND_FCI_INITIALIZED(fci)) {
				RETURN_ZVAL(*args[0], 1, 0);
			}
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					FUNCTIONAL_ARRAY_FREE_KEY
					RETURN_ZVAL(*args[0], 1, 0);
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (!ZEND_FCI_INITIALIZED(fci)) {
				RETVAL_ZVAL(*args[0], 1, 0);
				goto done;
			}
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					FUNCTIONAL_ITERATOR_FREE_KEY
					RETVAL_ZVAL(*args[0], 1, 0);
					goto done;
				}
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

PHP_FUNCTION(functional_last)
{
	FUNCTIONAL_DECLARE_FCI(3);

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|f!", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)

	if (ZEND_FCI_INITIALIZED(fci)) {
		FUNCTIONAL_PREPARE_ARGS
		FUNCTIONAL_PREPARE_CALLBACK(3)
	}

	RETVAL_NULL();

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (!ZEND_FCI_INITIALIZED(fci)) {
				RETVAL_ZVAL(*args[0], 1, 0);
				zend_hash_move_forward_ex(Z_ARRVAL_P(collection), &pos);
				continue;
			}
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETVAL_ZVAL(*args[0], 1, 0);
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (!ZEND_FCI_INITIALIZED(fci)) {
				RETVAL_ZVAL(*args[0], 1, 0);
				iter->funcs->move_forward(iter TSRMLS_CC);
				if (EG(exception)) {
					goto done;
				}
				continue;
			}
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					RETVAL_ZVAL(*args[0], 1, 0);
				}
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}

}

PHP_FUNCTION(functional_tail)
{
	int is_head = 1;
	FUNCTIONAL_DECLARE_FCI(3);

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|f!", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)

	if (ZEND_FCI_INITIALIZED(fci)) {
		FUNCTIONAL_PREPARE_ARGS
		FUNCTIONAL_PREPARE_CALLBACK(3)
	}

	array_init(return_value);

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (is_head) {
				is_head = 0;
				zend_hash_move_forward_ex(Z_ARRVAL_P(collection), &pos);
				continue;
			}

			FUNCTIONAL_ARRAY_PREPARE_KEY

			if (!ZEND_FCI_INITIALIZED(fci)) {
				php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				zend_hash_move_forward_ex(Z_ARRVAL_P(collection), &pos);
				continue;
			}

			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (is_head) {
				is_head = 0;
				iter->funcs->move_forward(iter TSRMLS_CC);
				continue;
			}

			FUNCTIONAL_ITERATOR_PREPARE_KEY

			if (!ZEND_FCI_INITIALIZED(fci)) {
				php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				iter->funcs->move_forward(iter TSRMLS_CC);
				if (EG(exception)) {
					goto done;
				}
				continue;
			}

			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (zend_is_true(retval_ptr)) {
					php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_drop_first)
{
	FUNCTIONAL_DECLARE_FCI(3)
	int drop = 1;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	array_init(return_value);

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			if (drop) {
				FUNCTIONAL_CALL_BACK_EX_BEGIN
					if (!zend_is_true(retval_ptr)) {
						drop = 0;
					} else {
						FUNCTIONAL_ARRAY_FREE_KEY
						zval_ptr_dtor(&retval_ptr);
						zend_hash_move_forward_ex(Z_ARRVAL_P(collection), &pos);
						continue;
					}
				FUNCTIONAL_ARRAY_CALL_BACK_EX_END
			}
			FUNCTIONAL_ARRAY_FREE_KEY
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			if (drop) {
				FUNCTIONAL_CALL_BACK_EX_BEGIN
					if (!zend_is_true(retval_ptr)) {
						drop = 0;
					} else {
						FUNCTIONAL_ITERATOR_FREE_KEY
						zval_ptr_dtor(&retval_ptr);
						iter->funcs->move_forward(iter TSRMLS_CC);
						if (EG(exception)) {
							goto done;
						}
						continue;
					}
				FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
			}
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_drop_last)
{
	FUNCTIONAL_DECLARE_FCI(3);

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	array_init(return_value);

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					FUNCTIONAL_ARRAY_FREE_KEY
					zval_ptr_dtor(&retval_ptr);
					break;
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (!zend_is_true(retval_ptr)) {
					FUNCTIONAL_ARRAY_FREE_KEY
					zval_ptr_dtor(&retval_ptr);
					goto done;
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
			php_functional_append_array_value(hash_key_type, &return_value, args[0], string_key, string_key_len, int_key);
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

PHP_FUNCTION(functional_group)
{
	FUNCTIONAL_DECLARE_FCI(3)
	zval *group;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	array_init(return_value);

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (php_functional_prepare_group(retval_ptr, &return_value, &group TSRMLS_CC) == SUCCESS) {
					php_functional_append_array_value(hash_key_type, &group, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				if (php_functional_prepare_group(retval_ptr, &return_value, &group TSRMLS_CC) == SUCCESS) {
					php_functional_append_array_value(hash_key_type, &group, args[0], string_key, string_key_len, int_key);
				}
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}

}

int php_functional_prepare_group(const zval *retval_ptr, zval **return_value, zval **group_ptr TSRMLS_DC)
{
	zval *group, **gptr;
	long index, key_len;
	int type, res;
	char *key, *type_name;

	switch (Z_TYPE_P(retval_ptr)) {
		case IS_NULL:
			key = "";
			key_len = 1;
			type = HASH_KEY_IS_STRING;
			res = SUCCESS;
			break;

		case IS_STRING:
			key = Z_STRVAL_P(retval_ptr);
			key_len = Z_STRLEN_P(retval_ptr) + 1;
			type = HASH_KEY_IS_STRING;
			res = SUCCESS;
			break;

		case IS_DOUBLE:
			index = Z_DVAL_P(retval_ptr);
			type = HASH_KEY_IS_LONG;
			res = SUCCESS;
			break;

		case IS_LONG:
		case IS_BOOL:
			index = Z_LVAL_P(retval_ptr);
			type = HASH_KEY_IS_LONG;
			res = SUCCESS;
			break;

		case IS_OBJECT:
			type_name = "object";
			res = FAILURE;
			break;

		case IS_RESOURCE:
			type_name = "resource";
			res = FAILURE;
			break;

		case IS_ARRAY:
			type_name = "array";
			res = FAILURE;
			break;

		default:
			type_name = "unknown";
			res = FAILURE;
			break;
	}

	if (res == FAILURE) {
		php_error_docref(NULL TSRMLS_CC, E_WARNING,
				"callback returned invalid array key of type \"%s\". Expected NULL, string, integer, double or boolean", type_name);
		return FAILURE;
	}

	if (type == HASH_KEY_IS_LONG) {
		if (zend_hash_index_find(Z_ARRVAL_PP(return_value), index, (void **)&gptr) == FAILURE) {
			MAKE_STD_ZVAL(group);
			array_init(group);
			zend_hash_index_update(Z_ARRVAL_PP(return_value), index, &group, sizeof(zval *), NULL);
			*group_ptr = group;
		} else {
			*group_ptr = *gptr;
		}
	} else {
		if (zend_hash_find(Z_ARRVAL_PP(return_value), key, key_len, (void **)&gptr) == FAILURE) {
			MAKE_STD_ZVAL(group);
			array_init(group);
			zend_hash_update(Z_ARRVAL_PP(return_value), key, key_len, &group, sizeof(zval *), NULL);
			*group_ptr = group;
		} else {
			*group_ptr = *gptr;
		}
	}
	return SUCCESS;
}

PHP_FUNCTION(functional_partition)
{
	FUNCTIONAL_DECLARE_FCI(3)
	zval *left, *right;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zf", &collection, &fci, &fci_cache) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS
	FUNCTIONAL_PREPARE_CALLBACK(3)

	array_init(return_value);
	MAKE_STD_ZVAL(left);
	array_init(left);
	MAKE_STD_ZVAL(right);
	array_init(right);
	zend_hash_index_update(Z_ARRVAL_P(return_value), 0, &left, sizeof(zval *), NULL);
	zend_hash_index_update(Z_ARRVAL_P(return_value), 1, &right, sizeof(zval *), NULL);

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				php_functional_append_array_value(hash_key_type, zend_is_true(retval_ptr) ? &left : &right, args[0], string_key, string_key_len, int_key);
			FUNCTIONAL_ARRAY_FREE_KEY
			FUNCTIONAL_ARRAY_CALL_BACK_EX_END
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			FUNCTIONAL_CALL_BACK_EX_BEGIN
				php_functional_append_array_value(hash_key_type, zend_is_true(retval_ptr) ? &left : &right, args[0], string_key, string_key_len, int_key);
			FUNCTIONAL_ITERATOR_CALL_BACK_EX_END
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

PHP_FUNCTION(functional_flatten)
{
	zval *collection;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z", &collection) == FAILURE) {
		RETURN_NULL();
	}
	FUNCTIONAL_COLLECTION_PARAM(collection)

	array_init(return_value);
	php_functional_flatten(collection, &return_value TSRMLS_CC);
}

void php_functional_flatten(zval *collection, zval **return_value TSRMLS_DC)
{
	zval **args[1];
	HashPosition pos;
	zend_object_iterator *iter;
	zend_class_entry *ce;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (FUNCTIONAL_NOT_ITERABLE(*args[0])) {
				zval_add_ref(args[0]);
				zend_hash_next_index_insert(Z_ARRVAL_PP(return_value), (void *)args[0], sizeof(zval *), NULL);
			} else {
				php_functional_flatten(*args[0], return_value TSRMLS_CC);
			}
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (FUNCTIONAL_NOT_ITERABLE(*args[0])) {
				zval_add_ref(args[0]);
				zend_hash_next_index_insert(Z_ARRVAL_PP(return_value), (void *)args[0], sizeof(zval *), NULL);
			} else {
				php_functional_flatten(*args[0], return_value TSRMLS_CC);
			}
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}
}

PHP_FUNCTION(functional_average)
{
	FUNCTIONAL_DECLARE(1)
	zval el, *initial = NULL;
	double dval;
	long lval;
	int type = 0, divisor = 0;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|z", &collection, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_MATH_EX(+, 0, (divisor++))

	if (divisor > 0) {
		if (Z_TYPE_P(return_value) == IS_LONG) {
			dval = (double)Z_LVAL_P(return_value) / (double)divisor;
			if ((double)(long)dval == dval && dval >= (double)LONG_MIN && dval <= (double)LONG_MAX) { \
				Z_LVAL_P(return_value) = (long)dval; \
			} else { \
				convert_to_double(return_value);
				Z_DVAL_P(return_value) = dval;
			}
		} else {
			Z_DVAL_P(return_value) /= (double)divisor;
		}
	} else {
		RETVAL_NULL();
	}
}

PHP_FUNCTION(functional_sum)
{
	FUNCTIONAL_DECLARE(1)
	zval el, *initial = NULL;
	double dval;
	long lval;
	int type = 0;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|z", &collection, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_MATH(+, 0)
}

PHP_FUNCTION(functional_difference)
{
	FUNCTIONAL_DECLARE(1)
	zval el, *initial = NULL;
	double dval;
	long lval;
	int type = 0;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|z", &collection, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_MATH(-, 0)
}

PHP_FUNCTION(functional_product)
{
	FUNCTIONAL_DECLARE(1)
	zval el, *initial = NULL;
	double dval;
	long lval;
	int type = 0;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|z", &collection, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_MATH(*, 1)
}

PHP_FUNCTION(functional_ratio)
{
	FUNCTIONAL_DECLARE(1)
	zval el, *initial = NULL;
	double dval;
	long lval;
	int type = 0;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|z", &collection, &initial) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_MATH(/, 1)
}

static int php_functional_is_equal(zval *value, zval **entry, int strict TSRMLS_DC)
{
	int (*is_equal_func)(zval *, zval *, zval * TSRMLS_DC) = is_equal_function;
	zval res;

	if (strict == 1) {
		is_equal_func = is_identical_function;
	}

	is_equal_func(&res, value, *entry TSRMLS_CC);

	if (Z_LVAL(res)) {
		return 1;
	} else {
		return 0;
	}
}

static int php_functional_in_array(zval *array, zval *value, int strict TSRMLS_DC)
{
	HashPosition pos;
	zval **entry;

	zend_hash_internal_pointer_reset_ex(Z_ARRVAL_P(array), &pos);

	while (zend_hash_get_current_data_ex(Z_ARRVAL_P(array), (void **)&entry, &pos) == SUCCESS) {
		if (php_functional_is_equal(value, entry, strict TSRMLS_CC)) {
			return 1;
		}

		zend_hash_move_forward_ex(Z_ARRVAL_P(array), &pos);
	}

	return 0;
}

PHP_FUNCTION(functional_unique)
{
	int strict = 1;
	zval *indexes = NULL;
	FUNCTIONAL_DECLARE_FCI(3);

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z|f!b", &collection, &fci, &fci_cache, &strict) == FAILURE) {
		RETURN_NULL();
	}

	array_init(return_value);

	FUNCTIONAL_COLLECTION_PARAM(collection)
	FUNCTIONAL_PREPARE_ARGS

	if (ZEND_FCI_INITIALIZED(fci)) {
		FUNCTIONAL_PREPARE_CALLBACK(3)
	}

	MAKE_STD_ZVAL(indexes);
	array_init(indexes);

	/* if callback given, unify based on callback return value, otherwise on current array/iterator value */
	if (Z_TYPE_P(collection) == IS_ARRAY) {
		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
				FUNCTIONAL_UNIQUE_INNER(FUNCTIONAL_ARRAY_CALL_BACK_EX_END)
			FUNCTIONAL_ARRAY_FREE_KEY
		FUNCTIONAL_ARRAY_ITERATE_END
	} else {
		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
				FUNCTIONAL_UNIQUE_INNER(FUNCTIONAL_ITERATOR_CALL_BACK_EX_END)
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE
	}

	zval_ptr_dtor(&indexes);
}


PHP_FUNCTION(functional_maximum)
{
	FUNCTIONAL_DECLARE(1)
	zval result, *max = NULL;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z", &collection) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (max == NULL || (FUNCTIONAL_IS_NUMERIC_PP(args[0]) && is_smaller_or_equal_function(&result, *args[0], max TSRMLS_CC) == SUCCESS && Z_LVAL(result) == 0)) {
				if (max != NULL) {
					zval_ptr_dtor(&max);
				}
				ALLOC_ZVAL(max);
				MAKE_COPY_ZVAL(args[0], max);
			}
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (max == NULL || (FUNCTIONAL_IS_NUMERIC_PP(args[0]) && is_smaller_or_equal_function(&result, *args[0], max TSRMLS_CC) == SUCCESS && Z_LVAL(result) == 0)) {
				if (max != NULL) {
					zval_ptr_dtor(&max);
				}
				ALLOC_ZVAL(max);
				MAKE_COPY_ZVAL(args[0], max);
			}
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}

	RETVAL_ZVAL(max, 0, 0);
}

PHP_FUNCTION(functional_minimum)
{
	FUNCTIONAL_DECLARE(1)
	zval result, *min = NULL;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z", &collection) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (min == NULL || (FUNCTIONAL_IS_NUMERIC_PP(args[0]) && is_smaller_function(&result, *args[0], min TSRMLS_CC) == SUCCESS && Z_LVAL(result) == 1)) {
				if (min != NULL) {
					zval_ptr_dtor(&min);
				}
				ALLOC_ZVAL(min);
				MAKE_COPY_ZVAL(args[0], min);
			}
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (min == NULL || (FUNCTIONAL_IS_NUMERIC_PP(args[0]) && is_smaller_function(&result, *args[0], min TSRMLS_CC) == SUCCESS && Z_LVAL(result) == 1)) {
				if (min != NULL) {
					zval_ptr_dtor(&min);
				}
				ALLOC_ZVAL(min);
				MAKE_COPY_ZVAL(args[0], min);
			}
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}

	RETVAL_ZVAL(min, 0, 0);
}

PHP_FUNCTION(functional_first_index_of)
{
	FUNCTIONAL_DECLARE(2)
	zval *value, result, *key = NULL;
	uint string_key_len, hash_key_type;
	ulong int_key;
	char *string_key = NULL;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zz", &collection, &value) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)

	RETVAL_FALSE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			if (is_identical_function(&result, value, &**args[0] TSRMLS_CC) == SUCCESS && Z_LVAL(result) == 1) {
				RETVAL_ZVAL(key, 1, 0);
				FUNCTIONAL_ARRAY_FREE_KEY
				break;
			}
			FUNCTIONAL_ARRAY_FREE_KEY
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			if (is_identical_function(&result, value, &**args[0] TSRMLS_CC) == SUCCESS && Z_LVAL(result) == 1) {
				RETVAL_ZVAL(key, 1, 0);
				FUNCTIONAL_ITERATOR_FREE_KEY
				goto done;
			}
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_last_index_of)
{
	FUNCTIONAL_DECLARE(2)
	zval *value, result, *key = NULL;
	uint string_key_len, hash_key_type;
	ulong int_key;
	char *string_key = NULL;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zz", &collection, &value) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)

	RETVAL_FALSE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY
			if (is_identical_function(&result, value, &**args[0] TSRMLS_CC) == SUCCESS && Z_LVAL(result) == 1) {
				RETVAL_ZVAL(key, 1, 0);
			}
			FUNCTIONAL_ARRAY_FREE_KEY
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY
			if (is_identical_function(&result, value, &**args[0] TSRMLS_CC) == SUCCESS && Z_LVAL(result) == 1) {
				RETVAL_ZVAL(key, 1, 0);
			}
			FUNCTIONAL_ITERATOR_FREE_KEY
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_true)
{
	FUNCTIONAL_DECLARE(1)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z", &collection) == FAILURE) {
		RETURN_NULL();
	}
	FUNCTIONAL_COLLECTION_PARAM(collection)

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (Z_TYPE_PP(args[0]) != IS_BOOL || Z_LVAL_PP(args[0]) != 1) {
				RETURN_FALSE;
			}
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (Z_TYPE_PP(args[0]) != IS_BOOL || Z_LVAL_PP(args[0]) != 1) {
				RETVAL_FALSE;
				goto done;
			}
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_false)
{
	FUNCTIONAL_DECLARE(1)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z", &collection) == FAILURE) {
		RETURN_NULL();
	}
	FUNCTIONAL_COLLECTION_PARAM(collection)

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (Z_TYPE_PP(args[0]) != IS_BOOL || Z_LVAL_PP(args[0]) != 0) {
				RETURN_FALSE;
			}
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (Z_TYPE_PP(args[0]) != IS_BOOL || Z_LVAL_PP(args[0]) != 0) {
				RETVAL_FALSE;
				goto done;
			}
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_truthy)
{
	FUNCTIONAL_DECLARE(1)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z", &collection) == FAILURE) {
		RETURN_NULL();
	}
	FUNCTIONAL_COLLECTION_PARAM(collection)

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (!zend_is_true(*args[0])) {
				RETURN_FALSE;
			}
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (!zend_is_true(*args[0])) {
				RETVAL_FALSE;
				goto done;
			}
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_falsy)
{
	FUNCTIONAL_DECLARE(1)

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "z", &collection) == FAILURE) {
		RETURN_NULL();
	}
	FUNCTIONAL_COLLECTION_PARAM(collection)

	RETVAL_TRUE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (zend_is_true(*args[0])) {
				RETURN_FALSE;
			}
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (zend_is_true(*args[0])) {
				RETVAL_FALSE;
				goto done;
			}
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

PHP_FUNCTION(functional_contains)
{
	FUNCTIONAL_DECLARE(1)
	int strict = 1;
	zval *value;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zz|b", &collection, &value, &strict) == FAILURE) {
		RETURN_NULL();
	}
	FUNCTIONAL_COLLECTION_PARAM(collection)

	RETVAL_FALSE;

	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			if (php_functional_is_equal(value, args[0], strict TSRMLS_CC)) {
				RETURN_TRUE;
			}
		FUNCTIONAL_ARRAY_ITERATE_END

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			if (php_functional_is_equal(value, args[0], strict TSRMLS_CC)) {
				RETVAL_TRUE;
				goto done;
			}
		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}
}

static void php_functional_invoke(INTERNAL_FUNCTION_PARAMETERS, int strategy)
{
	FUNCTIONAL_DECLARE_EX(3)
	FUNCTIONAL_DECLARE_FCALL_INFO_CACHE

	int arguments_len = 0, method_name_len, element = 0;
	zval *method, ***method_args = NULL;
	HashTable *arguments = NULL;
	char *callable, *error, *method_name;

	int last_invokable_callback_found = 0;
	zval **last_invokable_obj;
	zval *last_invokable_method;
	int last_invokable_arguments_len;
	zval ***last_invokable_method_args;


	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zs|H", &collection, &method_name, &method_name_len, &arguments) == FAILURE) {
		RETURN_NULL();
	}

	FUNCTIONAL_COLLECTION_PARAM(collection)

	MAKE_STD_ZVAL(method);
	ZVAL_STRINGL(method, method_name, method_name_len, 0);

	if (arguments) {
		arguments_len = zend_hash_num_elements(arguments);
		method_args = (zval ***) safe_emalloc(sizeof(zval **), arguments_len, 0);
		zend_hash_internal_pointer_reset(arguments);
		while (element < arguments_len && zend_hash_get_current_data(arguments, (void **) &(method_args[element])) == SUCCESS) {
			zend_hash_move_forward(arguments);
			element++;
		}
	}

	/* we only need to return array of callback results when Functional\invoke is used */
	if (strategy == FUNCTIONAL_INVOKE_STRATEGY_ALL) {
		array_init(return_value);
	}


	if (Z_TYPE_P(collection) == IS_ARRAY) {

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY

			if (strategy == FUNCTIONAL_INVOKE_STRATEGY_FIRST) {
				FUNCTIONAL_INVOKE_FIRST_INNER(break)
			} else if (strategy == FUNCTIONAL_INVOKE_STRATEGY_LAST) {
				FUNCTIONAL_INVOKE_LAST_INNER
			} else {
				FUNCTIONAL_INVOKE_INNER(break)
			}

			FUNCTIONAL_ARRAY_FREE_KEY
		FUNCTIONAL_ARRAY_ITERATE_END

		if (strategy == FUNCTIONAL_INVOKE_STRATEGY_LAST) {
			FUNCTIONAL_INVOKE_LAST(goto cleanup)
		}

	} else {

		FUNCTIONAL_ITERATOR_PREPARE
		FUNCTIONAL_ITERATOR_ITERATE_BEGIN
			FUNCTIONAL_ITERATOR_PREPARE_KEY

			if (strategy == FUNCTIONAL_INVOKE_STRATEGY_FIRST) {
				FUNCTIONAL_INVOKE_FIRST_INNER(goto done)
			} else if (strategy == FUNCTIONAL_INVOKE_STRATEGY_LAST) {
				FUNCTIONAL_INVOKE_LAST_INNER
			} else {
				FUNCTIONAL_INVOKE_INNER(goto done)
			}

			FUNCTIONAL_ITERATOR_FREE_KEY

		if (strategy == FUNCTIONAL_INVOKE_STRATEGY_LAST) {
			FUNCTIONAL_INVOKE_LAST(goto done)
		}

		FUNCTIONAL_ITERATOR_ITERATE_END
		FUNCTIONAL_ITERATOR_DONE

	}


	cleanup:

	efree(method);
	if (method_args) {
		efree(method_args);
	}
}

PHP_FUNCTION(functional_invoke)
{
	php_functional_invoke(INTERNAL_FUNCTION_PARAM_PASSTHRU, FUNCTIONAL_INVOKE_STRATEGY_ALL);
}

PHP_FUNCTION(functional_invoke_first)
{
	php_functional_invoke(INTERNAL_FUNCTION_PARAM_PASSTHRU, FUNCTIONAL_INVOKE_STRATEGY_FIRST);
}

PHP_FUNCTION(functional_invoke_last)
{
	php_functional_invoke(INTERNAL_FUNCTION_PARAM_PASSTHRU, FUNCTIONAL_INVOKE_STRATEGY_LAST);
}

PHP_FUNCTION(functional_zip)
{
	zval ***collections = NULL, *array, *null, ***callback_args;
	int argc, a, value_found;
	zval **arrays;
	FUNCTIONAL_DECLARE_FCI(3);


	if (zend_parse_parameters_ex(ZEND_PARSE_PARAMS_QUIET, ZEND_NUM_ARGS() TSRMLS_CC, "+|f!", &collections, &argc, &fci, &fci_cache) == FAILURE) {
		if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "+", &collections, &argc) == FAILURE) {
			RETURN_NULL();
		}
	}

	arrays = emalloc(argc * sizeof(zval *));

	for (a = 0; a < argc; a++) {
		FUNCTIONAL_COLLECTION_PARAM_EX(*collections[a], a + 1);
	}

	for (a = 0; a < argc; a++) {
		collection = *collections[a];
		if (Z_TYPE_P(collection) == IS_ARRAY) {
			arrays[a] = collection;
		} else {
			/** Transform iterators to arrays */
			MAKE_STD_ZVAL(arrays[a]);
			array_init(arrays[a]);
			FUNCTIONAL_ITERATOR_PREPARE
			FUNCTIONAL_ITERATOR_ITERATE_BEGIN
				FUNCTIONAL_ITERATOR_PREPARE_KEY
				php_functional_append_array_value(hash_key_type, &arrays[a], args[0], string_key, string_key_len, int_key);
				FUNCTIONAL_ITERATOR_FREE_KEY
			FUNCTIONAL_ITERATOR_ITERATE_END
			FUNCTIONAL_ITERATOR_DONE
		}
	}

	array_init(return_value);
	callback_args = (zval ***)safe_emalloc(argc, sizeof(zval **), 0);
	if (ZEND_FCI_INITIALIZED(fci)) {
		fci.no_separation = 0;
		fci.retval_ptr_ptr = &retval_ptr;
		fci.param_count = argc;
		fci.params = callback_args;
	}

	if (argc > 0) {
		collection = arrays[0];

		MAKE_STD_ZVAL(null);
		ZVAL_NULL(null);

		FUNCTIONAL_ARRAY_PREPARE
		FUNCTIONAL_ARRAY_ITERATE_BEGIN
			FUNCTIONAL_ARRAY_PREPARE_KEY

			if (ZEND_FCI_INITIALIZED(fci)) {
				callback_args[0] = args[0];
			} else {
				MAKE_STD_ZVAL(array);
				array_init(array);
				zval_add_ref(args[0]);
				add_next_index_zval(array, *args[0]);
			}

			for (a = 1; a < argc; a++) {

				if (hash_key_type == HASH_KEY_IS_LONG) {
					value_found = zend_hash_index_find(Z_ARRVAL_P(arrays[a]), int_key, (void **)&callback_args[a]);
				} else {
					value_found = zend_hash_find(Z_ARRVAL_P(arrays[a]), string_key, string_key_len, (void **)&callback_args[a]);
				}

				if (value_found == FAILURE) {
					callback_args[a] = &null;
				}

				if (!ZEND_FCI_INITIALIZED(fci)) {
					zval_add_ref(callback_args[a]);
					add_next_index_zval(array, *callback_args[a]);
				}
			}

			if (ZEND_FCI_INITIALIZED(fci)) {
				if (FUNCTIONAL_CALL_BACK_CALL && retval_ptr) {
					add_next_index_zval(return_value, retval_ptr);
				} else {
					goto cleanup;
				}
			} else {
				add_next_index_zval(return_value, array);
			}

			FUNCTIONAL_ARRAY_FREE_KEY
		FUNCTIONAL_ARRAY_ITERATE_END

	}

	cleanup:
		for (a = 0; a < argc; a++) {
			zval_ptr_dtor(&arrays[a]);
		}
		efree(collections);
		efree(callback_args);
		efree(arrays);
		if (null) {
			zval_ptr_dtor(&null);
		}
}

PHP_FUNCTION(functional_invoke_if)
{
	zval *object, *value = NULL, *method, ***method_args = NULL, *retval_ptr;
	HashTable *arguments = NULL;
	char *method_name;
	int method_name_len, arguments_len = 0, element = 0;

	if (zend_parse_parameters(ZEND_NUM_ARGS() TSRMLS_CC, "zs|Hz", &object, &method_name, &method_name_len, &arguments, &value) == FAILURE) {
		RETURN_NULL();
	}

	if (Z_TYPE_P(object) != IS_OBJECT) {
		RETURN_NULL();
	}

	MAKE_STD_ZVAL(method);
	ZVAL_STRINGL(method, method_name, method_name_len, 0);

	if (arguments) {
		arguments_len = zend_hash_num_elements(arguments);
		method_args = (zval ***) safe_emalloc(sizeof(zval **), arguments_len, 0);
		zend_hash_internal_pointer_reset(arguments);
		while (element < arguments_len && zend_hash_get_current_data(arguments, (void **) &(method_args[element])) == SUCCESS) {
			zend_hash_move_forward(arguments);
			element++;
		}
	}

	if (value) {
		RETVAL_ZVAL(value, 1, 0);
	} else {
		RETVAL_NULL();
	}

	if (call_user_function_ex(EG(function_table), &object, method, &retval_ptr, arguments_len, method_args, 1, NULL TSRMLS_CC) == SUCCESS) {
		if (!EG(exception)) {
			RETVAL_ZVAL(retval_ptr, 1, 0);
		}
	}

	efree(method);
	if (method_args) {
		efree(method_args);
	}

}
