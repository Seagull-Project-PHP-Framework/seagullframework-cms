<?php

/**
 * A workaround to be able to test private/protected classes
 *
 * Thanks to Yevgeniy A. Viktorov <wik@osmonitoring.com> for the idea.
 *
 */
class SGL_Finder_ContentProxy extends SGL_Finder_Content
{
   public function __call($function, $args)
   {
       $function = str_replace('protected_', '_', $function);

       return call_user_func_array(array(&$this, $function),  $args);
   }

}
?>