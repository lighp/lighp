<?php

class __ef421a551d29ce3cf61def96a4400789 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'backend' section
        $buffer .= $this->section66bbea7b5004e3bfe86069638490b2aa($context, $indent, $context->find('backend'));
        // 'backend' inverted section
        $value = $context->find('backend');
        if (empty($value)) {
            
            $buffer .= $indent . '	<div class="alert alert-error">';
            $buffer .= "\n";
            $buffer .= $indent . '		<h4 class="alert-heading">Introuvable</h4>';
            $buffer .= "\n";
            $buffer .= $indent . '		<p>Le module sp&eacute;cifi&eacute; est introuvable.</p>';
            $buffer .= "\n";
            $buffer .= $indent . '		<p><a class="btn" href="index.html">Retour</a></p>';
            $buffer .= "\n";
            $buffer .= $indent . '	</div>';
            $buffer .= "\n";
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionC3dc19758412a7f6989ed03716934900(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<li><a href="{{url}}">{{title}}</a></li>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<li><a href="';
                $value = $context->find('url');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '">';
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

    private function section66bbea7b5004e3bfe86069638490b2aa(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	{{> partials/form-search}}

	<ul class="nav nav-tabs nav-stacked">
		{{#actions}}
			<li><a href="{{url}}">{{title}}</a></li>
		{{/actions}}
	</ul>
	{{^actions}}
		<p>Aucune action n\'est associ&eacute;e &agrave; ce module.</p>
	{{/actions}}
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                if ($partial = $this->mustache->loadPartial('partials/form-search')) {
                    $buffer .= $partial->renderInternal($context, '	');
                }
                $buffer .= "\n";
                $buffer .= $indent . '	<ul class="nav nav-tabs nav-stacked">';
                $buffer .= "\n";
                // 'actions' section
                $buffer .= $this->sectionC3dc19758412a7f6989ed03716934900($context, $indent, $context->find('actions'));
                $buffer .= $indent . '	</ul>';
                $buffer .= "\n";
                // 'actions' inverted section
                $value = $context->find('actions');
                if (empty($value)) {
                    
                    $buffer .= $indent . '		<p>Aucune action n\'est associ&eacute;e &agrave; ce module.</p>';
                    $buffer .= "\n";
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }
}