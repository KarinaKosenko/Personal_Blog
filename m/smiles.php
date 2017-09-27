<?php

namespace M;

/**
 * Class Smiles - a model to work with smiles.
 */
class Smiles
{
    use \Core\Traits\Singleton;

    /**
     * Method convert smile symbol to image.
     */
    public function smile($var)
    {
        $symbol = [
            ':mellow:',
            '<_<',
            ':)',
            ':wub:',
            ':angry:',
            ':(',
            ':unsure:',
            ':wacko:',
            ':blink:',
            '-_-',
            ':rolleyes:',
            ':huh:',
            '^_^',
            ':o',
            ';)',
            ':P',
            ':D',
            ':lol:',
            'B)',
            ':ph34r:'
        ];

        $graph = [
            '<img src="/images/smiles/mellow.png">',
            '<img src="/images/smiles/dry.png">',
            '<img src="/images/smiles/smile.png">',
            '<img src="/images/smiles/wub.png">',
            '<img src="/images/smiles/angry.png">',
            '<img src="/images/smiles/sad.png">',
            '<img src="/images/smiles/unsure.png">',
            '<img src="/images/smiles/wacko.png">',
            '<img src="/images/smiles/blink.png">',
            '<img src="/images/smiles/sleep.png">',
            '<img src="/images/smiles/rolleyes.gif">',
            '<img src="/images/smiles/huh.png">',
            '<img src="/images/smiles/happy.png">',
            '<img src="/images/smiles/ohmy.png">',
            '<img src="/images/smiles/wink.png">',
            '<img src="/images/smiles/tongue.png">',
            '<img src="/images/smiles/biggrin.png">',
            '<img src="/images/smiles/laugh.png">',
            '<img src="/images/smiles/cool.png">',
            '<img src="/images/smiles/ph34r.png">'
        ];

        return str_replace($symbol, $graph, $var);
    }

}


