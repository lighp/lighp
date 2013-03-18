<?php

class __9511ac52d0594cb405d6716da9099aa6 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<form action="search.html" method="get" class="form-search">';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="input-append">';
        $buffer .= "\n";
        $buffer .= $indent . '		<input type="search" class="search-query span10" name="q" placeholder="Entrez une action..." value="';
        $value = $context->find('searchQuery');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" autofocus/>';
        $buffer .= "\n";
        $buffer .= $indent . '		<button class="btn" type="submit"><i class="icon-search"></i></button>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= "\n";
        // 'backend' section
        $buffer .= $this->sectionF017823b6ffba3f313762b5ebc599048($context, $indent, $context->find('backend'));
        $buffer .= $indent . '</form>';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionF017823b6ffba3f313762b5ebc599048(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<input type="hidden" name="module" value="{{backend.name}}"/>
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '		<input type="hidden" name="module" value="';
                $value = $context->findDot('backend.name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '"/>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}