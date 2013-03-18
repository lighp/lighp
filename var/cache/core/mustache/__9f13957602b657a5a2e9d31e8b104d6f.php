<?php

class __9f13957602b657a5a2e9d31e8b104d6f extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        if ($partial = $this->mustache->loadPartial('partials/form-search')) {
            $buffer .= $partial->renderInternal($context, '');
        }
        $buffer .= "\n";
        $buffer .= $indent . '<ul class="nav nav-tabs nav-stacked">';
        $buffer .= "\n";
        // 'backends' section
        $buffer .= $this->section5ede693ab973c793dd2581485f09663b($context, $indent, $context->find('backends'));
        $buffer .= $indent . '</ul>';
        $buffer .= "\n";
        // 'backends' inverted section
        $value = $context->find('backends');
        if (empty($value)) {
            
            $buffer .= $indent . '	<p class="alert alert-info">Il n\'y a actuellement aucun module qui soit administrable via cette page.</p>';
            $buffer .= "\n";
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionBb255b553790721d985b5c11c39036e3(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{icon}}';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $context->find('icon');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section5ede693ab973c793dd2581485f09663b(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<li><a href="module-{{name}}.html"><i class="icon-{{#icon}}{{icon}}{{/icon}}{{^icon}}cog{{/icon}}"></i>&nbsp;{{title}}</a></li>
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '		<li><a href="module-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html"><i class="icon-';
                // 'icon' section
                $buffer .= $this->sectionBb255b553790721d985b5c11c39036e3($context, $indent, $context->find('icon'));
                // 'icon' inverted section
                $value = $context->find('icon');
                if (empty($value)) {
                    
                    $buffer .= 'cog';
                }
                $buffer .= '"></i>&nbsp;';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</a></li>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}