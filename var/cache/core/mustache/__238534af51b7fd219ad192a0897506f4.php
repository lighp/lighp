<?php

class __238534af51b7fd219ad192a0897506f4 extends core\mustache\Template
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
        $buffer .= $indent . '			<th>Masque</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th>Module</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th>Action</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th>Variables</th>';
        $buffer .= "\n";
        $buffer .= $indent . '			<th></th>';
        $buffer .= "\n";
        $buffer .= $indent . '		</tr>';
        $buffer .= "\n";
        $buffer .= $indent . '	</thead>';
        $buffer .= "\n";
        $buffer .= $indent . '	<tbody>';
        $buffer .= "\n";
        // 'routes' section
        $buffer .= $this->section68c7e0e0190beb1b795e211af78563ec($context, $indent, $context->find('routes'));
        $buffer .= $indent . '	</tbody>';
        $buffer .= "\n";
        $buffer .= $indent . '</table>';
        $buffer .= "\n";
        // 'routes' inverted section
        $value = $context->find('routes');
        if (empty($value)) {
            
            $buffer .= $indent . '	<p class="alert alert-info">Il n\'y a actuellement aucun projet dans la base de donn&eacute;es.</p>';
            $buffer .= "\n";
        }

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionBd18f5051121eb2ad9b17a55ac2f5147(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '<a class="btn btn-danger" href="routes-delete-{{app}}-{{id}}.html"><i class="icon-trash icon-white"></i>&nbsp;Supprimer</a>';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= '<a class="btn btn-danger" href="routes-delete-';
                $value = $context->find('app');
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
                $buffer .= '.html"><i class="icon-trash icon-white"></i>&nbsp;Supprimer</a>';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section68c7e0e0190beb1b795e211af78563ec(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<tr>
				<td><code>{{url}}</code></td>
				<td>{{module}}</td>
				<td>{{action}}</td>
				<td>{{varsList}}</td>
				<td>
					<div class="btn-group">
						{{#editable?}}<a class="btn btn-danger" href="routes-delete-{{app}}-{{id}}.html"><i class="icon-trash icon-white"></i>&nbsp;Supprimer</a>{{/editable?}}
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
                $buffer .= $indent . '				<td><code>';
                $value = $context->find('url');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</code></td>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td>';
                $value = $context->find('module');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</td>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td>';
                $value = $context->find('action');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</td>';
                $buffer .= "\n";
                $buffer .= $indent . '				<td>';
                $value = $context->find('varsList');
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
                $buffer .= $indent . '						';
                // 'editable?' section
                $buffer .= $this->sectionBd18f5051121eb2ad9b17a55ac2f5147($context, $indent, $context->find('editable?'));
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