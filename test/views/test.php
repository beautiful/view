<?php echo isset($a) ? $a : ''; ?>
<?php echo isset($b) ? $b : ''; ?>
<?php echo isset($c) ? $c : ''; ?>
<?php echo isset($view->a) ? $view->a : ''; ?>
<?php echo isset($view) && method_exists($view, 'test') ? $view->test() : '' ?>