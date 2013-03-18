<?php

class __344240c67f9f88fe25b9fe9c6e6ac46d extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'error' section
        $buffer .= $this->section4e118076e313f3eb689eb60a6f27d6eb($context, $indent, $context->find('error'));
        $buffer .= "\n";
        $buffer .= $indent . '<form action="" method="post" class="form-horizontal">';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="inputUsername">Nom d\'utilisateur</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="text" name="login-username" id="inputUsername" placeholder="Nom d\'utilisateur" required>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="inputPassword">Mot de passe</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="password" name="login-password" id="inputPassword" placeholder="Mot de passe" required>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="form-actions">';
        $buffer .= "\n";
        $buffer .= $indent . '		<button type="submit" class="btn btn-primary">Valider</button>';
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

    private function section4e118076e313f3eb689eb60a6f27d6eb(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-block alert-error">
		<button class="close" data-dismiss="alert" type="button">&times;</button>
		<h4 class="alert-heading">Erreur</h4>
		<p>Une erreur est survenue lors de la connexion : {{error}}.</p>
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
                $buffer .= $indent . '		<button class="close" data-dismiss="alert" type="button">&times;</button>';
                $buffer .= "\n";
                $buffer .= $indent . '		<h4 class="alert-heading">Erreur</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>Une erreur est survenue lors de la connexion : ';
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