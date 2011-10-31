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
#ifndef PHP_FUNCTIONAL_H
#define PHP_FUNCTIONAL_H
#define FUNCTIONAL_VERSION "0.0.1"

#ifdef HAVE_CONFIG_H
#include "config.h"
#endif
#include "php.h"

extern zend_module_entry functional_module_entry;
#define phpext_functional_ptr &functional_module_entry

PHP_MINIT_FUNCTION(functional);
PHP_MSHUTDOWN_FUNCTION(functional);
PHP_MINFO_FUNCTION(functional);

void php_functional_prepare_array_key(int hash_key_type, zval **key, zval ***value, char *string_key, uint string_key_len, int num_key);
void php_functional_append_array_value(int hash_key_type, zval **return_value, zval **value, char *string_key, uint string_key_len, int int_key);
void php_functional_flatten(zval *collection, zval **return_value TSRMLS_DC);
int php_functional_prepare_group(const zval *retval_ptr, zval **return_value, zval **group_ptr TSRMLS_DC);

PHP_FUNCTION(functional_every);
PHP_FUNCTION(functional_some);
PHP_FUNCTION(functional_drop_first);
PHP_FUNCTION(functional_drop_last);
PHP_FUNCTION(functional_first);
PHP_FUNCTION(functional_group);
PHP_FUNCTION(functional_last);
PHP_FUNCTION(functional_each);
PHP_FUNCTION(functional_invoke);
PHP_FUNCTION(functional_map);
PHP_FUNCTION(functional_none);
PHP_FUNCTION(functional_pluck);
PHP_FUNCTION(functional_reduce_left);
PHP_FUNCTION(functional_reduce_right);
PHP_FUNCTION(functional_reject);
PHP_FUNCTION(functional_select);
PHP_FUNCTION(functional_partition);
PHP_FUNCTION(functional_flatten);
PHP_FUNCTION(functional_sum);
PHP_FUNCTION(functional_difference);
PHP_FUNCTION(functional_product);
PHP_FUNCTION(functional_ratio);

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
