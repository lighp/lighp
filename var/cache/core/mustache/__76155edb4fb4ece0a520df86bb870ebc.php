<?php

class __76155edb4fb4ece0a520df86bb870ebc extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="container marketing">';
        $buffer .= "\n";
        // 'category' section
        $buffer .= $this->sectionE6e4c60bc8f7c03b8df7a758909f5488($context, $indent, $context->find('category'));
        $buffer .= $indent . '	';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="row">';
        $buffer .= "\n";
        // 'projects' section
        $buffer .= $this->sectionA84c7f92bf2ca1138e4ce465a27929a4($context, $indent, $context->find('projects'));
        $buffer .= $indent . '	</div><!-- /.row -->';
        $buffer .= "\n";
        $buffer .= "\n";
        // 'projects' inverted section
        $value = $context->find('projects');
        if (empty($value)) {
            
            $buffer .= $indent . '		<p class="muted">Aucun projet dans cette catégorie.</p>';
            $buffer .= "\n";
        }
        $buffer .= $indent . '</div><!-- /.container -->';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionEbc8b2e5ba6fcce5d9d99495eabe1be9(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<img class="img-circle pull-right" src="img/portfolio/category/large/{{name}}.png"/>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<img class="img-circle pull-right" src="img/portfolio/category/large/';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.png"/>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE6e4c60bc8f7c03b8df7a758909f5488(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		{{#hasImage}}
			<img class="img-circle pull-right" src="img/portfolio/category/large/{{name}}.png"/>
		{{/hasImage}}

		<div class="page-header">
			<h1>{{title}}</h1>
			<p class="lead">{{{shortDescription}}}</p>
		</div>
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'hasImage' section
                $buffer .= $this->sectionEbc8b2e5ba6fcce5d9d99495eabe1be9($context, $indent, $context->find('hasImage'));
                $buffer .= "\n";
                $buffer .= $indent . '		<div class="page-header">';
                $buffer .= "\n";
                $buffer .= $indent . '			<h1>';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</h1>';
                $buffer .= "\n";
                $buffer .= $indent . '			<p class="lead">';
                $value = $context->find('shortDescription');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= $value;
                $buffer .= '</p>';
                $buffer .= "\n";
                $buffer .= $indent . '		</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionE116a9df0eb0f0824b0331b13e364b63(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
				</div><!-- /.row -->
				<div class="row">
			';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '				</div><!-- /.row -->';
                $buffer .= "\n";
                $buffer .= $indent . '				<div class="row">';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section06c4d5fc11349e85a3c09b6cb88bd00e(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
					<img src="img/portfolio/project/medium/{{name}}.png"/>
				';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '					<img src="img/portfolio/project/medium/';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.png"/>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionA84c7f92bf2ca1138e4ce465a27929a4(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			{{#changeRow?}}
				</div><!-- /.row -->
				<div class="row">
			{{/changeRow?}}
			<div class="span4">
				{{#hasImage}}
					<img src="img/portfolio/project/medium/{{name}}.png"/>
				{{/hasImage}}
				{{^hasImage}}
					<img src="img/portfolio/generic/project.png"/>
				{{/hasImage}}
				<h2>{{title}}</h2>
				<p>{{{shortDescription}}}</p>
				<p><a class="btn" href="project-{{name}}.html">Plus &raquo;</a></p>
			</div><!-- /.span4 -->
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'changeRow?' section
                $buffer .= $this->sectionE116a9df0eb0f0824b0331b13e364b63($context, $indent, $context->find('changeRow?'));
                $buffer .= $indent . '			<div class="span4">';
                $buffer .= "\n";
                // 'hasImage' section
                $buffer .= $this->section06c4d5fc11349e85a3c09b6cb88bd00e($context, $indent, $context->find('hasImage'));
                // 'hasImage' inverted section
                $value = $context->find('hasImage');
                if (empty($value)) {
                    
                    $buffer .= $indent . '					<img src="img/portfolio/generic/project.png"/>';
                    $buffer .= "\n";
                }
                $buffer .= $indent . '				<h2>';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</h2>';
                $buffer .= "\n";
                $buffer .= $indent . '				<p>';
                $value = $context->find('shortDescription');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= $value;
                $buffer .= '</p>';
                $buffer .= "\n";
                $buffer .= $indent . '				<p><a class="btn" href="project-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html">Plus &raquo;</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '			</div><!-- /.span4 -->';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}