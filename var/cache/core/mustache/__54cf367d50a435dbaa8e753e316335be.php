<?php

class __54cf367d50a435dbaa8e753e316335be extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<ul class="thumbnails">';
        $buffer .= "\n";
        // 'gallery' section
        $buffer .= $this->section28722cb61705b50a3440f953ac3e7aee($context, $indent, $context->find('gallery'));
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

    private function section28722cb61705b50a3440f953ac3e7aee(core\mustache\Context $context, $indent, $value) {
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
				<p>Thumbnail caption...</p>
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
                $buffer .= $indent . '				<p>Thumbnail caption...</p>';
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