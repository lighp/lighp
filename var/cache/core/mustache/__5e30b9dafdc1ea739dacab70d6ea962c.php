<?php

class __5e30b9dafdc1ea739dacab70d6ea962c extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        // 'inserted?' section
        $buffer .= $this->sectionD00e4ff43b6a5c072f9bf5485ce2501c($context, $indent, $context->find('inserted?'));
        // 'inserted?' inverted section
        $value = $context->find('inserted?');
        if (empty($value)) {
            
            if ($partial = $this->mustache->loadPartial('partials/form-galleryItem')) {
                $buffer .= $partial->renderInternal($context, '	');
            }
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionD00e4ff43b6a5c072f9bf5485ce2501c(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
	<div class="alert alert-block alert-success">
		<h4 class="alert-heading">Item cr&eacute;&eacute;</h4>
		<p>L\'item a bien &eacute;t&eacute; cr&eacute;&eacute;.</p>
		<p><a class="btn" href="module-portfolio.html">Retour</a></p>
	</div>
';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '	<div class="alert alert-block alert-success">';
                $buffer .= "\n";
                $buffer .= $indent . '		<h4 class="alert-heading">Item cr&eacute;&eacute;</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p>L\'item a bien &eacute;t&eacute; cr&eacute;&eacute;.</p>';
                $buffer .= "\n";
                $buffer .= $indent . '		<p><a class="btn" href="module-portfolio.html">Retour</a></p>';
                $buffer .= "\n";
                $buffer .= $indent . '	</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}