PHP_ARG_ENABLE(functional, enable functional PHP extension,
[  --enable-functional Enable Functional PHP])

if test "$PHP_FUNCTIONAL" != "no"; then
  PHP_SUBST(FUNCTIONAL_SHARED_LIBADD)

  PHP_NEW_EXTENSION(functional, functional.c, $ext_shared)
  CFLAGS="$CFLAGS -Wall -g -O0 -pedantic"
fi
