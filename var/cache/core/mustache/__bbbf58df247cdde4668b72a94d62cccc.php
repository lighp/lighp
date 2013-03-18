<?php

class __bbbf58df247cdde4668b72a94d62cccc extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'updated?' section
        $buffer .= $this->section2f5a32d4c821deb60b005c5e1a3dc25b($context, $indent, $context->find('updated?'));
        // 'updated?' inverted section
        $value = $context->find('updated?');
        if (empty($value)) {
            
            // 'error' section
            $buffer .= $this->sectionEf515c2394ab139c329536427927cc47($context, $indent, $context->find('error'));
            $buffer .= "\n";
            $buffer .= $indent . '	<form action="" method="post" class="form-horizontal">';
            $buffer .= "\n";
            $buffer .= $indent . '		<div class="control-group">';
            $buffer .= "\n";
            $buffer .= $indent . '			<label class="control-label" for="inputUpdateUsername">Nouveau nom d\'utilisateur</label>';
            $buffer .= "\n";
            $buffer .= $indent . '			<div class="controls">';
            $buffer .= "\n";
            $buffer .= $indent . '				<input type="text" name="login-update-username" id="inputUpdateUsername" placeholder="Nom d\'utilisateur" value="';
            $value = $context->find('username');
            if (!is_string($value) && is_callable($value)) {
                $value = $this->mustache
                    ->loadLambda((string) call_user_func($value))
                    ->renderInternal($context, $indent);
            }
            $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $buffer .= '" required>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '		</div>';
            $buffer .= "\n";
            $buffer .= $indent . '		<div class="control-group">';
            $buffer .= "\n";
            $buffer .= $indent . '			<label class="control-label" for="inputUpdatePassword">Nouveau mot de passe</label>';
            $buffer .= "\n";
            $buffer .= $indent . '			<div class="controls">';
            $buffer .= "\n";
            $buffer .= $indent . '				<input type="password" name="login-update-password" id="inputUpdatePassword" placeholder="Mot de passe"><span class="help-block">Laissez ce champ vide pour ne pas effectuer de changement de mot de passe.</span>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '		</div>';
            $buffer .= "\n";
            $buffer .= "\n";
            $buffer .= $indent . '		<hr />';
            $buffer .= "\n";
            $buffer .= "\n";
            $buffer .= $indent . '		<div class="control-group">';
            $buffer .= "\n";
            $buffer .= $indent . '				<label class="control-label" for="inputPassword">Ancien mot de passe</label>';
            $buffer .= "\n";
            $buffer .= $indent . '				<div class="controls">';
            $buffer .= "\n";
            $buffer .= $indent . '					<input type="password" name="login-password" id="inputPassword" placeholder="Mot de passe" required>';
            $buffer .= "\n";
            $buffer .= $indent . '				</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '		<div class="form-actions">';
            $buffer .= "\n";
            $buffer .= $indent . '			<button type="submit" class="btn btn-primary">Valider</button>';
            $buffer .= "\n";
            $buffer .= $indent . '			<a class="btn" href="module-login.html">Annuler</a>';
            $buffer .= "\n";
            $buffer .= $indent . '		</div>';
            $buffer .= "\n";
            $buffer .= $indent . '	</form>';
            $buffer .= "\n";
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function section2f5a32d4c821deb60b005c5e1a3dc25b(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-block alert-success">
		<h4 class="alert-heading">Identifiants modifi&eacute;s</h4>
		<p>Les identifiants ont bien &eacute;t&eacute; modifi&eacute;s.</p>
		<p><a class="btn" href="module-login.html">Retour</a></p>
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
                $buffer .= $indent . '		<h4 class="alert-heading">Identifiants modifi&eacute;s</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>Les identifiants ont bien &eacute;t&eacute; modifi&eacute;s.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p><a class="btn" href="module-login.html">Retour</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '	</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionEf515c2394ab139c329536427927cc47(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="alert alert-block alert-error">
			<button class="close" data-dismiss="alert" type="button">&times;</button>
			<h4 class="alert-heading">Erreur</h4>
			<p>Une erreur est survenue lors de la modification des identifiants : {{error}}.</p>
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
                $buffer .= $indent . '			<p>Une erreur est survenue lors de la modification des identifiants : ';
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