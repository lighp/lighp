<?php

class __6b928436e01c165fe0ef95578f0e3179 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<form action="" method="post" class="form-horizontal">';
        $buffer .= "\n";
        // 'error' section
        $buffer .= $this->sectionB94a7d9be16b6b802a3ed303ced49664($context, $indent, $context->find('error'));
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="leadingItem-place">Emplacement</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="text" name="leadingItem-place" id="leadingItem-place" value="';
        $value = $context->findDot('leadingItem.place');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" placeholder="Emplacement" pattern="[A-Za-z]+" required><span class="help-inline">(l\'emplacement définit à quel endroit de la page l\'item sera mis en avant, par exemple : <em>index</em>)</span>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="form-actions">';
        $buffer .= "\n";
        $buffer .= $indent . '		<button type="submit" class="btn btn-primary">Valider</button>';
        $buffer .= "\n";
        $buffer .= $indent . '		<a class="btn" href="module-portfolio.html">Annuler</a>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '</form>';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionB94a7d9be16b6b802a3ed303ced49664(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="alert alert-block alert-error">
			<button class="close" data-dismiss="alert" type="button">&times;</button>
			<h4 class="alert-heading">Erreur</h4>
			<p>Une erreur est survenue lors du traitement de la requ&ecirc;te : {{error}}.</p>
		</div>
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '		<div class="alert alert-block alert-error">';
                $buffer .= "\n";
                $buffer .= $indent . '			<button class="close" data-dismiss="alert" type="button">&times;</button>';
                $buffer .= "\n";
                $buffer .= $indent . '			<h4 class="alert-heading">Erreur</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '			<p>Une erreur est survenue lors du traitement de la requ&ecirc;te : ';
                $value = $context->find('error');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '		</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}