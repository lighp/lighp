<?php

class __5183a44e0dc3a7d492a6688ac6cd2630 extends core\mustache\Template
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
        // 'actions' section
        $buffer .= $this->sectionD060df6ef900fea6a2481b45365d0693($context, $indent, $context->find('actions'));
        $buffer .= $indent . '</ul>';
        $buffer .= "\n";
        $buffer .= "\n";
        // 'emptyQuery?' section
        $buffer .= $this->section93ee251bc091e93e4e79793ac9197cff($context, $indent, $context->find('emptyQuery?'));
        // 'emptyQuery?' inverted section
        $value = $context->find('emptyQuery?');
        if (empty($value)) {
            
            // 'actions' inverted section
            $value = $context->find('actions');
            if (empty($value)) {
                
                $buffer .= $indent . '		<div class="alert alert-warning">';
                $buffer .= "\n";
                $buffer .= $indent . '			<h4 class="alert-heading">Aucun r&eacute;sultat</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '			<p>Les termes de votre recherche ne correspondent &agrave; aucune action.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '			<p><a class="btn" href="index.html">Retour</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '		</div>';
                $buffer .= "\n";
            }
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionD060df6ef900fea6a2481b45365d0693(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<li><a href="{{url}}"><i class="icon-{{backend.icon}}{{^backend}}cog{{/backend}}"></i>&nbsp;{{{backend.title}}} : {{{title}}}</a></li>
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '		<li><a href="';
                $value = $context->find('url');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '"><i class="icon-';
                $value = $context->findDot('backend.icon');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                // 'backend' inverted section
                $value = $context->find('backend');
                if (empty($value)) {
                    
                    $buffer .= 'cog';
                }
                $buffer .= '"></i>&nbsp;';
                $value = $context->findDot('backend.title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= $value;
                $buffer .= ' : ';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= $value;
                $buffer .= '</a></li>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section93ee251bc091e93e4e79793ac9197cff(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-warning">
		<h4 class="alert-heading">Recherche vide</h4>
		<p>Vous n\'avez entr&eacute; aucune recherche.</p>
		<p><a class="btn" href="index.html">Retour</a></p>
	</div>
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '	<div class="alert alert-warning">';
                $buffer .= "\n";
                $buffer .= $indent . '		<h4 class="alert-heading">Recherche vide</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>Vous n\'avez entr&eacute; aucune recherche.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p><a class="btn" href="index.html">Retour</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '	</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}