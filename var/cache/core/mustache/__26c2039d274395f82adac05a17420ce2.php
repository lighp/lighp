<?php

class __26c2039d274395f82adac05a17420ce2 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= "\n";
        // 'upgraded?' section
        $buffer .= $this->section98059cd0a8948347289947a948352010($context, $indent, $context->find('upgraded?'));
        $buffer .= "\n";
        // 'upgraded?' inverted section
        $value = $context->find('upgraded?');
        if (empty($value)) {
            
            // 'upgrades?' section
            $buffer .= $this->sectionBcaf7e469977db8dd70991e2efafdf0f($context, $indent, $context->find('upgrades?'));
            // 'upgrades?' inverted section
            $value = $context->find('upgrades?');
            if (empty($value)) {
                
                $buffer .= $indent . '		<div class="alert alert-block alert-info">';
                $buffer .= "\n";
                $buffer .= $indent . '			<h4 class="alert-heading">Le système est à jour</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '			<p>Aucune mise à jour n\'est pour l\'instant disponible.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '			<p><a class="btn" href="module-packagecontrol.html">Retour</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '		</div>';
                $buffer .= "\n";
            }
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function section98059cd0a8948347289947a948352010(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-block alert-success">
		<h4 class="alert-heading">Mises &agrave; jour appliqu&eacute;es</h4>
		<p>Les mises &agrave; jour ont bien &eacute;t&eacute; appliqu&eacute;es.</p>
		<p><a class="btn" href="module-packagecontrol.html">Retour</a></p>
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
                $buffer .= $indent . '		<h4 class="alert-heading">Mises &agrave; jour appliqu&eacute;es</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>Les mises &agrave; jour ont bien &eacute;t&eacute; appliqu&eacute;es.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p><a class="btn" href="module-packagecontrol.html">Retour</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '	</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section0cef220b0a5ee0b0e2d0cebf7099cf25(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<div class="alert alert-block alert-error">
				<h4 class="alert-heading">Erreur lors de l\'installation</h4>
				<p>Le paquet n\'a pas p&ucirc; &eacute;t&eacute; install&eacute; : {{error}}</p>
			</div>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<div class="alert alert-block alert-error">';
                $buffer .= "\n";
                $buffer .= $indent . '				<h4 class="alert-heading">Erreur lors de l\'installation</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '				<p>Le paquet n\'a pas p&ucirc; &eacute;t&eacute; install&eacute; : ';
                $value = $context->find('error');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</p>';
                $buffer .= "\n";
                $buffer .= $indent . '			</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section812f622d425727bc50534301616631a0(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
									{{> partials/table-upgrade}}
								';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                if ($partial = $this->mustache->loadPartial('partials/table-upgrade')) {
                    $buffer .= $partial->renderInternal($context, '									');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA3124efddfd955405d2d3087d3013199(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
								{{#metadata}}
									{{> partials/table-upgrade}}
								{{/metadata}}
							';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'metadata' section
                $buffer .= $this->section812f622d425727bc50534301616631a0($context, $indent, $context->find('metadata'));
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionBcaf7e469977db8dd70991e2efafdf0f(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		{{#error}}
			<div class="alert alert-block alert-error">
				<h4 class="alert-heading">Erreur lors de l\'installation</h4>
				<p>Le paquet n\'a pas p&ucirc; &eacute;t&eacute; install&eacute; : {{error}}</p>
			</div>
		{{/error}}
		<form action="" method="post">
			<input type="hidden" name="check" value="1">

			<ul class="nav nav-tabs">
				<li class="active"><a href="#upgrade-info" data-toggle="tab">Informations</a></li>
				<li><a href="#upgrade-packages" data-toggle="tab">Paquets</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="upgrade-info">
					<dl class="dl-horizontal">
						<dt>Paquets</dt>
						<dd>{{upgradesNbr}}</dd>

						<dt>&Agrave; télécharger</dt>
						<dd>{{downloadSize | filesize}}</dd>

						<dt>Une fois installé</dt>
						<dd>{{extractedSize | filesize}}</dd>

						<dt>Taille nette</dt>
						<dd>{{netSize | filesize}}</dd>
					</dl>
				</div>
				<div class="tab-pane" id="upgrade-packages">
					<table class="table">
						<thead>
							<tr>
								<th>Nom</th>
								<th>Version</th>
								<th>License</th>
								<th>Taille à télécharger</th>
								<th>Taille une fois installé</th>
							</tr>
						</thead>
						<tbody>
							{{#upgrades}}
								{{#metadata}}
									{{> partials/table-upgrade}}
								{{/metadata}}
							{{/upgrades}}
						</tbody>
					</table>
				</div>
			</div>

			<div class="form-actions">
				<button type="submit" class="btn btn-primary"><i class="icon-arrow-down icon-white"></i>&nbsp;Mettre à jour</button>
				<a class="btn" href="module-packagecontrol.html">Annuler</a>
			</div>
		</form>
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'error' section
                $buffer .= $this->section0cef220b0a5ee0b0e2d0cebf7099cf25($context, $indent, $context->find('error'));
                $buffer .= $indent . '		<form action="" method="post">';
                $buffer .= "\n";
                $buffer .= $indent . '			<input type="hidden" name="check" value="1">';
                $buffer .= "\n";
                $buffer .= "\n";
                $buffer .= $indent . '			<ul class="nav nav-tabs">';
                $buffer .= "\n";
                $buffer .= $indent . '				<li class="active"><a href="#upgrade-info" data-toggle="tab">Informations</a></li>';
                $buffer .= "\n";
                $buffer .= $indent . '				<li><a href="#upgrade-packages" data-toggle="tab">Paquets</a></li>';
                $buffer .= "\n";
                $buffer .= $indent . '			</ul>';
                $buffer .= "\n";
                $buffer .= $indent . '			<div class="tab-content">';
                $buffer .= "\n";
                $buffer .= $indent . '				<div class="tab-pane active" id="upgrade-info">';
                $buffer .= "\n";
                $buffer .= $indent . '					<dl class="dl-horizontal">';
                $buffer .= "\n";
                $buffer .= $indent . '						<dt>Paquets</dt>';
                $buffer .= "\n";
                $buffer .= $indent . '						<dd>';
                $value = $context->find('upgradesNbr');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</dd>';
                $buffer .= "\n";
                $buffer .= "\n";
                $buffer .= $indent . '						<dt>&Agrave; télécharger</dt>';
                $buffer .= "\n";
                $buffer .= $indent . '						<dd>';
                $value = $context->find('downloadSize');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $filter = $context->find('filesize');
                if (is_string($filter) || !is_callable($filter)) {
                    throw new \UnexpectedValueException('Filter not found: filesize');
                }
                $value = call_user_func($filter, $value);
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</dd>';
                $buffer .= "\n";
                $buffer .= "\n";
                $buffer .= $indent . '						<dt>Une fois installé</dt>';
                $buffer .= "\n";
                $buffer .= $indent . '						<dd>';
                $value = $context->find('extractedSize');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $filter = $context->find('filesize');
                if (is_string($filter) || !is_callable($filter)) {
                    throw new \UnexpectedValueException('Filter not found: filesize');
                }
                $value = call_user_func($filter, $value);
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</dd>';
                $buffer .= "\n";
                $buffer .= "\n";
                $buffer .= $indent . '						<dt>Taille nette</dt>';
                $buffer .= "\n";
                $buffer .= $indent . '						<dd>';
                $value = $context->find('netSize');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $filter = $context->find('filesize');
                if (is_string($filter) || !is_callable($filter)) {
                    throw new \UnexpectedValueException('Filter not found: filesize');
                }
                $value = call_user_func($filter, $value);
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</dd>';
                $buffer .= "\n";
                $buffer .= $indent . '					</dl>';
                $buffer .= "\n";
                $buffer .= $indent . '				</div>';
                $buffer .= "\n";
                $buffer .= $indent . '				<div class="tab-pane" id="upgrade-packages">';
                $buffer .= "\n";
                $buffer .= $indent . '					<table class="table">';
                $buffer .= "\n";
                $buffer .= $indent . '						<thead>';
                $buffer .= "\n";
                $buffer .= $indent . '							<tr>';
                $buffer .= "\n";
                $buffer .= $indent . '								<th>Nom</th>';
                $buffer .= "\n";
                $buffer .= $indent . '								<th>Version</th>';
                $buffer .= "\n";
                $buffer .= $indent . '								<th>License</th>';
                $buffer .= "\n";
                $buffer .= $indent . '								<th>Taille à télécharger</th>';
                $buffer .= "\n";
                $buffer .= $indent . '								<th>Taille une fois installé</th>';
                $buffer .= "\n";
                $buffer .= $indent . '							</tr>';
                $buffer .= "\n";
                $buffer .= $indent . '						</thead>';
                $buffer .= "\n";
                $buffer .= $indent . '						<tbody>';
                $buffer .= "\n";
                // 'upgrades' section
                $buffer .= $this->sectionA3124efddfd955405d2d3087d3013199($context, $indent, $context->find('upgrades'));
                $buffer .= $indent . '						</tbody>';
                $buffer .= "\n";
                $buffer .= $indent . '					</table>';
                $buffer .= "\n";
                $buffer .= $indent . '				</div>';
                $buffer .= "\n";
                $buffer .= $indent . '			</div>';
                $buffer .= "\n";
                $buffer .= "\n";
                $buffer .= $indent . '			<div class="form-actions">';
                $buffer .= "\n";
                $buffer .= $indent . '				<button type="submit" class="btn btn-primary"><i class="icon-arrow-down icon-white"></i>&nbsp;Mettre à jour</button>';
                $buffer .= "\n";
                $buffer .= $indent . '				<a class="btn" href="module-packagecontrol.html">Annuler</a>';
                $buffer .= "\n";
                $buffer .= $indent . '			</div>';
                $buffer .= "\n";
                $buffer .= $indent . '		</form>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}