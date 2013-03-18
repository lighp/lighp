<?php

class __118ae541526703ccb26e41452fe2259c extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<table class="table">';
        $buffer .= "\n";
        $buffer .= $indent . '	<thead>';
        $buffer .= "\n";
        $buffer .= $indent . '		<tr>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th>Nom</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th>URL</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th></th>';
        $buffer .= "\n";
        $buffer .= $indent . '		</tr>';
        $buffer .= "\n";
        $buffer .= $indent . '	</thead>';
        $buffer .= "\n";
        $buffer .= $indent . '	<tbody>';
        $buffer .= "\n";
        // 'repositories' section
        $buffer .= $this->sectionEf2f4278766118e737040737d242dcf6($context, $indent, $context->find('repositories'));
        $buffer .= $indent . '	</tbody>';
        $buffer .= "\n";
        $buffer .= $indent . '</table>';
        $buffer .= "\n";
        // 'repositories' inverted section
        $value = $context->find('repositories');
        if (empty($value)) {
            
            $buffer .= $indent . '	<p class="alert alert-info">Il n\'y a actuellement aucun d&eacute;p&ocirc;t configur&eacute;.</p>';
            $buffer .= "\n";
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionEf2f4278766118e737040737d242dcf6(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			{{> partials/table-repository}}
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                if ($partial = $this->mustache->loadPartial('partials/table-repository')) {
                    $buffer .= $partial->renderInternal($context, '			');
                }
                $context->pop();
            }
        }
    
        return $buffer;
    }
}