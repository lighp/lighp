<?php

class __98541d5fb2412b51c32769985895b5a2 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<form action="" method="get" class="form-horizontal form-search">';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="input-append">';
        $buffer .= "\n";
        $buffer .= $indent . '		<input type="search" class="search-query input-xxlarge" name="q" placeholder="Entrez votre recherche..." value="';
        $value = $context->find('searchQuery');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" required/>';
        $buffer .= "\n";
        $buffer .= $indent . '		<button class="btn" type="submit"><i class="icon-search"></i></button>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<select id="package-repo" name="repo">';
        $buffer .= "\n";
        $buffer .= $indent . '		<option value="">Tous les dépôts disponibles</option>';
        $buffer .= "\n";
        // 'repositories' section
        $buffer .= $this->section22abde17e561344d8c98fe77bd7b20f9($context, $indent, $context->find('repositories'));
        $buffer .= $indent . '	</select>';
        $buffer .= "\n";
        $buffer .= $indent . '</form>';
        $buffer .= "\n";
        $buffer .= "\n";
        // 'emptyQuery?' section
        $buffer .= $this->section9b1f49295d11cde56609a6be7884b2fe($context, $indent, $context->find('emptyQuery?'));
        $buffer .= "\n";
        // 'searched?' section
        $buffer .= $this->section2cede75a1ec4fe49aa1e77a189bd44a9($context, $indent, $context->find('searched?'));

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function section2feb874b1badc97db0e7f0181abc7a03(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = ' selected="selected"';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= ' selected="selected"';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section22abde17e561344d8c98fe77bd7b20f9(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<option value="{{name}}"{{#selected?}} selected="selected"{{/selected?}}>{{name}}</option>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<option value="';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '"';
                // 'selected?' section
                $buffer .= $this->section2feb874b1badc97db0e7f0181abc7a03($context, $indent, $context->find('selected?'));
                $buffer .= '>';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</option>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9b1f49295d11cde56609a6be7884b2fe(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-warning">
		<h4 class="alert-heading">Recherche vide</h4>
		<p>Vous n\'avez entr&eacute; aucune recherche.</p>
	</div>
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '	<div class="alert alert-warning">';
                $buffer .= "\n";
                $buffer .= $indent . '		<h4 class="alert-heading">Recherche vide</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>Vous n\'avez entr&eacute; aucune recherche.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '	</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section64e8950c017ff9ade60243b6d8751f29(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
				{{> partials/table-package}}
			';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                if ($partial = $this->mustache->loadPartial('partials/table-package')) {
                    $buffer .= $partial->renderInternal($context, '				');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE17bf5f1f510ba68b668bbac40e8b2fb(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<table class="table">
		<thead>
			<tr>
				<th>Nom</th>
				<th>Version</th>
				<th>License</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{{#packages}}
				{{> partials/table-package}}
			{{/packages}}
		</tbody>
	</table>
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '	<table class="table">';
                $buffer .= "\n";
                $buffer .= $indent . '		<thead>';
                $buffer .= "\n";
                $buffer .= $indent . '			<tr>';
                $buffer .= "\n";
                $buffer .= $indent . '				<th>Nom</th>';
                $buffer .= "\n";
                $buffer .= $indent . '				<th>Version</th>';
                $buffer .= "\n";
                $buffer .= $indent . '				<th>License</th>';
                $buffer .= "\n";
                $buffer .= $indent . '				<th></th>';
                $buffer .= "\n";
                $buffer .= $indent . '			</tr>';
                $buffer .= "\n";
                $buffer .= $indent . '		</thead>';
                $buffer .= "\n";
                $buffer .= $indent . '		<tbody>';
                $buffer .= "\n";
                // 'packages' section
                $buffer .= $this->section64e8950c017ff9ade60243b6d8751f29($context, $indent, $context->find('packages'));
                $buffer .= $indent . '		</tbody>';
                $buffer .= "\n";
                $buffer .= $indent . '	</table>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section2cede75a1ec4fe49aa1e77a189bd44a9(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	{{#packages?}}
	<table class="table">
		<thead>
			<tr>
				<th>Nom</th>
				<th>Version</th>
				<th>License</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{{#packages}}
				{{> partials/table-package}}
			{{/packages}}
		</tbody>
	</table>
	{{/packages?}}
	{{^packages?}}
		<p class="alert alert-warning">Aucun paquet ne correspond &agrave; votre recherche.</p>
	{{/packages?}}
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'packages?' section
                $buffer .= $this->sectionE17bf5f1f510ba68b668bbac40e8b2fb($context, $indent, $context->find('packages?'));
                // 'packages?' inverted section
                $value = $context->find('packages?');
                if (empty($value)) {
                    
                    $buffer .= $indent . '		<p class="alert alert-warning">Aucun paquet ne correspond &agrave; votre recherche.</p>';
                    $buffer .= "\n";
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }
}