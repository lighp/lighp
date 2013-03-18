<?php

class __3f4f3fe3a4c08a9733cb3057f377cac6 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<!-- Marketing messaging and featurettes';
        $buffer .= "\n";
        $buffer .= $indent . '================================================== -->';
        $buffer .= "\n";
        $buffer .= $indent . '<!-- Wrap the rest of the page in another container to center all the content. -->';
        $buffer .= "\n";
        $buffer .= $indent . '<div class="container marketing">';
        $buffer .= "\n";
        $buffer .= $indent . '	<!-- Three columns of text below the carousel -->';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="row">';
        $buffer .= "\n";
        // 'categories' section
        $buffer .= $this->section92022f6e1958caf0a7fc1a0a2b702e0f($context, $indent, $context->find('categories'));
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="span4">';
        $buffer .= "\n";
        $buffer .= $indent . '			<img src="img/contact/icon-medium.png">';
        $buffer .= "\n";
        $buffer .= $indent . '			<h2>Contact</h2>';
        $buffer .= "\n";
        $buffer .= $indent . '			<p>N\'h&eacute;sitez pas &agrave; me contacter pour toute question !</p>';
        $buffer .= "\n";
        $buffer .= $indent . '			<p><a class="btn" href="contact.html">Contact &raquo;</a></p>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div><!-- /.span4 -->';
        $buffer .= "\n";
        $buffer .= $indent . '	</div><!-- /.row -->';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<!-- START THE FEATURETTES -->';
        $buffer .= "\n";
        $buffer .= $indent . '	<hr class="featurette-divider" />';
        $buffer .= "\n";
        // 'leadingProjects' section
        $buffer .= $this->section84160a8dfda1bcf514fe316091efdc3d($context, $indent, $context->find('leadingProjects'));
        $buffer .= $indent . '	<!-- /END THE FEATURETTES -->';
        $buffer .= "\n";
        $buffer .= $indent . '	';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="btn-group pull-right">';
        $buffer .= "\n";
        // 'categories' section
        $buffer .= $this->section8f501d4565232eef0866ed97ad65ec2f($context, $indent, $context->find('categories'));
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<h1>Autres projets</h1>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="row">';
        $buffer .= "\n";
        // 'otherProjects' section
        $buffer .= $this->sectionA84c7f92bf2ca1138e4ce465a27929a4($context, $indent, $context->find('otherProjects'));
        $buffer .= $indent . '	</div><!-- /.row -->';
        $buffer .= "\n";
        // 'otherProjects' inverted section
        $value = $context->find('otherProjects');
        if (empty($value)) {
            
            $buffer .= $indent . '		<p class="muted">Il n\'y a aucun projet pour le moment.</p>';
            $buffer .= "\n";
        }
        $buffer .= $indent . '</div><!-- /.container -->';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function section2b3a6ef0453e6b49d0e2409b2da10afd(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
				</div>
				<div class="row">
			';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '				</div>';
                $buffer .= "\n";
                $buffer .= $indent . '				<div class="row">';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3fee2e8f67b5674ba90c90d1ce2ffae7(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
					<img class="img-circle" src="img/portfolio/category/medium/{{name}}.png"/>
				';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '					<img class="img-circle" src="img/portfolio/category/medium/';
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

    private function section92022f6e1958caf0a7fc1a0a2b702e0f(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			{{#changeRow?}}
				</div>
				<div class="row">
			{{/changeRow?}}
			<div class="span4">
				{{#hasImage}}
					<img class="img-circle" src="img/portfolio/category/medium/{{name}}.png"/>
				{{/hasImage}}
				{{^hasImage}}
					<img src="img/portfolio/generic/category.png"/>
				{{/hasImage}}
				<h2>{{title}}</h2>
				<p>{{{shortDescription}}}</p>
				<p><a class="btn btn-inverse" href="category-{{name}}.html">Plus &raquo;</a></p>
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
                $buffer .= $this->section2b3a6ef0453e6b49d0e2409b2da10afd($context, $indent, $context->find('changeRow?'));
                $buffer .= $indent . '			<div class="span4">';
                $buffer .= "\n";
                // 'hasImage' section
                $buffer .= $this->section3fee2e8f67b5674ba90c90d1ce2ffae7($context, $indent, $context->find('hasImage'));
                // 'hasImage' inverted section
                $value = $context->find('hasImage');
                if (empty($value)) {
                    
                    $buffer .= $indent . '					<img src="img/portfolio/generic/category.png"/>';
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
                $buffer .= $indent . '				<p><a class="btn btn-inverse" href="category-';
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

    private function section6f33152a41341e2c397de871a1796b75(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = 'right';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= 'right';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section036d0bea96be207b844fd9868e80cdf1(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<img class="featurette-image pull-{{#pullRight?}}right{{/pullRight?}}{{^pullRight?}}left{{/pullRight?}}" src="img/portfolio/{{item.kind}}/large/{{item.name}}.png">
			';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<img class="featurette-image pull-';
                // 'pullRight?' section
                $buffer .= $this->section6f33152a41341e2c397de871a1796b75($context, $indent, $context->find('pullRight?'));
                // 'pullRight?' inverted section
                $value = $context->find('pullRight?');
                if (empty($value)) {
                    
                    $buffer .= 'left';
                }
                $buffer .= '" src="img/portfolio/';
                $value = $context->findDot('item.kind');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '/large/';
                $value = $context->findDot('item.name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.png">';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section84160a8dfda1bcf514fe316091efdc3d(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="featurette">
			{{#data.hasImage}}
			<img class="featurette-image pull-{{#pullRight?}}right{{/pullRight?}}{{^pullRight?}}left{{/pullRight?}}" src="img/portfolio/{{item.kind}}/large/{{item.name}}.png">
			{{/data.hasImage}}
			<h2 class="featurette-heading">{{data.title}}. <span class="muted">{{data.subtitle}}</span></h2>
			<p class="lead">{{{data.shortDescription}}}</p>
			<p><a class="btn btn-large btn-primary" href="{{item.kind}}-{{item.name}}.html">En savoir plus &raquo;</a></p>
		</div>

		<hr class="featurette-divider" />
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '		<div class="featurette">';
                $buffer .= "\n";
                // 'data.hasImage' section
                $buffer .= $this->section036d0bea96be207b844fd9868e80cdf1($context, $indent, $context->findDot('data.hasImage'));
                $buffer .= $indent . '			<h2 class="featurette-heading">';
                $value = $context->findDot('data.title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '. <span class="muted">';
                $value = $context->findDot('data.subtitle');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</span></h2>';
                $buffer .= "\n";
                $buffer .= $indent . '			<p class="lead">';
                $value = $context->findDot('data.shortDescription');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= $value;
                $buffer .= '</p>';
                $buffer .= "\n";
                $buffer .= $indent . '			<p><a class="btn btn-large btn-primary" href="';
                $value = $context->findDot('item.kind');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '-';
                $value = $context->findDot('item.name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html">En savoir plus &raquo;</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '		</div>';
                $buffer .= "\n";
                $buffer .= "\n";
                $buffer .= $indent . '		<hr class="featurette-divider" />';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8f501d4565232eef0866ed97ad65ec2f(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<a href="category-{{name}}.html" class="btn btn-large btn-inverse">{{title}}</a>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<a href="category-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html" class="btn btn-large btn-inverse">';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</a>';
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