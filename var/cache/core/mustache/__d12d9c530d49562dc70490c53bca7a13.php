<?php

class __d12d9c530d49562dc70490c53bca7a13 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'inserted?' section
        $buffer .= $this->section9369afe3771e33980bcf9fc0ff969b22($context, $indent, $context->find('inserted?'));
        // 'inserted?' inverted section
        $value = $context->find('inserted?');
        if (empty($value)) {
            
            if ($partial = $this->mustache->loadPartial('partials/form-leadingItem')) {
                $buffer .= $partial->renderInternal($context, '	');
            }
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function section9369afe3771e33980bcf9fc0ff969b22(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-block alert-success">
		<h4 class="alert-heading">Item mis en avant</h4>
		<p>L\'item a bien &eacute;t&eacute; mis en avant.</p>
		<p><a class="btn" href="module-portfolio.html">Retour</a></p>
	</div>
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '	<div class="alert alert-block alert-success">';
                $buffer .= "\n";
                $buffer .= $indent . '		<h4 class="alert-heading">Item mis en avant</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>L\'item a bien &eacute;t&eacute; mis en avant.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p><a class="btn" href="module-portfolio.html">Retour</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '	</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}