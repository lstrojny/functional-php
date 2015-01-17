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
#ifndef PHP_FUNCTIONAL_H
#define PHP_FUNCTIONAL_H
#define FUNCTIONAL_VERSION "0.7.0-dev"

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
static void php_functional_invoke(INTERNAL_FUNCTION_PARAMETERS, int strategy);

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
PHP_FUNCTION(functional_average);
PHP_FUNCTION(functional_sum);
PHP_FUNCTION(functional_difference);
PHP_FUNCTION(functional_product);
PHP_FUNCTION(functional_ratio);
PHP_FUNCTION(functional_unique);
PHP_FUNCTION(functional_maximum);
PHP_FUNCTION(functional_minimum);
PHP_FUNCTION(functional_first_index_of);
PHP_FUNCTION(functional_last_index_of);
PHP_FUNCTION(functional_true);
PHP_FUNCTION(functional_false);
PHP_FUNCTION(functional_truthy);
PHP_FUNCTION(functional_falsy);
PHP_FUNCTION(functional_contains);
PHP_FUNCTION(functional_invoke_first);
PHP_FUNCTION(functional_invoke_last);
PHP_FUNCTION(functional_zip);
PHP_FUNCTION(functional_tail);
PHP_FUNCTION(functional_invoke_if);

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

#ifndef ZEND_HANDLE_NUMERIC_EX
#define ZEND_HANDLE_NUMERIC_EX(key, length, idx, func) do {                 \
    register const char *tmp = key;                                         \
                                                                            \
    if (*tmp == '-') {                                                      \
        tmp++;                                                              \
    }                                                                       \
    if (*tmp >= '0' && *tmp <= '9') { /* possibly a numeric index */        \
        const char *end = key + length - 1;                                 \
        ulong idx;                                                          \
                                                                            \
        if ((*end != '\0') /* not a null terminated string */               \
         || (*tmp == '0' && length > 2) /* numbers with leading zeros */    \
         || (end - tmp > MAX_LENGTH_OF_LONG - 1) /* number too long */      \
         || (SIZEOF_LONG == 4 &&                                            \
             end - tmp == MAX_LENGTH_OF_LONG - 1 &&                         \
             *tmp > '2')) { /* overflow */                                  \
            break;                                                          \
        }                                                                   \
        idx = (*tmp - '0');                                                 \
        while (++tmp != end && *tmp >= '0' && *tmp <= '9') {                \
            idx = (idx * 10) + (*tmp - '0');                                \
        }                                                                   \
        if (tmp == end) {                                                   \
            if (*key == '-') {                                              \
                if (idx-1 > LONG_MAX) { /* overflow */                      \
                    break;                                                  \
                }                                                           \
                idx = (ulong)(-(long)idx);                                  \
            } else if (idx > LONG_MAX) { /* overflow */                     \
                break;                                                      \
            }                                                               \
            func;                                                           \
        }                                                                   \
    }                                                                       \
} while (0)
#endif
#endif
