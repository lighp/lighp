<?php

class __f7eee5f385417923af83216036cc1e7e extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'updated?' section
        $buffer .= $this->section9574e69cf06a67c2b27fc9bba0cb8a7e($context, $indent, $context->find('updated?'));
        // 'updated?' inverted section
        $value = $context->find('updated?');
        if (empty($value)) {
            
            if ($partial = $this->mustache->loadPartial('partials/form-project')) {
                $buffer .= $partial->renderInternal($context, '	');
            }
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function section9574e69cf06a67c2b27fc9bba0cb8a7e(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-block alert-success">
		<h4 class="alert-heading">Projet modifi&eacute;</h4>
		<p>Le projet a bien &eacute;t&eacute; modifi&eacute;.</p>
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
                $buffer .= $indent . '		<h4 class="alert-heading">Projet modifi&eacute;</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>Le projet a bien &eacute;t&eacute; modifi&eacute;.</p>';
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