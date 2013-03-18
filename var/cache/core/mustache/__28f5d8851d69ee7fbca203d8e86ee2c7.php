<?php

class __28f5d8851d69ee7fbca203d8e86ee2c7 extends core\mustache\Template
{
    private $lambdaHelper;

    public function renderInternal(core\mustache\Context $context, $indent = '', $escape = false)
    {
        $this->lambdaHelper = new core\mustache\LambdaHelper($this->mustache, $context);
        $buffer = '';

        $buffer .= $indent . '<div class="container">';
        $buffer .= "\n";
        $buffer .= $indent . '	<img src="img/contact/icon-medium.png" alt="" class="pull-right" />';
        $buffer .= "\n";
        $buffer .= $indent . '	<div class="page-header">';
        $buffer .= "\n";
        $buffer .= $indent . '		<h1>Contact <small> - n\'h&eacute;sitez pas &agrave; me contacter !</small></h1>';
        $buffer .= "\n";
        $buffer .= $indent . '		<p class="lead">Pour me contacter, remplissez le formulaire ci-dessous.</p>';
        $buffer .= "\n";
        $buffer .= $indent . '	</div>';
        $buffer .= "\n";
        $buffer .= "\n";
        // 'messageSent?' section
        $buffer .= $this->section76a33f4055e73044b635b7ccd1e8f44d($context, $indent, $context->find('messageSent?'));
        $buffer .= "\n";
        // 'messageSent?' inverted section
        $value = $context->find('messageSent?');
        if (empty($value)) {
            
            // 'error' section
            $buffer .= $this->sectionFb24815454705933bb2e4ed51e1d56e7($context, $indent, $context->find('error'));
            $buffer .= "\n";
            $buffer .= $indent . '		<form action="" method="post" class="form-horizontal">';
            $buffer .= "\n";
            $buffer .= $indent . '			<input type="hidden" name="captcha-id" value="';
            $value = $context->findDot('captcha.id');
            if (!is_string($value) && is_callable($value)) {
                $value = $this->mustache
                    ->loadLambda((string) call_user_func($value))
                    ->renderInternal($context, $indent);
            }
            $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $buffer .= '"/>';
            $buffer .= "\n";
            $buffer .= "\n";
            $buffer .= $indent . '			<div class="control-group">';
            $buffer .= "\n";
            $buffer .= $indent . '				<label class="control-label" for="inputName">Nom</label>';
            $buffer .= "\n";
            $buffer .= $indent . '				<div class="controls">';
            $buffer .= "\n";
            $buffer .= $indent . '					<input type="text" name="message-sender-name" id="inputName" placeholder="Nom" value="';
            $value = $context->findDot('message.senderName');
            if (!is_string($value) && is_callable($value)) {
                $value = $this->mustache
                    ->loadLambda((string) call_user_func($value))
                    ->renderInternal($context, $indent);
            }
            $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $buffer .= '" required/>';
            $buffer .= "\n";
            $buffer .= $indent . '				</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			<div class="control-group">';
            $buffer .= "\n";
            $buffer .= $indent . '				<label class="control-label" for="inputEmail">E-mail</label>';
            $buffer .= "\n";
            $buffer .= $indent . '				<div class="controls">';
            $buffer .= "\n";
            $buffer .= $indent . '					<input type="email" name="message-sender-email" id="inputEmail" placeholder="E-mail" value="';
            $value = $context->findDot('message.senderEmail');
            if (!is_string($value) && is_callable($value)) {
                $value = $this->mustache
                    ->loadLambda((string) call_user_func($value))
                    ->renderInternal($context, $indent);
            }
            $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $buffer .= '" required/>';
            $buffer .= "\n";
            $buffer .= $indent . '				</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			<div class="control-group">';
            $buffer .= "\n";
            $buffer .= $indent . '				<label class="control-label" for="inputSubject">Sujet</label>';
            $buffer .= "\n";
            $buffer .= $indent . '				<div class="controls">';
            $buffer .= "\n";
            $buffer .= $indent . '					<input type="text" name="message-subject" id="inputSubject" placeholder="Sujet" class="input-xlarge" value="';
            $value = $context->findDot('message.subject');
            if (!is_string($value) && is_callable($value)) {
                $value = $this->mustache
                    ->loadLambda((string) call_user_func($value))
                    ->renderInternal($context, $indent);
            }
            $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $buffer .= '"/>';
            $buffer .= "\n";
            $buffer .= $indent . '				</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			<div class="control-group">';
            $buffer .= "\n";
            $buffer .= $indent . '				<label class="control-label" for="inputContent">Message</label>';
            $buffer .= "\n";
            $buffer .= $indent . '				<div class="controls">';
            $buffer .= "\n";
            $buffer .= $indent . '					<textarea id="inputContent" name="message-content" placeholder="Message" rows="10" cols="50" style="width: 100%;" required>';
            $value = $context->findDot('message.content');
            if (!is_string($value) && is_callable($value)) {
                $value = $this->mustache
                    ->loadLambda((string) call_user_func($value))
                    ->renderInternal($context, $indent);
            }
            $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $buffer .= '</textarea>';
            $buffer .= "\n";
            $buffer .= $indent . '				</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			<div class="control-group">';
            $buffer .= "\n";
            $buffer .= $indent . '				<label class="control-label" for="inputCaptcha">';
            $value = $context->findDot('captcha.question');
            if (!is_string($value) && is_callable($value)) {
                $value = $this->mustache
                    ->loadLambda((string) call_user_func($value))
                    ->renderInternal($context, $indent);
            }
            $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
            $buffer .= '</label>';
            $buffer .= "\n";
            $buffer .= $indent . '				<div class="controls">';
            $buffer .= "\n";
            $buffer .= $indent . '					<input type="number" name="captcha-value" id="inputCaptcha" placeholder="R&eacute;sultat" class="input-small" required/><span class="help-inline"><span class="muted">Ceci est un code de v&eacute;rification pour &eacute;viter l\'envoi d\'e-mails non d&eacute;sirables.</span></span>';
            $buffer .= "\n";
            $buffer .= $indent . '				</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '			<div class="form-actions">';
            $buffer .= "\n";
            $buffer .= $indent . '				<button type="submit" class="btn btn-primary">Valider</button>';
            $buffer .= "\n";
            $buffer .= $indent . '			</div>';
            $buffer .= "\n";
            $buffer .= $indent . '		</form>';
            $buffer .= "\n";
        }
        $buffer .= $indent . '</div><!-- /.container -->';

        if ($escape) {
            return htmlspecialchars($buffer, ENT_COMPAT, 'UTF-8');
        } else {
            return $buffer;
        }
    }

