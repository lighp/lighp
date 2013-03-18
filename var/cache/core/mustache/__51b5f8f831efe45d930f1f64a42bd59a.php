<?php

class __51b5f8f831efe45d930f1f64a42bd59a extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<table class="table">';
        $buffer .= "\n";
        $buffer .= $indent . '	<thead>';
        $buffer .= "\n";
        $buffer .= $indent . '		<tr>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th>Titre</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th>Cat&eacute;gorie</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th></th>';
        $buffer .= "\n";
        $buffer .= $indent . '		</tr>';
        $buffer .= "\n";
        $buffer .= $indent . '	</thead>';
        $buffer .= "\n";
        $buffer .= $indent . '	<tbody>';
        $buffer .= "\n";
        // 'projects' section
        $buffer .= $this->section3359443a70334ea7e43fb5c197a852b6($context, $indent, $context->find('projects'));
        $buffer .= $indent . '	</tbody>';
        $buffer .= "\n";
        $buffer .= $indent . '</table>';
        $buffer .= "\n";
        // 'projects' inverted section
        $value = $context->find('projects');
        if (empty($value)) {
            
            $buffer .= $indent . '	<p class="alert alert-info">Il n\'y a actuellement aucun projet dans la base de donn&eacute;es.</p>';
            $buffer .= "\n";
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionCeaa9ea2156dcc226aeae758dfa9e9ce(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<a href="../category-{{name}}.html">{{title}}</a>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<a href="../category-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html">';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</a>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3359443a70334ea7e43fb5c197a852b6(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<tr>
				<td><a href="../project-{{name}}.html">{{title}}</a> - {{subtitle}}</td>
				<td>{{#category}}<a href="../category-{{name}}.html">{{title}}</a>{{/category}}</td>
				<td>
					<div class="btn-group">
						<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
							G&eacute;rer
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li><a href="portfolio-projects-update-{{name}}.html"><i class="icon-pencil"></i> Modifier</a></li>
							<li><a href="portfolio-galleries-{{name}}.html"><i class="icon-picture"></i> Gérer la galerie</a></li>
							<li><a href="portfolio-galleries-insert-{{name}}.html"><i class="icon-plus-sign"></i> Insérer un item dans la galerie</a></li>
							<li class="divider"></li>
							<li><a href="portfolio-projects-delete-{{name}}.html"><i class="icon-trash"></i> Supprimer</a></li>
						</ul>
					</div>
				</td>
			</tr>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<tr>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td><a href="../project-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html">';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</a> - ';
                $value = $context->find('subtitle');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</td>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td>';
                // 'category' section
                $buffer .= $this->sectionCeaa9ea2156dcc226aeae758dfa9e9ce($context, $indent, $context->find('category'));
                $buffer .= '</td>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td>';
                $buffer .= "\n";
                $buffer .= $indent . '					<div class="btn-group">';
                $buffer .= "\n";
                $buffer .= $indent . '						<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">';
                $buffer .= "\n";
                $buffer .= $indent . '							G&eacute;rer';
                $buffer .= "\n";
                $buffer .= $indent . '							<span class="caret"></span>';
                $buffer .= "\n";
                $buffer .= $indent . '						</a>';
                $buffer .= "\n";
                $buffer .= $indent . '						<ul class="dropdown-menu">';
                $buffer .= "\n";
                $buffer .= $indent . '							<li><a href="portfolio-projects-update-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html"><i class="icon-pencil"></i> Modifier</a></li>';
                $buffer .= "\n";
                $buffer .= $indent . '							<li><a href="portfolio-galleries-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html"><i class="icon-picture"></i> Gérer la galerie</a></li>';
                $buffer .= "\n";
                $buffer .= $indent . '							<li><a href="portfolio-galleries-insert-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html"><i class="icon-plus-sign"></i> Insérer un item dans la galerie</a></li>';
                $buffer .= "\n";
                $buffer .= $indent . '							<li class="divider"></li>';
                $buffer .= "\n";
                $buffer .= $indent . '							<li><a href="portfolio-projects-delete-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html"><i class="icon-trash"></i> Supprimer</a></li>';
                $buffer .= "\n";
                $buffer .= $indent . '						</ul>';
                $buffer .= "\n";
                $buffer .= $indent . '					</div>';
                $buffer .= "\n";
                $buffer .= $indent . '				</td>';
                $buffer .= "\n";
                $buffer .= $indent . '			</tr>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}