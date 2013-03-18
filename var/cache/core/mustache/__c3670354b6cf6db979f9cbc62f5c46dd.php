<?php

class __c3670354b6cf6db979f9cbc62f5c46dd extends core\mustache\Template
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
        $buffer .= $indent . '		<label class="control-label" for="project-name">Nom unique du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="text" name="project-name" id="project-name" value="';
        $value = $context->findDot('project.name');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" placeholder="Nom" pattern="[A-Za-z0-9_.\\-]+" required><span class="help-inline">(seuls les caract&egrave;res alphanum&eacute;riques et les suivants sont accept&eacute;s : &laquo;_, -, . &raquo;)</span>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="project-title">Titre du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="text" name="project-title" id="project-title" value="';
        $value = $context->findDot('project.title');
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
        $buffer .= $indent . '		<label class="control-label" for="project-subtitle">Sous-titre du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="text" name="project-subtitle" id="project-subtitle" value="';
        $value = $context->findDot('project.subtitle');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" placeholder="Sous-titre" class="input-xxlarge">';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="project-category">Cat&eacute;gorie du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<select name="project-category" id="project-category">';
        $buffer .= "\n";
        // 'categories' section
        $buffer .= $this->sectionBe92db1e102f2867807cc45cbf345dfe($context, $indent, $context->find('categories'));
        $buffer .= $indent . '			</select>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="project-largeimage">Image du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        // 'project.hasImage' section
        $buffer .= $this->section66df169ebe1f4371fd1ec5d3f3a844c5($context, $indent, $context->findDot('project.hasImage'));
        $buffer .= $indent . '			<input type="file" name="project-largeimage" id="project-largeimage" accept="image/png"><span class="help-inline">(taille conseillée : 512&times;512 pixels)</span>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="project-mediumimage">Petite image du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        // 'project.hasImage' section
        $buffer .= $this->section30cbcab3931f4b2d3bb3762045b57b0f($context, $indent, $context->findDot('project.hasImage'));
        $buffer .= $indent . '			<input type="file" name="project-mediumimage" id="project-mediumimage" accept="image/png"><span class="help-inline">(taille conseillée : 140&times;140 pixels)</span>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="project-url">URL du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<input type="url" name="project-url" id="project-url" value="';
        $value = $context->findDot('project.url');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" placeholder="URL" class="input-xxlarge">';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="project-shortDescription">Courte description du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<textarea name="project-shortDescription" id="project-shortDescription" rows="5" class="input-xxlarge">';
        $value = $context->findDot('project.shortDescription');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '</textarea>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="control-group">';
        $buffer .= "\n";
        $buffer .= $indent . '		<label class="control-label" for="project-description">Description complète du projet</label>';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="controls">';
        $buffer .= "\n";
        $buffer .= $indent . '			<textarea name="project-description" id="project-description" rows="10" class="input-xxlarge">';
        $value = $context->findDot('project.description');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '</textarea>';
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

    private function sectionBe92db1e102f2867807cc45cbf345dfe(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
					<option value="{{name}}"{{#selected?}} selected="selected"{{/selected?}}>{{title}}</option>
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
                $value = $context->find('title');
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

    private function section66df169ebe1f4371fd1ec5d3f3a844c5(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
				<div class="row">
					<div class="thumbnail span3">
						<img src="{{WEBSITE_ROOT}}/img/portfolio/project/large/{{project.name}}.png" alt="Image actuelle"/>
						<label class="checkbox">
							<input type="checkbox" name="project-largeimage-remove" id="project-largeimage-remove"> Supprimer l\'image actuelle
						</label>
					</div>
				</div>
			';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '				<div class="row">';
                $buffer .= "\n";
                $buffer .= $indent . '					<div class="thumbnail span3">';
                $buffer .= "\n";
                $buffer .= $indent . '						<img src="';
                $value = $context->find('WEBSITE_ROOT');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '/img/portfolio/project/large/';
                $value = $context->findDot('project.name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.png" alt="Image actuelle"/>';
                $buffer .= "\n";
                $buffer .= $indent . '						<label class="checkbox">';
                $buffer .= "\n";
                $buffer .= $indent . '							<input type="checkbox" name="project-largeimage-remove" id="project-largeimage-remove"> Supprimer l\'image actuelle';
                $buffer .= "\n";
                $buffer .= $indent . '						</label>';
                $buffer .= "\n";
                $buffer .= $indent . '					</div>';
                $buffer .= "\n";
                $buffer .= $indent . '				</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section30cbcab3931f4b2d3bb3762045b57b0f(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
				<div class="row">
					<div class="thumbnail span3">
						<img src="{{WEBSITE_ROOT}}/img/portfolio/project/medium/{{project.name}}.png" alt="Image actuelle"/>
					</div>
				</div>
			';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '				<div class="row">';
                $buffer .= "\n";
                $buffer .= $indent . '					<div class="thumbnail span3">';
                $buffer .= "\n";
                $buffer .= $indent . '						<img src="';
                $value = $context->find('WEBSITE_ROOT');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '/img/portfolio/project/medium/';
                $value = $context->findDot('project.name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.png" alt="Image actuelle"/>';
                $buffer .= "\n";
                $buffer .= $indent . '					</div>';
                $buffer .= "\n";
                $buffer .= $indent . '				</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}