    private function section76a33f4055e73044b635b7ccd1e8f44d(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="alert alert-block alert-success fade in">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<h4>Mail envoy&eacute; !</h4>
			Votre message a bien &eacute;t&eacute; envoy&eacute;, vous recevrez une r&eacute;ponse prochainement.
		</div>
	';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '		<div class="alert alert-block alert-success fade in">';
                $buffer .= "\n";
                $buffer .= $indent . '			<button type="button" class="close" data-dismiss="alert">×</button>';
                $buffer .= "\n";
                $buffer .= $indent . '			<h4>Mail envoy&eacute; !</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '			Votre message a bien &eacute;t&eacute; envoy&eacute;, vous recevrez une r&eacute;ponse prochainement.';
                $buffer .= "\n";
                $buffer .= $indent . '		</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFb24815454705933bb2e4ed51e1d56e7(core\mustache\Context $context, $indent, $value) {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
			<div class="alert alert-block alert-error fade in">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<h4>Erreur</h4>
				<p>Une erreur est survenue : {{error}}</p>
			</div>
		';
            $buffer .= $this->mustache
                ->loadLambda((string) call_user_func($value, $source, $this->lambdaHelper))
                ->renderInternal($context, $indent);
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                $buffer .= $indent . '			<div class="alert alert-block alert-error fade in">';
                $buffer .= "\n";
                $buffer .= $indent . '				<button type="button" class="close" data-dismiss="alert">×</button>';
                $buffer .= "\n";
                $buffer .= $indent . '				<h4>Erreur</h4>';
                $buffer .= "\n";
                $buffer .= $indent . '				<p>Une erreur est survenue : ';
                $value = $context->find('error');
                if (!is_string($value) && is_callable($value)) {
                    $value = $this->mustache
                        ->loadLambda((string) call_user_func($value))
                        ->renderInternal($context, $indent);
                }
                $buffer .= htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
                $buffer .= '</p>';
                $buffer .= "\n";
                $buffer .= $indent . '			</div>';
                $buffer .= "\n";
                $context->pop();
            }
        }
    
        return $buffer;
    }
}