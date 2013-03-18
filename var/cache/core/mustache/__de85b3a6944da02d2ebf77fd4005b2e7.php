<?php

class __de85b3a6944da02d2ebf77fd4005b2e7 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'deleted?' section
        $buffer .= $this->sectionA9b589a2333635a7b3d4e38db219dc4c($context, $indent, $context->find('deleted?'));
        // 'error' section
        $buffer .= $this->section90d9e9a1450b605d363f37d58dfbfcb9($context, $indent, $context->find('error'));

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionA9b589a2333635a7b3d4e38db219dc4c(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-block alert-success">
		<h4 class="alert-heading">Item supprimé</h4>
		<p>L\'item de la galerie a bien été supprimé.</p>
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
                $buffer .= $indent . '		<h4 class="alert-heading">Item supprimé</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>L\'item de la galerie a bien été supprimé.</p>';
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

    private function section90d9e9a1450b605d363f37d58dfbfcb9(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-block alert-error">
		<h4 class="alert-heading">Erreur</h4>
		<p>Une erreur est survenue lors de la suppression de l\'item : {{error}}.</p>
	</div>
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '	<div class="alert alert-block alert-error">';
                $buffer .= "\n";
                $buffer .= $indent . '		<h4 class="alert-heading">Erreur</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>Une erreur est survenue lors de la suppression de l\'item : ';
                $value = $context->find('error');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '	</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}