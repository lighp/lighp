<?php

class __8f63d3c76dc30eb74235e5875995f0b6 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="container">';
        $buffer .= "\n";
        // 'project' section
        $buffer .= $this->section89ab0630116d8f0dfa3644fab73b32a6($context, $indent, $context->find('project'));
        $buffer .= $indent . '</div><!-- /.container -->';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function section096b0e5938c2e4ce8bd2bd4c2f63af1d(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
				<img class="featurette-image pull-left" src="img/portfolio/project/large/{{name}}.png"/>
			';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '				<img class="featurette-image pull-left" src="img/portfolio/project/large/';
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

    private function section48ab0c2096e73e9f8d41986972c774b9(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
					<a class="btn btn-large btn-primary" href="{{url}}">Acc&eacute;der au site Web &raquo;</a>
				';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '					<a class="btn btn-large btn-primary" href="';
                $value = $context->find('url');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '">Acc&eacute;der au site Web &raquo;</a>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section241a6b54c32908e56d77b6c83dfa7d99(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<hr />

			<p>{{{description}}}</p>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<hr />';
                $buffer .= "\n";
                $buffer .= "\n";
                $buffer .= $indent . '			<p>';
                $value = $context->find('description');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= $value;
                $buffer .= '</p>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5b4c55918e01da611bd5cc4984dd76ab(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
					</ul>
					<ul class="thumbnails">
				';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '					</ul>';
                $buffer .= "\n";
                $buffer .= $indent . '					<ul class="thumbnails">';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section888d558c5b8ee1a1a6cd7d58762b274e(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
				{{#changeRow?}}
					</ul>
					<ul class="thumbnails">
				{{/changeRow?}}

				{{{render}}}
			';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'changeRow?' section
                $buffer .= $this->section5b4c55918e01da611bd5cc4984dd76ab($context, $indent, $context->find('changeRow?'));
                $buffer .= "\n";
                $buffer .= $indent . '				';
                $value = $context->find('render');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= $value;
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section76551f86bfb1ead1ae034fbb3e8b3db6(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<h2>Gallerie</h2>
		<ul class="thumbnails">
			{{#gallery}}
				{{#changeRow?}}
					</ul>
					<ul class="thumbnails">
				{{/changeRow?}}

				{{{render}}}
			{{/gallery}}
		</ul><!-- /.thumbnails -->
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '		<h2>Gallerie</h2>';
                $buffer .= "\n";
                $buffer .= $indent . '		<ul class="thumbnails">';
                $buffer .= "\n";
                // 'gallery' section
                $buffer .= $this->section888d558c5b8ee1a1a6cd7d58762b274e($context, $indent, $context->find('gallery'));
                $buffer .= $indent . '		</ul><!-- /.thumbnails -->';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section89ab0630116d8f0dfa3644fab73b32a6(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="featurette">
			{{#hasImage}}
				<img class="featurette-image pull-left" src="img/portfolio/project/large/{{name}}.png"/>
			{{/hasImage}}
			<h2 class="featurette-heading">{{title}}. <span class="muted">{{subtitle}}</span></h2>
			<p class="lead">{{{shortDescription}}}</p>
			<p>
				{{#url}}
					<a class="btn btn-large btn-primary" href="{{url}}">Acc&eacute;der au site Web &raquo;</a>
				{{/url}}
				<a class="btn btn-large btn-inverse" href="category-{{category}}.html">Projets similaires</a>
			</p>
		</div><!-- /.featurette -->

		{{#description}}
			<hr />

			<p>{{{description}}}</p>
		{{/description}}

		{{#gallery?}}
		<h2>Gallerie</h2>
		<ul class="thumbnails">
			{{#gallery}}
				{{#changeRow?}}
					</ul>
					<ul class="thumbnails">
				{{/changeRow?}}

				{{{render}}}
			{{/gallery}}
		</ul><!-- /.thumbnails -->
		{{/gallery?}}
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
                // 'hasImage' section
                $buffer .= $this->section096b0e5938c2e4ce8bd2bd4c2f63af1d($context, $indent, $context->find('hasImage'));
                $buffer .= $indent . '			<h2 class="featurette-heading">';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '. <span class="muted">';
                $value = $context->find('subtitle');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</span></h2>';
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
                $buffer .= $indent . '			<p>';
                $buffer .= "\n";
                // 'url' section
                $buffer .= $this->section48ab0c2096e73e9f8d41986972c774b9($context, $indent, $context->find('url'));
                $buffer .= $indent . '				<a class="btn btn-large btn-inverse" href="category-';
                $value = $context->find('category');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html">Projets similaires</a>';
                $buffer .= "\n";
                $buffer .= $indent . '			</p>';
                $buffer .= "\n";
                $buffer .= $indent . '		</div><!-- /.featurette -->';
                $buffer .= "\n";
                $buffer .= "\n";
                // 'description' section
                $buffer .= $this->section241a6b54c32908e56d77b6c83dfa7d99($context, $indent, $context->find('description'));
                $buffer .= "\n";
                // 'gallery?' section
                $buffer .= $this->section76551f86bfb1ead1ae034fbb3e8b3db6($context, $indent, $context->find('gallery?'));
                $context->pop();
            }
        }
    
        return $buffer;
    }
}