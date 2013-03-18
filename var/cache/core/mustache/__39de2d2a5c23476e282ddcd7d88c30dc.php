<?php

class __39de2d2a5c23476e282ddcd7d88c30dc extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<tr>';
        $buffer .= "\n";
        $buffer .= $indent . '	<td><strong>';
        $value = $context->find('name');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '</strong></td>';
        $buffer .= "\n";
        $buffer .= $indent . '	<td><a href="';
        $value = $context->find('url');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" title="Acc&eacute;der au d&eacute;p&ocirc;t" target="_blank">';
        $value = $context->find('url');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '</a></td>';
        $buffer .= "\n";
        $buffer .= $indent . '	<td>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="btn-group">';
        $buffer .= "\n";
        $buffer .= $indent . '			<a class="btn btn-danger" href="packagecontrol-repos-remove-';
        $value = $context->find('name');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '.html"><i class="icon-trash icon-white"></i>&nbsp;Supprimer</a>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</td>';
        $buffer .= "\n";
        $buffer .= $indent . '</tr>';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

}