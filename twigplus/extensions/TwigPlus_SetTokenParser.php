<?php
namespace Craft;

use Twig_Token;
use Twig_TokenParser;
use Twig_Error_Syntax;


class TwigPlus_SetTokenParser extends Twig_TokenParser
{
    public function parse(Twig_Token $token)
    {
        $line   = $token->getLine();
        $stream = $this->parser->getStream();

        /**
         * @var \Twig_Node $names
         */
        $names   = $this->parser->getExpressionParser()->parseAssignmentExpression();
        $capture = false;
        $name    = '';

        if ($stream->nextIf(Twig_Token::PUNCTUATION_TYPE))
        {
            $copy = null;

            foreach ($names->getIterator() as &$node)
            {
                $copy = $node;
                $name = $node->getAttribute('name');
            }

            while (true)
            {
                if (!$stream->nextIf(Twig_Token::NAME_TYPE) && !$stream->nextIf(Twig_Token::PUNCTUATION_TYPE))
                {
                    $copy->setAttribute('name', $name);
                    break;
                }

                $name = $name.$stream->getCurrent()->getValue();
            }
            $values = $this->parser->getExpressionParser()->parseMultitargetExpression();

            $stream->expect(Twig_Token::BLOCK_END_TYPE);

            if (count($names) !== count($values)) {
                throw new Twig_Error_Syntax('When using set, you must have the same number of variables and assignments.',
                    $stream->getCurrent()->getLine(), $stream->getFilename());
            }
        }

        if ($stream->nextIf(Twig_Token::OPERATOR_TYPE, '=')) {
            $values = $this->parser->getExpressionParser()->parseMultitargetExpression();

            $stream->expect(Twig_Token::BLOCK_END_TYPE);

            if (count($names) !== count($values)) {
                throw new Twig_Error_Syntax('When using set, you must have the same number of variables and assignments.',
                    $stream->getCurrent()->getLine(), $stream->getFilename());
            }
        } else {
            $capture = true;

            if (count($names) > 1) {
                throw new Twig_Error_Syntax('When using set with a block, you cannot have a multi-target.',
                    $stream->getCurrent()->getLine(), $stream->getFilename());
            }

            $stream->expect(Twig_Token::BLOCK_END_TYPE);

            $values = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
            $stream->expect(Twig_Token::BLOCK_END_TYPE);
        }

        return new TwigPlus_SetNode($capture, $names, $values, $line, $this->getTag());
    }

    public function decideBlockEnd(Twig_Token $token)
    {
        return $token->test('endset');
    }

    public function getTag()
    {
        return 'set';
    }
}
