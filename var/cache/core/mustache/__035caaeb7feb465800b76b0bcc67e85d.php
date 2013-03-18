<?php

class __035caaeb7feb465800b76b0bcc67e85d extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<ul class="thumbnails">';
        $buffer .= "\n";
        // 'gallery' section
        $buffer .= $this->sectionFa47be4312ce3ffa5b3ed565e6c55b4a($context, $indent, $context->find('gallery'));
        $buffer .= $indent . '</ul><!-- /.thumbnails -->';
        $buffer .= "\n";
        // 'gallery' inverted section
        $value = $context->find('gallery');
        if (empty($value)) {
            
            $buffer .= $indent . '	<p class="alert alert-info">La galerie de ce projet est vide.</p>';
            $buffer .= "\n";
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionA5e21f4a0fe5f7c1395ec714b9f46c4b(core\mustache\Context $context, $indent, $value) {
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
                $buffer .= $indent . '			</ul>';
                $buffer .= "\n";
                $buffer .= $indent . '			<ul class="thumbnails">';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFa47be4312ce3ffa5b3ed565e6c55b4a(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		{{#changeRow?}}
			</ul>
			<ul class="thumbnails">
		{{/changeRow?}}

		<li class="span4">
			<div class="thumbnail">
				{{{render}}}
				<p>
					<a href="portfolio-galleries-update-{{projectName}}-{{id}}.html" class="btn"><i class="icon-pencil"></i> Modifier</a>
					<a href="portfolio-galleries-delete-{{projectName}}-{{id}}.html" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</a>
				</p>
			</div>
		</li><!-- /.span4 -->
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                // 'changeRow?' section
                $buffer .= $this->sectionA5e21f4a0fe5f7c1395ec714b9f46c4b($context, $indent, $context->find('changeRow?'));
                $buffer .= "\n";
                $buffer .= $indent . '		<li class="span4">';
                $buffer .= "\n";
                $buffer .= $indent . '			<div class="thumbnail">';
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
                $buffer .= $indent . '				<p>';
                $buffer .= "\n";
                $buffer .= $indent . '					<a href="portfolio-galleries-update-';
                $value = $context->find('projectName');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '-';
                $value = $context->find('id');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html" class="btn"><i class="icon-pencil"></i> Modifier</a>';
                $buffer .= "\n";
                $buffer .= $indent . '					<a href="portfolio-galleries-delete-';
                $value = $context->find('projectName');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '-';
                $value = $context->find('id');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html" class="btn btn-danger"><i class="icon-trash icon-white"></i> Supprimer</a>';
                $buffer .= "\n";
                $buffer .= $indent . '				</p>';
                $buffer .= "\n";
                $buffer .= $indent . '			</div>';
                $buffer .= "\n";
                $buffer .= $indent . '		</li><!-- /.span4 -->';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}