<?php

class __1778582548f9a0b9374d18dcf52baf16 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<!DOCTYPE html>';
        $buffer .= "\n";
        $buffer .= $indent . '<html>';
        $buffer .= "\n";
        $buffer .= $indent . '<head>';
        $buffer .= "\n";
        $buffer .= $indent . '	<meta charset="UTF-8">';
        $buffer .= "\n";
        $buffer .= $indent . '	<meta name="viewport" content="width=device-width, initial-scale=1.0">';
        $buffer .= "\n";
        $buffer .= $indent . '	<meta name="description" content="';
        $value = $context->find('WEBSITE_DESCRIPTION');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" />';
        $buffer .= "\n";
        $buffer .= $indent . '	<meta name="author" content="';
        $value = $context->find('WEBSITE_AUTHOR');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '" />';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<title>test';
        // 'title' section
        $buffer .= $this->sectionB83ad67a8c65d09ba1ee7ae3bf754c5f($context, $indent, $context->find('title'));
        $value = $context->find('WEBSITE_NAME');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '</title>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<!-- Bootstrap -->';
        $buffer .= "\n";
        $buffer .= $indent . '	<link href="';
        $value = $context->find('WEBSITE_ROOT');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '/css/bootstrap.min.css" rel="stylesheet" media="screen">';
        $buffer .= "\n";
        $buffer .= $indent . '	<link href="';
        $value = $context->find('WEBSITE_ROOT');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen">';
        $buffer .= "\n";
        $buffer .= $indent . '</head>';
        $buffer .= "\n";
        $buffer .= $indent . '<body>';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="navbar-wrapper">';
        $buffer .= "\n";
        $buffer .= $indent . '		<div class="navbar navbar-static-top navbar-inverse">';
        $buffer .= "\n";
        $buffer .= $indent . '			<div class="navbar-inner">';
        $buffer .= "\n";
        $buffer .= $indent . '				<div class="container">';
        $buffer .= "\n";
        $buffer .= $indent . '					<a class="brand" href="';
        $value = $context->find('WEBSITE_ROOT');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '/">';
        $value = $context->find('WEBSITE_NAME');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= $value;
        $buffer .= '</a>';
        $buffer .= "\n";
        $buffer .= $indent . '					';
        $buffer .= "\n";
        $buffer .= $indent . '					<!-- .btn-navbar is used as the toggle for collapsed navbar content -->';
        $buffer .= "\n";
        $buffer .= $indent . '					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">';
        $buffer .= "\n";
        $buffer .= $indent . '						<span class="icon-bar"></span>';
        $buffer .= "\n";
        $buffer .= $indent . '						<span class="icon-bar"></span>';
        $buffer .= "\n";
        $buffer .= $indent . '						<span class="icon-bar"></span>';
        $buffer .= "\n";
        $buffer .= $indent . '					</a>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '					<div class="nav-collapse collapse">';
        $buffer .= "\n";
        $buffer .= $indent . '						<ul class="nav">';
        $buffer .= "\n";
        $buffer .= $indent . '							<li><a href="';
        $value = $context->find('WEBSITE_ROOT');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '/">Accueil</a></li>';
        $buffer .= "\n";
        $buffer .= $indent . '						</ul>';
        $buffer .= "\n";
        $buffer .= $indent . '					</div><!--/.nav-collapse -->';
        $buffer .= "\n";
        $buffer .= $indent . '				</div>';
        $buffer .= "\n";
        $buffer .= $indent . '			</div>';
        $buffer .= "\n";
        $buffer .= $indent . '		</div>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<p></p><!-- To add a space between the navbar and the content -->';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	';
        $value = $context->find('content');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= $value;
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="container">';
        $buffer .= "\n";
        $buffer .= $indent . '		<hr />';
        $buffer .= "\n";
        $buffer .= $indent . '		';
        $buffer .= "\n";
        $buffer .= $indent . '		<!-- FOOTER -->';
        $buffer .= "\n";
        $buffer .= $indent . '		<footer>';
        $buffer .= "\n";
        $buffer .= $indent . '			<p class="pull-right">';
        $buffer .= "\n";
        $buffer .= $indent . '				<span class="muted">Propulsé par <a href="http://github.com/simonser/lighp">Lighp</a></span> &middot; <a href="#">Retour en haut</a>';
        $buffer .= "\n";
        $buffer .= $indent . '			</p>';
        $buffer .= "\n";
        $buffer .= $indent . '			<p>';
        $buffer .= "\n";
        $buffer .= $indent . '				<!--[if lte IE 8]><span style="filter: FlipH; -ms-filter: "FlipH"; display: inline-block;"><![endif]--><span style="-moz-transform: scaleX(-1); -o-transform: scaleX(-1); -webkit-transform: scaleX(-1); transform: scaleX(-1); display: inline-block;">&copy;</span><!--[if lte IE 8]></span><![endif]--> 2013 ';
        $value = $context->find('WEBSITE_AUTHOR');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= ' &middot; <a href="';
        $value = $context->find('WEBSITE_ROOT');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '/index.html">Accueil</a>';
        $buffer .= "\n";
        $buffer .= $indent . '			</p>';
        $buffer .= "\n";
        $buffer .= $indent . '		</footer>';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	</div><!-- /.container -->';
        $buffer .= "\n";
        $buffer .= "\n";
        $buffer .= $indent . '	<script type="text/javascript" src="';
        $value = $context->find('WEBSITE_ROOT');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '/js/jquery-1.9.1.min.js"></script>';
        $buffer .= "\n";
        $buffer .= $indent . '	<script type="text/javascript" src="';
        $value = $context->find('WEBSITE_ROOT');
        if (!is_string($value) && is_callable($value)) {
            $value = $this->mustache
                ->loadLambda((string) call_user_func($value))
                ->renderInternal($context, $indent);
        }
        $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
        $buffer .= '/js/bootstrap.min.js"></script>';
        $buffer .= "\n";
        $buffer .= $indent . '</body>';
        $buffer .= "\n";
        $buffer .= $indent . '</html>';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function sectionB83ad67a8c65d09ba1ee7ae3bf754c5f(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '{{title}} - ';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $value = $context->find('title');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= ' - ';
                $context->pop();
            }
        }
    
        return $buffer;
    }
}