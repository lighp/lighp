<?php

class __fdc1ece98a0eacf04d99df07a7b467b9 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="container">';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="page-header">';
        $buffer .= "\n";
        $buffer .= $indent . '		<h1>Page introuvable <small>(erreur 404)</small></h1>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="pull-right">';
        $buffer .= "\n";
        $buffer .= $indent . '		<img src="';
        $value = $context->find('WEBSITE_ROOT');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '/img/error/404/dead-link.png" alt=""/>';
        $buffer .= "\n";
        $buffer .= $indent . '		<span class="clearfix"></span>';
        $buffer .= "\n";
        $buffer .= $indent . '		<p class="muted pull-right">Ooops ! You found a dead link.</p>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<p class="lead">La page demand&eacute;e est introuvable.</p>';
        $buffer .= "\n";
        $buffer .= $indent . '</div>';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

}