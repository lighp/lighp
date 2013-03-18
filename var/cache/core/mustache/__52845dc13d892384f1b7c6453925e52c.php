<?php

class __52845dc13d892384f1b7c6453925e52c extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<form action="" method="post" enctype="multipart/form-data" class="form-horizontal">';
        $buffer .= "\n";
        // 'error' section
        $buffer .= $this->sectionB94a7d9be16b6b802a3ed303ced49664($context, $indent, $context->find('error'));
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="galleryItem-title">Titre de l\'item</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="text" name="galleryItem-title" id="galleryItem-title" value="';
        $value = $context->findDot('galleryItem.title');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" placeholder="Titre" required>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="galleryItem-category">Type de l\'item</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<select name="galleryItem-category" id="galleryItem-category">';
        $buffer .= "\n";
        $buffer .= $indent . '				<option value="">Détection automatique</option>';
        $buffer .= "\n";
        // 'galleryItemTypes' section
        $buffer .= $this->section466bfb85581e54fd4f81b14ef28f2e90($context, $indent, $context->find('galleryItemTypes'));
        $buffer .= $indent . '			</select>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="galleryItem-source">Source de l\'item</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="text" name="galleryItem-source" id="galleryItem-source" value="';
        $value = $context->findDot('galleryItem.source');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" placeholder="Source" class="input-xxlarge" required><span class="help-block">Entrez une URL ou un chemin vers un fichier</span>';
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

    private function section466bfb85581e54fd4f81b14ef28f2e90(core\mustache\Context $context, $indent, $value) {
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
                $buffer .= $indent . '					<option value="';
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
}