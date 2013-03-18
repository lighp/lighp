<?php

class __e158498bea5a8c30ac7776cdc54908ae extends core\mustache\Template
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
        $buffer .= $indent . '			<th>Type</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th>Emplacement</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th></th>';
        $buffer .= "\n";
        $buffer .= $indent . '		</tr>';
        $buffer .= "\n";
        $buffer .= $indent . '	</thead>';
        $buffer .= "\n";
        $buffer .= $indent . '	<tbody>';
        $buffer .= "\n";
        // 'leadingItems' section
        $buffer .= $this->sectionEf9cf8a221620d67686550587637aca5($context, $indent, $context->find('leadingItems'));
        $buffer .= $indent . '	</tbody>';
        $buffer .= "\n";
        $buffer .= $indent . '</table>';
        $buffer .= "\n";
        // 'leadingItems' inverted section
        $value = $context->find('leadingItems');
        if (empty($value)) {
            
            $buffer .= $indent . '	<p class="alert alert-info">Aucun item n\'est pour l\'instant mis en avant.</p>';
            $buffer .= "\n";
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionEf9cf8a221620d67686550587637aca5(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<tr>
				<td><a href="portfolio-{{leadingKind}}-{{name}}.html">{{title}}</a></td>
				<td>{{leadingKind}}</td>
				<td>{{leadingPlace}}</td>
				<td>
					<div class="btn-group">
						<a class="btn btn-danger" href="portfolio-leading-remove-{{id}}.html"><i class="icon-trash icon-white"></i>&nbsp;Supprimer {{id}}</a>
					</div>
				</td>
			</tr>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<tr>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td><a href="portfolio-';
                $value = $context->find('leadingKind');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '-';
                $value = $context->find('name');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html">';
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</a></td>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td>';
                $value = $context->find('leadingKind');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</td>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td>';
                $value = $context->find('leadingPlace');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</td>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td>';
                $buffer .= "\n";
                $buffer .= $indent . '					<div class="btn-group">';
                $buffer .= "\n";
                $buffer .= $indent . '						<a class="btn btn-danger" href="portfolio-leading-remove-';
                $value = $context->find('id');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '.html"><i class="icon-trash icon-white"></i>&nbsp;Supprimer ';
                $value = $context->find('id');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</a>';
                $buffer .= "\n";
                $buffer .= $indent . '					</div>';
                $buffer .= "\n";
                $buffer .= $indent . '				</td>';
                $buffer .= "\n";
                $buffer .= $indent . '			</tr>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}